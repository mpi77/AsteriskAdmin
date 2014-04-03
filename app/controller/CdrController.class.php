<?php
/**
 * Cdr controller.
 *
 * @version 1.3
 * @author MPI
 * */
class CdrController extends Controller{
	const DISPOSITION_NO_ANSWER = "NO ANSWER";
	const DISPOSITION_FAILED = "FAILED";
	const DISPOSITION_BUSY = "BUSY";
	const DISPOSITION_ANSWERED = "ANSWERED";

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show cdr list.
	 *
	 * @throws NoticeException
	 */
	public function lst(){
		$args = $this->getArgs();
		// show cdr list can only logged user
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		$user_lines = $this->getModel()->getUserLines($_SESSION["user"]["uid"]);
		if($user_lines != Database::EMPTY_RESULT && is_array($user_lines)){
			$line_id = "0";
			if($_SERVER["REQUEST_METHOD"] == "GET" && !isset($args["GET"]["line"])){
				$line_id = $user_lines[0][0];
			}
			
			// can display only own line cdr
			$line_id = (!empty($args["POST"]["select_cdr_line"])) ? $args["POST"]["select_cdr_line"] : (!empty($args["GET"]["line"]) ? $args["GET"]["line"] : ($line_id > 0 ? $line_id : "0"));
			if(!is_numeric($line_id) || $line_id == "0"){
				System::setViewDisabled();
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			$line = $this->getModel()->getLineData($line_id);
			if($line == Database::EMPTY_RESULT || !is_array($line) || $line[1] != $_SESSION["user"]["uid"]){
				System::setViewDisabled();
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
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