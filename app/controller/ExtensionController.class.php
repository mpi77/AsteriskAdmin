<?php
/**
 * Extension controller.
 *
 * @version 1.18
 * @author MPI
 * */
class ExtensionController extends Controller{
	private static $validation_table = array(
			"context" => "/^[a-zA-Z0-9,\.-]{1,20}$/i",
			"line" => "/^[0-9_ZXis\(\)\[\]\{\}\*]{1,30}$/i",
			"priority" => "/^[0-9]{1,3}$/i",
			"app" => "/^[a-zA-Z0-9,\.-_]{1,20}$/i",
			"appdata" => "/^[a-zA-Z0-9,\.-_@\$\?:\/\(\)\[\]\{\}=\"\' ]{1,100}$/i",
			"confirm_delete" => "/^yes$/i",
			"pstn_number" => "/^(00[0-9]{3})?([0-9]{9})$/i"
	);
	const EXT_CONTEXT_INCOMING = "incoming";
	const EXT_CONTEXT_INTERNAL = "internal";
	const EXT_CONTEXT_OUTGOING = "outgoing";
	const EXT_APP_DIAL = "Dial";
	const EXT_APP_HANGUP = "Hangup";
	const EXT_APP_NOOP = "Noop";
	const EXT_APP_GOTOIF = "GotoIf";
	const EXT_APP_GOTO = "Goto";
	const EXT_APP_SET = "Set";
	const EXT_DEFAULT_PRIORITY = "1";
	const EXT_OUTGOING_DIAL_PRIORITY = "3";
	const EXT_OUTGOING_DEFAULT_CONDITION_LINE = "99199";
	const EXT_OUTGOING_GOTOIF_PATTERN = "$[\"\${CALLERID(num)}\" == \"%d\"]?%d:%d";
	const EXT_OUTGOING_EXTENSION = "_00420[23456789]XXXXXXXX";
	const EXT_OUTGOING_PEER = "to-odorik";

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}
	
	public function getName(){
		return get_class($this);
	}

	/**
	 * Show extension list.
	 * 
	 * @throws NoticeException
	 */
	public function lst(){
		$args = $this->getArgs();
		// show extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
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
	
	/**
	 * Create new extension.
	 * 
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function create(){
		$args = $this->getArgs();
		// create extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
	
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(preg_match(self::getRegexp("context"), $args["POST"]["ext_context"]) === 1 && preg_match(self::getRegexp("line"), $args["POST"]["ext_line"]) === 1 && preg_match(self::getRegexp("priority"), $args["POST"]["ext_priority"]) === 1 && preg_match(self::getRegexp("app"), $args["POST"]["ext_app"]) === 1 && preg_match(self::getRegexp("appdata"), $args["POST"]["ext_appdata"]) === 1){
				// create extension
				$r = $this->getModel()->createExtension($_SESSION["user"]["uid"], $args["POST"]["ext_context"], $args["POST"]["ext_line"], $args["POST"]["ext_priority"], $args["POST"]["ext_app"], $args["POST"]["ext_appdata"]);
				if($r === true){
					System::redirect(sprintf("%s%s", Config::SITE_PATH, "extension/lst/"));
					// throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
		}
	}

	/**
	 * Edit extension form.
	 * 
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function edit(){
		$args = $this->getArgs();
		// edit extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		$ext = $this->getModel()->getData($args["GET"]["id"]);
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $ext == Database::EMPTY_RESULT || !is_array($ext)){
			// extension does not exist
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(preg_match(self::getRegexp("context"), $args["POST"]["ext_context"]) === 1 && preg_match(self::getRegexp("line"), $args["POST"]["ext_line"]) === 1 && preg_match(self::getRegexp("priority"), $args["POST"]["ext_priority"]) === 1 && preg_match(self::getRegexp("app"), $args["POST"]["ext_app"]) === 1 && preg_match(self::getRegexp("appdata"), $args["POST"]["ext_appdata"]) === 1){
				$r = $this->getModel()->saveExtension($_SESSION["user"]["uid"], $args["GET"]["id"],$args["POST"]["ext_context"], $args["POST"]["ext_line"], $args["POST"]["ext_priority"], $args["POST"]["ext_app"], $args["POST"]["ext_appdata"]);
				if($r === true){
					throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
		}
	}

	/**
	 * Remove extension from dialplan by id.
	 * 
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function delete(){
		$args = $this->getArgs();
		// delete extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		$ext = $this->getModel()->getData($args["GET"]["id"]);
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $ext == Database::EMPTY_RESULT || !is_array($ext)){
			// extension does not exist
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(array_key_exists("confirm_delete", $args["POST"]) && preg_match(self::getRegexp("confirm_delete"), $args["POST"]["confirm_delete"]) === 1){
				$r = $this->getModel()->remove($_SESSION["user"]["uid"], $args["GET"]["id"]);
				if($r === true){
					System::redirect(sprintf("%s%s", Config::SITE_PATH, "extension/lst/"));
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
		}
	}

	/**
	 * Show PSTN extension list.
	 * 
	 * @throws NoticeException
	 */
	public function pstnlst(){
		$args = $this->getArgs();
		// show pstn extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
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
	
	/**
	 * Create PSTN line.
	 * 
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function pstncreate(){
		$args = $this->getArgs();
		// create extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
	
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(preg_match(self::getRegexp("pstn_number"), $args["POST"]["pstn_number"]) === 1){
				// create pstn extension
				$pstn_number = (preg_match("/^([0-9]{9})$/i", $args["POST"]["pstn_number"]) === 1) ? "00420" . $args["POST"]["pstn_number"] : $args["POST"]["pstn_number"];
				$r = $this->getModel()->createPstn($_SESSION["user"]["uid"], $pstn_number);
				if($r === true){
					System::redirect(sprintf("%s%s", Config::SITE_PATH, "extension/pstnlst/"));
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
		}
	}
	
	/**
	 * Cancel PSTN.
	 * 
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function pstncancel(){
		$args = $this->getArgs();
		// edit extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		$ext = $this->getModel()->getData($args["GET"]["id"]);
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $ext == Database::EMPTY_RESULT || !is_array($ext)){
			// extension does not exist
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
	
		if($_SERVER["REQUEST_METHOD"] == "GET"){
			$pstn_number = substr($ext[2], 1);
			$r = $this->getModel()->unregisterPstn($_SESSION["user"]["uid"], $pstn_number);
			if($r === true){
				System::redirect(sprintf("%s%s", Config::SITE_PATH, "extension/pstnlst/"));
			}else{
				System::setViewDisabled();
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
		}
	}
	
	/**
	 * Remove PSTN.
	 * 
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function pstndelete(){
		$args = $this->getArgs();
		// delete extensions can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		$ext = $this->getModel()->getData($args["GET"]["id"]);
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $ext == Database::EMPTY_RESULT || !is_array($ext)){
			// extension does not exist
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
	
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(array_key_exists("confirm_delete", $args["POST"]) && preg_match(self::getRegexp("confirm_delete"), $args["POST"]["confirm_delete"]) === 1){
				$r = $this->getModel()->removePstn($_SESSION["user"]["uid"], substr($ext[2], 1));
				if($r === true){
					System::redirect(sprintf("%s%s", Config::SITE_PATH, "extension/pstnlst/"));
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
		}
	}
	
	public static function getRegexp($key){
		return self::$validation_table[$key];
	}
}
?>