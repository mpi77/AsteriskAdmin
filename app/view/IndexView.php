<?php
/**
 * Index view.
 *
 * @version 1.1
 * @author MPI
 * */
class IndexView extends View{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	public function outputJson(){
	}

	public function outputHtml(){
		include 'gui/template/IndexTemplate.php';
	}
}
?>