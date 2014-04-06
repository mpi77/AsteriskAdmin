<?php
/**
 * Line controller.
 *
 * @version 1.15
 * @author MPI
 * */
class LineController extends Controller{
	private static $validation_table = array(
			"linenumber" => "/^[1-9]{1}[0-9]{3}$/i",
			"name" => "/^[a-zA-Z0-9,\.-]{2,50}$/i",
			"secret" => "/^[a-zA-Z0-9,\.-]{6,50}$/i",
			"nat" => "/^(on|off)$/i",
			"voicemail" => "/^(on|off)$/i",
			"forward" => "/^(on|off)$/i",
			"permit_ip" => "/^(on|off)$/i",
			"confirm_delete" => "/^yes$/i",
			"confirm_pstncancel" => "/^yes$/i",
			"ip" => "/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/i",
			"sm" => "/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/i",
			"external_phone" => "/^(\+[0-9]{3})?([0-9]{9})$/i",
			"pstn_number_id" => "/^[0-9]{1,11}$/i"
	);
	const SIP_REALM = "sip.sd2.cz";
	const SIP_CONTEXT_INCOMING = "incoming";
	const SIP_CONTEXT_INTERNAL = "internal";
	const SIP_CONTEXT_OUTGOING = "outgoing";
	const SIP_DEFAULT_HOST = "dynamic";
	const SIP_DEFAULT_CANREINVITE = "no";
	const SIP_DEFAULT_NAT = "no";
	const SIP_DEFAULT_DENY = "0.0.0.0/0.0.0.0";
	const SIP_DEFAULT_PERMIT = "";
	const SIP_DEFAULT_TYPE = "friend";
	const SIP_DEFAULT_DISALLOW = "all";
	const SIP_DEFAULT_ALLOW = "alaw;ulaw;g722;g729;gsm";
	const SIP_DEFAULT_CANCALLFORWARD = "yes";
	const SIP_DEFAULT_QUALIFY = "yes";
	const CONST_ON = "on";
	const CONST_OFF = "off";
	const CONST_YES = "yes";
	const CONST_NO = "no";

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Edit line form.
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function edit(){
		$args = $this->getArgs();
		// must be loggedin
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_LOGIN_REQUIRED);
		}
		
		// can edit only own line
		$line = $this->getModel()->getData($args["GET"]["id"]);
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $line == Database::EMPTY_RESULT || !is_array($line) || $line[1] != $_SESSION["user"]["uid"]){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(array_key_exists("line_secret_1", $args["POST"]) && array_key_exists("line_secret_1", $args["POST"])){
				// save new secret
				if(!empty($args["POST"]["line_secret_1"]) && !empty($args["POST"]["line_secret_2"]) && $args["POST"]["line_secret_1"] == $args["POST"]["line_secret_2"] && preg_match(self::getRegexp("secret"), $args["POST"]["line_secret_1"]) === 1){
					$secret = "";
					$md5secret = self::getSecretHash($line[2], self::SIP_REALM, $args["POST"]["line_secret_1"]);
					$r = $this->getModel()->saveLineSecret($_SESSION["user"]["uid"], $args["GET"]["id"], $line[2], $secret, $md5secret);
					if($r === true){
						throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
					}else{
						System::setViewDisabled();
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
				}else{
					throw new NoticeException(NoticeException::NOTICE_PASSWORD_INVALID_FORMAT);
				}
			}else if(array_key_exists("line_name", $args["POST"])){
				// save line name
				if(preg_match(self::getRegexp("name"), $args["POST"]["line_name"]) === 1){
					$r = $this->getModel()->saveLineName($_SESSION["user"]["uid"], $args["GET"]["id"], $line[2], $args["POST"]["line_name"]);
					if($r === true){
						throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
					}else{
						System::setViewDisabled();
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
				}else{
					throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
				}
			}else if(array_key_exists("selected_pstn_number", $args["POST"])){
				// save pstn registration
				if(preg_match(self::getRegexp("pstn_number_id"), $args["POST"]["selected_pstn_number"]) === 1){
					$pstn_number = $this->getModel()->getExtensionData($args["POST"]["selected_pstn_number"]);
					if($pstn_number == Database::EMPTY_RESULT || !is_array($pstn_number)){
						throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
					}
					$pstn_number = substr($pstn_number[2], 1);
					$r = $this->getModel()->registerPstnExtension($_SESSION["user"]["uid"], $pstn_number, $line[2]);
					if($r === true){
						throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
					}else{
						System::setViewDisabled();
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
				}else{
					throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
				}
			}else if(array_key_exists("confirm_pstncancel", $args["POST"])){
				// save pstn unregistration
				if(preg_match(self::getRegexp("confirm_pstncancel"), $args["POST"]["confirm_pstncancel"]) === 1){
					$pstn_number = $this->getModel()->getLineRegisteredPstn($line[2]);
					if($pstn_number == Database::EMPTY_RESULT || !is_array($pstn_number)){
						throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
					}
					$pstn_number = substr($pstn_number[2], 1);
					$r = $this->getModel()->unregisterPstnExtension($_SESSION["user"]["uid"], $pstn_number);
					if($r === true){
						throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
					}else{
						System::setViewDisabled();
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
				}else{
					throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
				}
			}else if(array_key_exists("line_voicemail", $args["POST"]) && array_key_exists("line_fwd", $args["POST"]) && array_key_exists("line_nat", $args["POST"]) && array_key_exists("line_permit_ip", $args["POST"])){
				// save params
				if(preg_match(self::getRegexp("voicemail"), $args["POST"]["line_voicemail"]) === 1 && preg_match(self::getRegexp("forward"), $args["POST"]["line_fwd"]) === 1 && preg_match(self::getRegexp("nat"), $args["POST"]["line_nat"]) === 1 && preg_match(self::getRegexp("permit_ip"), $args["POST"]["line_permit_ip"]) === 1){
					if($args["POST"]["line_permit_ip"] == self::CONST_ON && (preg_match(self::getRegexp("ip"), $args["POST"]["line_ip"]) !== 1 || preg_match(self::getRegexp("sm"), $args["POST"]["line_sm"]) !== 1)){
						throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
					}
					if($args["POST"]["line_voicemail"] == self::CONST_ON && (preg_match(VoicemailController::getRegexp("password"), $args["POST"]["line_voicemail_pass"]) !== 1)){
						throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
					}
					$voicemail = ($args["POST"]["line_voicemail"] == self::CONST_ON);
					$vm = $this->getModel()->getVoicemailData($line[2], $line[7]);
					if($voicemail === true && $vm == Database::EMPTY_RESULT){
						// create new voicemail
						$r = $this->getModel()->createVoicemail($line[2], $line[7], $args["POST"]["line_voicemail_pass"], VoicemailController::DEFAULT_EMAIL, VoicemailController::DEFAULT_PAGER, VoicemailController::DEFAULT_TZ, VoicemailController::DEFAULT_ATTACH, VoicemailController::DEFAULT_SAYCID, VoicemailController::DEFAULT_DIALOUT, VoicemailController::DEFAULT_CALLBACK);
						if($r != 0 && $r != 1){
							System::setViewDisabled();
							throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
						}
					}else if($voicemail === true && is_array($vm) && is_numeric($vm[0])){
						// update voicemail
						$r = $this->getModel()->updateVoicemail($vm[0], $args["POST"]["line_voicemail_pass"]);
						if($r != 0 && $r != 1){
							System::setViewDisabled();
							throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
						}
					}else if($voicemail === false && is_array($vm) && is_numeric($vm[0])){
						// delete voicemail
						$r = $this->getModel()->removeVoicemail($vm[0], $line[2], $line[7]);
						if($r !== true){
							System::setViewDisabled();
							throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
						}
					}
					
					$nat = ($args["POST"]["line_nat"] == self::CONST_ON) ? self::CONST_YES : self::CONST_NO;
					$fwd = ($args["POST"]["line_fwd"] == self::CONST_ON) ? self::CONST_YES : self::CONST_NO;
					$deny = "";
					$permit = "";
					if($args["POST"]["line_permit_ip"] == self::CONST_ON){
						$deny = self::SIP_DEFAULT_DENY;
						$permit = sprintf("%s/%s", $args["POST"]["line_ip"], $args["POST"]["line_sm"]);
					}
					$r = $this->getModel()->saveLineParams($_SESSION["user"]["uid"], $args["GET"]["id"], $line[2], $nat, $fwd, $deny, $permit);
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
	}

	/**
	 * Show user line list.
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
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			// set page size
			if(array_key_exists("action_pagesize", $args["POST"]) && is_numeric($args["POST"]["action_pagesize"]) && in_array(intval($args["POST"]["action_pagesize"]), System::$page_size)){
				$_SESSION["page_size"] = intval($args["POST"]["action_pagesize"]);
			}
		}
	}

	/**
	 * Create new line.
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function create(){
		$args = $this->getArgs();
		// create can only logged user
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(array_key_exists("line_number", $args["POST"]) && array_key_exists("line_name", $args["POST"]) && array_key_exists("line_secret_1", $args["POST"]) && array_key_exists("line_secret_2", $args["POST"])){
				// validate input
				if(preg_match(self::getRegexp("linenumber"), $args["POST"]["line_number"]) === 1 && preg_match(self::getRegexp("name"), $args["POST"]["line_name"]) === 1 && preg_match(self::getRegexp("secret"), $args["POST"]["line_secret_1"]) === 1 && preg_match(self::getRegexp("secret"), $args["POST"]["line_secret_2"]) === 1 && $args["POST"]["line_secret_1"] == $args["POST"]["line_secret_2"]){
					// create line
					$secret = "";
					$md5secret = self::getSecretHash($args["POST"]["line_number"], self::SIP_REALM, $args["POST"]["line_secret_1"]);
					$r = $this->getModel()->createLine($_SESSION["user"]["uid"], $args["POST"]["line_number"], $args["POST"]["line_name"], $secret, self::SIP_CONTEXT_INTERNAL, self::SIP_DEFAULT_CANREINVITE, self::SIP_DEFAULT_HOST, $md5secret, self::SIP_DEFAULT_NAT, self::SIP_DEFAULT_DENY, self::SIP_DEFAULT_PERMIT, self::SIP_DEFAULT_TYPE, self::SIP_DEFAULT_DISALLOW, self::SIP_DEFAULT_ALLOW, self::SIP_DEFAULT_CANCALLFORWARD, self::SIP_DEFAULT_QUALIFY, $args["POST"]["line_number"], self::SIP_REALM);
					if($r === true){
						System::redirect(sprintf("%s%s", Config::SITE_PATH, "line/lst/"));
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
	}

	/**
	 * Remove line from dialplan.
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function delete(){
		$args = $this->getArgs();
		// must be loggedin
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_LOGIN_REQUIRED);
		}
		
		// can delete only own line
		$line = $this->getModel()->getData($args["GET"]["id"]);
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $line == Database::EMPTY_RESULT || !is_array($line) || $line[1] != $_SESSION["user"]["uid"]){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(array_key_exists("confirm_delete", $args["POST"]) && preg_match(self::getRegexp("confirm_delete"), $args["POST"]["confirm_delete"]) === 1){
				$r = $this->getModel()->removeLine($_SESSION["user"]["uid"], $args["GET"]["id"]);
				if($r === true){
					System::redirect(sprintf("%s%s", Config::SITE_PATH, "line/lst/"));
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_PASSWORD_INVALID_FORMAT);
			}
		}
	}

	public static function getRegexp($key){
		return self::$validation_table[$key];
	}

	public static function getSecretHash($name, $realm, $secret){
		return hash("md5", sprintf("%s:%s:%s", $name, $realm, $secret));
	}
}
?>