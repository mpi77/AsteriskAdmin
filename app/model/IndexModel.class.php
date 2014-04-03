<?php
/**
 * Index model.
 *
 * @version 1.1
 * @author MPI
 * */
class IndexModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}
}
?>