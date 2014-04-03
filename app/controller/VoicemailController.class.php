<?php
/**
 * Voicemail controller.
 *
 * @version 1.4
 * @author MPI
 * */
class VoicemailController extends Controller{
	private static $validation_table = array(
			"password" => "/^[0-9]{4,8}$/i"
	);
	const ASTERISK_VOICEMAIL_PATH = "/home/martin/public_html/voicemail/"; // CentOS: /var/spool/asterisk/voicemail/
	const ASTERISK_LINE_INBOX_PATH = "%s/%d/INBOX/";
	const ASTERISK_FILENAME_PREFIX = "msg";
	const ASTERISK_WAV_SUFFIX = ".wav";
	const ASTERISK_TXT_SUFFIX = ".txt";
	const ASTERISK_GSM_SUFFIX = ".gsm";
	const ASTERISK_STARTING_INDEX = 1;
	const DEFAULT_EMAIL = "";
	const DEFAULT_PAGER = "";
	const DEFAULT_TZ = "central";
	const DEFAULT_ATTACH = "yes";
	const DEFAULT_SAYCID = "yes";
	const DEFAULT_DIALOUT = "";
	const DEFAULT_CALLBACK = "";

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show voicemail list.
	 *
	 * @throws NoticeException
	 */
	public function lst(){
		$args = $this->getArgs();
		// show line list can only logged user
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		// get one line if it is first access without GET line
		$user_lines = $this->getModel()->getUserLines($_SESSION["user"]["uid"]);
		if($user_lines != Database::EMPTY_RESULT && is_array($user_lines)){
			$line_id = "0";
			if($_SERVER["REQUEST_METHOD"] == "GET" && !isset($args["GET"]["line"])){
				$line_id = $user_lines[0][0];
			}
			
			// can display only own line voicemail
			$line_id = (!empty($args["POST"]["select_voicemail_line"])) ? $args["POST"]["select_voicemail_line"] : (!empty($args["GET"]["line"]) ? $args["GET"]["line"] : ($line_id > 0 ? $line_id : "0"));
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

	/**
	 * Download voicemail message.
	 *
	 * @throws NoticeException
	 */
	public function download(){
		$args = $this->getArgs();
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		System::redirect(sprintf("%sinclude/scripts/download-vm.php?line_id=%d&msg_id=%d", Config::SITE_PATH, $args["GET"]["m"], $args["GET"]["id"]));
	}

	/**
	 * Remove voicemail message.
	 *
	 * @throws NoticeException
	 */
	public function delete(){
		$args = $this->getArgs();
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		if(!empty($args["GET"]["m"]) && !empty($args["GET"]["id"]) && is_numeric($args["GET"]["m"]) && is_numeric($args["GET"]["id"]) && intval($args["GET"]["id"]) >= self::ASTERISK_STARTING_INDEX){
			$r = $this->getModel()->removeMessage($_SESSION["user"]["uid"], $args["GET"]["m"], $args["GET"]["id"]);
			if($r === true){
				System::redirect(sprintf("%svoicemail/lst/?line=%d", Config::SITE_PATH, $args["GET"]["m"]));
			}else{
				System::setViewDisabled();
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
		}else{
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_FILE_IS_NOT_DELETABLE);
		}
	}

	public static function getRegexp($key){
		return self::$validation_table[$key];
	}
}
?>