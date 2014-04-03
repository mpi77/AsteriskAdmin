<?php
/**
 * User view.
 *
 * @version 1.8
 * @author MPI
 * */
class UserView extends View{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show user log list.
	 */
	public function log(){
		$args = $this->getArgs();
		$uid = $_SESSION["user"]["uid"];
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::USER_LOG_LIST_HEADER_TIME)),
				sprintf("%s", Translate::get(Translator::USER_LOG_LIST_HEADER_VALUE))
		);
		
		$config = array(
				"config" => array(
						"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
						"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
						"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
						"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
						"data_count" => $this->getModel()->getCountLogList($uid),
						"disable_menu" => true,
						"disable_select" => true,
						"disable_pagging" => false,
						"disable_set_pagesize" => false
				),
				"form_url" => array(
						"page" => $base_url . "-%d-%d-%s",
						"header_sort" => $base_url . "-%d-%d-%s",
						"form_action" => $base_url
				),
				"item_menu" => array(),
				"select_item_action" => array(),
				"style" => array(
						"marked_row_class" => "marked",
						"count_box_class" => "count_box",
						"pagging_box_class" => "pagging_box",
						"actual_page_class" => "actual_page",
						"table_header_class" => "head",
						"table_id" => "user_log",
						"select_form_id" => "",
						"pagesize_form_id" => "pagesize_box",
						"list_footer_id" => ""
				)
		);
		$data = $this->getModel()->getLogList($uid, $config["config"]["page"], $config["config"]["actual_pagesize"], $config["config"]["column"], $config["config"]["direction"], $config["config"]["disable_pagging"]);
		$out = Paginator::generatePage($header, $data, $config);
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Show list of existing users.
	 */
	public function lst(){
		$args = $this->getArgs();
		$uid = $_SESSION["user"]["uid"];
		$base_url = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") + 1);
		$header = array(
				sprintf("%s", Translate::get(Translator::USER_LIST_HEADER_ID)),
				sprintf("%s", Translate::get(Translator::USER_LIST_HEADER_EMAIL)),
				sprintf("%s", Translate::get(Translator::USER_LIST_HEADER_FNAME)),
				sprintf("%s", Translate::get(Translator::USER_LIST_HEADER_LNAME)),
				sprintf("%s", Translate::get(Translator::USER_LIST_HEADER_PHONE))
		);
		
		$config = array(
				"config" => array(
						"page" => (isset($args["GET"]["page"]) ? $args["GET"]["page"] : System::PAGE_ACTUAL_DEFAULT),
						"column" => (isset($args["GET"]["column"]) ? $args["GET"]["column"] : System::SORT_DEFAULT_COLUMN),
						"direction" => (isset($args["GET"]["direction"]) ? $args["GET"]["direction"] : System::SORT_DES),
						"actual_pagesize" => (isset($_SESSION["page_size"]) ? $_SESSION["page_size"] : System::PAGE_SIZE_DEFAULT),
						"data_count" => $this->getModel()->getCountUserList(),
						"disable_menu" => true,
						"disable_select" => true,
						"disable_pagging" => false,
						"disable_set_pagesize" => false
				),
				"form_url" => array(
						"page" => $base_url . "-%d-%d-%s",
						"header_sort" => $base_url . "-%d-%d-%s",
						"form_action" => $base_url
				),
				"item_menu" => array(),
				"select_item_action" => array(),
				"style" => array(
						"marked_row_class" => "marked",
						"count_box_class" => "count_box",
						"pagging_box_class" => "pagging_box",
						"actual_page_class" => "actual_page",
						"table_header_class" => "head",
						"table_id" => "user_lst",
						"select_form_id" => "",
						"pagesize_form_id" => "pagesize_box",
						"list_footer_id" => ""
				)
		);
		$data = $this->getModel()->getUserList($config["config"]["page"], $config["config"]["actual_pagesize"], $config["config"]["column"], $config["config"]["direction"], $config["config"]["disable_pagging"]);
		$out = Paginator::generatePage($header, $data, $config);
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Login user.
	 */
	public function login(){
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Logout user.
	 */
	public function logout(){
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Edit user form.
	 */
	public function edit(){
		$args = $this->getArgs();
		$out = $this->getModel()->getUserData($args["GET"]["id"]);
		$out2 = "";
		foreach(Translator::getLanguage(null) as $k => $v){
			$out2 .= sprintf("<option value=\"%d\"%s>%s</option>", $k, ($k == $out[7] ? " selected=\"selected\"" : ""), $v);
		}
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Renew user password (request for token).
	 */
	public function renew(){
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Set lost password for user by renew token.
	 */
	public function setpassword(){
		$args = $this->getArgs();
		$out = $this->getModel()->getUserDataByRenewToken(UserController::getRenewTokenHash($args["GET"]["token"]));
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Create request for adding new user.
	 */
	public function addrequest(){
		include 'gui/template/UserTemplate.php';
	}

	/**
	 * Create new user with ucr token.
	 */
	public function create(){
		$args = $this->getArgs();
		$out = $this->getModel()->getUcrDataByToken(UserController::getUcrRequestTokenHash($args["GET"]["token"]));
		include 'gui/template/UserTemplate.php';
	}

	public function outputJson(){
	}

	public function outputHtml(){
		if(System::isCallable($this, $this->getActionName()) === true){
			$this->{$this->getActionName()}();
		}else{
			throw new WarningException(WarningException::WARNING_ACTION_IS_NOT_CALLABLE);
		}
	}
}
?>