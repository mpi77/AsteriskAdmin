<?php
/**
 * Index controller.
 *
 * @version 1.1
 * @author MPI
 * */
class IndexController extends Controller{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show index page.
	 */
	public function index(){
	}
}
?>