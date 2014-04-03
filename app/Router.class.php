<?php

/**
 * Router 
 *
 * @version 1.2
 * @author MPI
 * */
class Router{
	private $table = array();

	public function __construct(){
	}

	/**
	 * Get route by it's name.
	 *
	 * @param string $route_name
	 *        	(if not found route, returns default route)
	 * @return 1D string array
	 */
	public function getRoute($route_name){
		$route_name = strtolower($route_name);
		
		// Return a default route if no route is found
		if($this->isRoute($route_name) === false){
			return $this->table["default"];
		}
		
		return $this->table[$route_name];
	}

	/**
	 * Add new route to router
	 *
	 * @param string $route_name        	
	 * @param string $controller_name        	
	 * @param string $view_name        	
	 * @param string $model_name        	
	 * @return bool
	 */
	public function addRoute($route_name, $controller_name, $view_name, $model_name){
		$route_name = strtolower($route_name);
		if($this->isRoute($route_name) === false && class_exists($model_name) && class_exists($view_name) && class_exists($controller_name)){
			$this->table[$route_name] = new Route($model_name, $view_name, $controller_name);
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Check if route exists.
	 *
	 * @param string $route_name        	
	 * @return true (id route exists) | false (if route doesn't exist)
	 */
	public function isRoute($route_name){
		$route_name = strtolower($route_name);
		return array_key_exists($route_name, $this->table);
	}
}
?>