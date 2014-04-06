<?php
/**
 * User controller.
 *
 * @version 1.20
 * @author MPI
 * */
class UserController extends Controller{
	private static $validation_table = array(
			"username" => "/^[a-zA-Z0-9,\.-]{5,50}$/i",
			"password" => "/^[a-zA-Z0-9,\.-]{6,50}$/i",
			"first_name" => "/[\w\s]{0,50}/i",
			"last_name" => "/[\w\s]{0,50}/i",
			"email" => "/^[^.]+(\.[^.]+)*@([^.]+[.])+[a-z]{2,4}$/i",
			"phone" => "/^(\+[0-9]{3})?([0-9]{9})$/i",
			"lang" => "/^[0-9]{1,3}$/i"
	);
	const USER_STATUS_ENABLED = 1;
	const USER_STATUS_DISABLED = 0;
	const USER_RENEW_TIME_LIMIT = 24; // minim. 1 h, max. 24 h
	const USER_CREATE_TIME_LIMIT = 24; // minim. 1 h, max. 24 h
	const USER_PERM_UNDEF = 0;
	const USER_PERM_ROOT = 1;
	const USER_PERM_USER = 2;
	const USER_LOG_SIZE = 100;
	private static $account_table = array(
			self::USER_PERM_UNDEF => "unknown",
			self::USER_PERM_ROOT => "root",
			self::USER_PERM_USER => "user"
	);

	public function __construct(Model $model, $args){
		parent::__construct($model, $args);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Show user log list.
	 *
	 * @throws NoticeException
	 */
	public function log(){
		$args = $this->getArgs();
		// must be loggedin
		if(Acl::isLoggedin() === false){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_LOGIN_REQUIRED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			// set page size
			if(array_key_exists("action_pagesize", $args["POST"]) && is_numeric($args["POST"]["action_pagesize"]) && in_array(intval($args["POST"]["action_pagesize"]), System::$page_size)){
				$_SESSION["page_size"] = intval($args["POST"]["action_pagesize"]);
			}
		}
	}

	/**
	 * Show list of existing users.
	 *
	 * @throws NoticeException
	 */
	public function lst(){
		$args = $this->getArgs();
		// show user list can only logged user{root}
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
	 * Login user.
	 *
	 * @throws NoticeException
	 */
	public function login(){
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$args = $this->getArgs();
			if(preg_match(self::getRegexp("email"), $args["POST"]["email"]) === 1 && preg_match(self::getRegexp("password"), $args["POST"]["password"]) === 1){
				$r = $this->getModel()->login($args["POST"]["email"], $args["POST"]["password"]);
				if($r === true){
					System::redirect(Config::SITE_PATH);
				}else{
					session_unset();
					System::initSession();
					throw new NoticeException(NoticeException::NOTICE_LOGIN_FAILED);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_LOGIN_FAILED);
			}
		}
	}

	/**
	 * Logout user.
	 */
	public function logout(){
		if(Acl::isLoggedin()){
			$k = $this->getModel()->insertActivityRecord($_SESSION["user"]["uid"], Translate::get(Translator::LOG_USER_LOGOUT));
		}
		session_unset();
		System::initSession();
		System::redirect(Config::SITE_PATH);
	}

	/**
	 * Edit user form.
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
		
		// can edit only own user account
		if(!isset($args["GET"]["id"]) || !is_numeric($args["GET"]["id"]) || $args["GET"]["id"] != $_SESSION["user"]["uid"]){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(array_key_exists("password_1", $args["POST"]) && array_key_exists("password_2", $args["POST"]) && array_key_exists("password_old", $args["POST"])){
				// save new password
				if(!empty($args["POST"]["password_1"]) && !empty($args["POST"]["password_2"]) && !empty($args["POST"]["password_old"]) && $args["POST"]["password_1"] == $args["POST"]["password_2"] && preg_match(self::getRegexp("password"), $args["POST"]["password_1"]) === 1 && preg_match(self::getRegexp("password"), $args["POST"]["password_old"]) === 1){
					$r = $this->getModel()->saveUserPassword($args["GET"]["id"], $args["POST"]["password_old"], $args["POST"]["password_1"]);
					if($r === true){
						throw new NoticeException(NoticeException::NOTICE_SUCCESSFULLY_SAVED);
					}else{
						System::setViewDisabled();
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
				}else{
					throw new NoticeException(NoticeException::NOTICE_PASSWORD_INVALID_FORMAT);
				}
			}else{
				// save other account data
				if(preg_match(self::getRegexp("first_name"), $args["POST"]["first_name"]) === 1 && preg_match(self::getRegexp("last_name"), $args["POST"]["last_name"]) === 1 && (empty($args["POST"]["phone"]) || preg_match(self::getRegexp("phone"), $args["POST"]["phone"]) === 1) && preg_match(self::getRegexp("lang"), $args["POST"]["select_lang"]) === 1){
					$phone = (preg_match("/^([0-9]{9})$/i", $args["POST"]["phone"]) === 1) ? "+420" . $args["POST"]["phone"] : $args["POST"]["phone"];
					$r = $this->getModel()->saveUserData($args["GET"]["id"], $args["POST"]["first_name"], $args["POST"]["last_name"], $phone, $args["POST"]["select_lang"]);
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
	 * Renew user password (request for token).
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function renew(){
		// renew can only unlogged user
		if(Acl::isLoggedin() === true){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$args = $this->getArgs();
			$email = $args["POST"]["renew_value"];
			
			if(preg_match(self::getRegexp("email"), $email) === 1){
				$r = $this->getModel()->setRenewToken($email);
				if($r === true){
					throw new NoticeException(NoticeException::NOTICE_RENEW_EMAIL_SENDED);
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
	 * Set lost password for user by renew token.
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function setpassword(){
		$args = $this->getArgs();
		// setpassword can only unlogged user
		if(Acl::isLoggedin() === true){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		// token must be valid
		$user = $this->getModel()->getUserDataByRenewToken(self::getRenewTokenHash($args["GET"]["token"]));
		if(!isset($args["GET"]["token"]) || empty($args["GET"]["token"]) || !(is_array($user) && is_numeric($user[0]) && $user[0] > 0)){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_INVALID_TOKEN);
		}
		$uid = $user[0];
		$email = $user[1];
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(!empty($args["POST"]["password_1"]) && !empty($args["POST"]["password_2"]) && $args["POST"]["password_1"] == $args["POST"]["password_2"] && $args["POST"]["password_1"] != $email && preg_match(self::getRegexp("password"), $args["POST"]["password_1"]) === 1){
				$r = $this->getModel()->setPassword($uid, self::getPasswordHash($email, $args["POST"]["password_1"]));
				if($r === true){
					System::setViewDisabled();
					throw new NoticeException(NoticeException::NOTICE_PASSWORD_CHANGED);
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				throw new NoticeException(NoticeException::NOTICE_PASSWORD_INVALID_FORMAT);
			}
		}
	}

	/**
	 * Create request for adding new user.
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function addrequest(){
		$args = $this->getArgs();
		// add request can only logged user{root}
		if(!(Acl::isLoggedin() === true && Acl::isRoot())){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$email = $args["POST"]["email"];
			$account_type = intval($args["POST"]["type"]);
			
			if($account_type != UserController::USER_PERM_USER){
				System::setViewDisabled();
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			
			if(System::isCsrfAttack($args["POST"]["auth_token"]) === true){
				throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
			}
			System::updateAuthToken();
			if(preg_match(self::getRegexp("email"), $email) === 1 && self::isValidAccountType(intval($account_type))){
				$r = $this->getModel()->createUcr($_SESSION["user"]["uid"], $email, $account_type);
				if($r === true){
					throw new NoticeException(NoticeException::NOTICE_USER_CREATE_EMAIL_SENDED);
				}else{
					System::setViewDisabled();
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}else{
				// not valid data
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
		}
	}

	/**
	 * Create new user with ucr token.
	 *
	 * @throws NoticeException
	 * @throws WarningException
	 */
	public function create(){
		$args = $this->getArgs();
		// create can only unlogged user
		if(Acl::isLoggedin() === true){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_PERMISSION_DENIED);
		}
		// token must exists
		$ucr = $this->getModel()->getUcrDataByToken(UserController::getUcrRequestTokenHash($args["GET"]["token"]));
		if(!isset($args["GET"]["token"]) || empty($args["GET"]["token"]) || !(is_array($ucr) && is_numeric($ucr[0]) && $ucr[0] > 0)){
			System::setViewDisabled();
			throw new NoticeException(NoticeException::NOTICE_INVALID_TOKEN);
		}
		
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if(!empty($args["POST"]["password_1"]) && !empty($args["POST"]["password_2"]) && $args["POST"]["password_1"] == $args["POST"]["password_2"] && preg_match(self::getRegexp("password"), $args["POST"]["password_1"]) === 1){
				$r = $this->getModel()->createUser($args["GET"]["token"], $args["POST"]["password_1"]);
				if($r === true){
					System::setViewDisabled();
					throw new NoticeException(NoticeException::NOTICE_USER_ACTIVATED);
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

	public static function getPasswordHash($email, $password){
		return hash("sha256", sprintf("%s:%s", $email, $password));
	}

	public static function getRenewTokenHash($code){
		return hash("sha256", $code);
	}

	public static function getUcrRequestTokenHash($code){
		return hash("sha256", $code);
	}

	public static function getNameUserType($type){
		$r = self::$account_table[$type];
		return (empty($r) ? self::$account_table[self::USER_PERM_UNDEF] : $r);
	}

	public static function getUserTypeTable($min = null){
		return (empty($min) ? self::$account_table : array_slice(self::$account_table, $min, null, true));
	}

	public static function isValidAccountType($type){
		return ($type === self::USER_PERM_ROOT || $type === self::USER_PERM_USER);
	}
}
?>