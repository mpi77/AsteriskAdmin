<?php

/**
 * FrontController
 * 
 * @version 1.11
 * @author MPI
 * */
class FrontController {
	private $controller;
	private $view;
	private $router;
	private $db;
	private $args = array();

	public function __construct() {
		try {
			$this->router = new Router();
			$this->router->addRoute("default", "IndexController", "IndexView", "IndexModel");
			$this->router->addRoute("user", "UserController", "UserView", "UserModel");
			$this->router->addRoute("line", "LineController", "LineView", "LineModel");
			$this->router->addRoute("voicemail", "VoicemailController", "VoicemailView", "VoicemailModel");
			$this->router->addRoute("cdr", "CdrController", "CdrView", "CdrModel");
			$this->router->addRoute("extension", "ExtensionController", "ExtensionView", "ExtensionModel");
			$this->router->addRoute("phonebook", "PhonebookController", "PhonebookView", "PhonebookModel");
			
			$this->db = new Database(Config::getDbParams());
			System::setViewEnabled();
			System::clearException();
		} catch (FailureException $e) {
			Log::saveFailure($e);
			header("Location: " . Config::SITE_PATH . Config::SHUTDOWN_PAGE);
			exit();
		}
		
		// do not change (trim&slash) $_GET and $_POST
		// trim&slash $_GET and $_POST to $this->args and this array give while calling functions
		$this->args["GET"] = System::trimSlashArray1dAssociative($_GET, true, true);
		$this->args["POST"] = System::trimSlashArray1dAssociative($_POST, true, true);
		
		$this->dispatch();
	}

	public function __destruct() {
		if (!empty($this->db)) {
			$this->db->close();
		}
		System::setViewEnabled();
		System::clearException();
	}

	/**
	 * Dispatch user request.
	 */
	private function dispatch() {
		$route_name = isset($this->args["GET"]["route"]) ? $this->args["GET"]["route"] : "default";
		$action_name = isset($this->args["GET"]["action"]) ? $this->args["GET"]["action"] : "index";
		
		try {
			// if route is invalid, redirect to index (route=default, action=index)
			if ($this->router->isRoute($route_name) === false) {
				$action_name = "index";
			}
			$route = $this->router->getRoute($route_name);
			$model_name = $route->getModelName();
			$controller_name = $route->getControllerName();
			$view_name = $route->getViewName();
			
			// var_dump($model_name . " " . $controller_name . " " . $view_name . " " . $action_name);
			
			if (class_exists($model_name) && class_exists($controller_name) && class_exists($view_name)) {
				$model = new $model_name($this->db);
				$this->controller = new $controller_name($model, $this->args);
				$this->view = new $view_name($model, $this->args);
			} else {
				throw new WarningException(WarningException::WARNING_CLASS_NOT_FOUND);
			}
			
			if (System::isCallable($this->controller, $action_name) === true) {
				$this->controller->{$action_name}();
			} else {
				throw new WarningException(WarningException::WARNING_ACTION_IS_NOT_CALLABLE);
			}
			
			if ($this->router->isRoute($route_name) === false) {
				throw new WarningException(WarningException::WARNING_INVALID_ROUTE);
			}
		} catch (NoticeException $e) {
			$_SESSION["exception"] = $e;
		} catch (WarningException $e) {
			Log::saveWarning($this->db, $e);
			System::setViewDisabled();
			$_SESSION["exception"] = $e;
		} catch (FailureException $e) {
			Log::saveFailure($e);
			header("Location: " . Config::SITE_PATH . Config::SHUTDOWN_PAGE);
			exit();
		}
	}

	/**
	 * Generate HTML output.
	 */
	public function output() {
		try {
			System::makeExceptionCont();
			if (!empty($this->view) && System::isViewEnabled()) {
				$this->view->outputHtml();
			}
		} catch (NoticeException $e) {
			$_SESSION["exception"] = $e;
			System::makeExceptionCont();
		} catch (WarningException $e) {
			Log::saveWarning($this->db, $e);
			$_SESSION["exception"] = $e;
			System::makeExceptionCont();
		} catch (FailureException $e) {
			Log::saveFailure($e);
			header("Location: " . Config::SITE_PATH . Config::SHUTDOWN_PAGE);
			exit();
		}
	}
}
?>