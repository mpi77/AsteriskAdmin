<?php
/**
 * Phonebook controller.
 *
 * @version 1.3
 * @author MPI
 * */
class PhonebookController extends Controller{

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show list of available lines.
	 *
	 * @throws NoticeException
	 */
	public function lst(){
		$args = $this->getArgs();
		// show phonebook list can only logged user
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			// set page size
			if(array_key_exists("action_pagesize", $args["POST"]) && is_numeric($args["POST"]["action_pagesize"]) && in_array(intval($args["POST"]["action_pagesize"]), System::$page_size)){
				$_SESSION["page_size"] = intval($args["POST"]["action_pagesize"]);
			}
		}
	}
}
?>