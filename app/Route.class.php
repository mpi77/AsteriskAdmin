<?php

/**
 * Route object.
 *
 * @version 1.0
 * @author MPI
 * */
class Route {
	private $model;
	private $view;
	private $controller;

	public function __construct($model, $view, $controller) {
		$this->model = $model;
		$this->view = $view;
		$this->controller = $controller;
	}

	/**
	 * Get this model name.
	 * 
	 * @return string
	 */
	public function getModelName() {
		return $this->model;
	}
	
	/**
	 * Get this view name.
	 *
	 * @return string
	 */
	public function getViewName() {
		return $this->view;
	}

	/**
	 * Get this controller name.
	 *
	 * @return string
	 */
	public function getControllerName() {
		return $this->controller;
	}
}
?>