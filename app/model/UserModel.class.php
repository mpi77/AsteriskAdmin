<?php
/**
 * User model.
 *
 * @version 1.18
 * @author MPI
 * */
class UserModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Get log list data.
	 *
	 * @param integer $uid        	
	 * @param integer $page        	
	 * @param integer $page_size        	
	 * @param integer $order_column        	
	 * @param string $order_direction        	
	 * @param boolean $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getLogList($uid, $page, $page_size, $order_column, $order_direction, $disable_pagging = false){
		if(empty($uid) || empty($page) || empty($page_size)){
			return null;
		}
		$offset = Paginator::getStartRow($page, $page_size);
		$limit = $page_size;
		if($disable_pagging === true){
			$offset = null;
			$limit = null;
		}
		$select_columns = array(
				0 => "ts_insert",
				1 => "description"
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		
		$sql = sprintf("SELECT %s FROM system_log_activity WHERE (uid='%d') ORDER BY %s %s %s;", implode(",", $select_columns), $uid, $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of log list items for user.
	 *
	 * @param integer $uid        	
	 * @return integer
	 */
	public function getCountLogList($uid){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM system_log_activity WHERE (uid='%d')", $uid));
		return intval($r[0]);
	}

	/**
	 * Get user list data.
	 *
	 * @param integer $page        	
	 * @param integer $page_size        	
	 * @param integer $order_column        	
	 * @param string $order_direction        	
	 * @param boolean $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getUserList($page, $page_size, $order_column, $order_direction, $disable_pagging = false){
		if(empty($page) || empty($page_size)){
			return null;
		}
		$offset = Paginator::getStartRow($page, $page_size);
		$limit = $page_size;
		if($disable_pagging === true){
			$offset = null;
			$limit = null;
		}
		$select_columns = array(
				0 => "uid",
				1 => "email",
				2 => "first_name",
				3 => "last_name",
				4 => "phone"
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		
		$sql = sprintf("SELECT %s FROM user WHERE (type='%d') ORDER BY %s %s %s;", implode(",", $select_columns), UserController::USER_PERM_USER, $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of users in list.
	 *
	 * @return integer
	 */
	public function getCountUserList(){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM user WHERE (type='%d')", UserController::USER_PERM_USER));
		return intval($r[0]);
	}

	/**
	 * Login user.
	 *
	 * @param string $email        	
	 * @param string $password        	
	 * @return boolean
	 */
	public function login($email, $password){
		$password_hash = UserController::getPasswordHash($email, $password);
		$r = $this->getDb()->selectQuery(sprintf("SELECT uid, email, first_name, last_name, last_login, type, language FROM user WHERE (email='%s' AND password='%s' AND status='%d')", $email, $password_hash, UserController::USER_STATUS_ENABLED));
		if($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false && is_numeric($r[0]) && $r[0] > 0){
			$_SESSION["user"]["uid"] = intval($r[0]);
			$_SESSION["user"]["email"] = $r[1];
			$_SESSION["user"]["first_name"] = $r[2];
			$_SESSION["user"]["last_name"] = $r[3];
			$_SESSION["user"]["last_login"] = $r[4];
			$_SESSION["user"]["type"] = intval($r[5]);
			$_SESSION["user"]["lang"] = intval($r[6]);
			$_SESSION["user"]["auth"] = true;
			// update login time
			$u = $this->updateLoginTime($r[0]);
			// insert log_activity record
			$k = $this->insertActivityRecord($r[0], Translate::get(Translator::LOG_USER_LOGIN));
			return true;
		}
		return false;
	}

	/**
	 * Save user data.
	 *
	 * @param integer $uid        	
	 * @param string $first_name        	
	 * @param string $last_name        	
	 * @param string $phone        	
	 * @param integer $lang        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function saveUserData($uid, $first_name, $last_name, $phone, $lang){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->updateUserData($uid, $first_name, $last_name, $phone, $lang);
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$t = $this->insertActivityRecord($uid, Translate::get(Translator::LOG_USER_ACCOUNT_DATA_CHANGED));
			$this->getDb()->commitTransaction();
			$_SESSION["user"]["first_name"] = $first_name;
			$_SESSION["user"]["last_name"] = $last_name;
			$_SESSION["user"]["lang"] = $lang;
			Translate::changeLang($lang);
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Save user password.
	 *
	 * @param integer $uid        	
	 * @param string $old_password        	
	 * @param string $new_password        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public function saveUserPassword($uid, $old_password, $new_password){
		try{
			$this->getDb()->beginTransaction();
			$user = $this->getUserData($uid);
			$email = $user[1];
			$old_pass = $this->getUserPassword($uid);
			$old_pass_db = $old_pass[1];
			$old_pass_post = UserController::getPasswordHash($email, $old_password);
			if($user == Database::EMPTY_RESULT || $old_pass_db != $old_pass_post || $new_password == $email){
				throw new NoticeException(NoticeException::NOTICE_PASSWORD_INVALID_FORMAT);
			}
			$r = $this->updatePassword($uid, UserController::getPasswordHash($email, $new_password));
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$t = $this->insertActivityRecord($uid, Translate::get(Translator::LOG_USER_PASSWORD_CHANGED));
			$this->getDb()->commitTransaction();
		}catch(NoticeException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new NoticeException($e->getCode());
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Create request for adding new user.
	 *
	 * @param integer $uid        	
	 * @param string $email        	
	 * @param integer $account_type        	
	 * @throws WarningException
	 * @throws NoticeException
	 * @return boolean
	 */
	public function createUcr($uid, $email, $account_type){
		try{
			$this->getDb()->beginTransaction();
			// check if user exists
			$user = $this->getUserDataByEmail($email);
			// check if request exists in ucr (search for time valid req)
			$req = $this->getUcrDataByEmail($email);
			if($user == Database::EMPTY_RESULT && $req == Database::EMPTY_RESULT){
				// user doesnt exist, first remove all 'old' ucr then generate token
				$rem_req = $this->removeOldUcr($email);
				$code = System::generateRandomCode(32);
				$token = UserController::getUcrRequestTokenHash($code);
				while($this->getUcrDataByToken($token) != Database::EMPTY_RESULT){
					$code = System::generateRandomCode(32);
					$token = UserController::getUcrRequestTokenHash($code);
				}
				$args = array(
						"msg_index" => Email::MSG_UCR_TOKEN,
						"to_email" => $email
				);
				$msg_data = array(
						"token" => $code
				);
				$e = Email::send($args, $msg_data);
				if($e === true){
					// save ucr request
					$r = $this->insertUcrRequest($email, $token, $account_type);
					if($r != 1){
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
					$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_USER_REQUEST), $email));
				}else{
					// error while sending email
					throw new NoticeException(NoticeException::NOTICE_USER_CREATE_EMAIL_ERROR);
				}
			}else{
				// email is used
				throw new NoticeException(NoticeException::NOTICE_EMAIL_USED_ENTER_ANOTHER);
			}
			$this->getDb()->commitTransaction();
		}catch(NoticeException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new NoticeException($e->getCode());
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Create new user with ucr token.
	 *
	 * @param string $ucr_token        	
	 * @param string $new_password        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public function createUser($ucr_token, $new_password){
		try{
			$this->getDb()->beginTransaction();
			// find ucr by token
			$ucr = $this->getUcrDataByToken(UserController::getUcrRequestTokenHash($ucr_token));
			if($ucr == Database::EMPTY_RESULT || !(is_array($ucr) && is_numeric($ucr[0]) && $ucr[0] > 0)){
				throw new NoticeException(NoticeException::NOTICE_INVALID_TOKEN);
			}
			$ucr_id = $ucr[0];
			$email = $ucr[1];
			$type = $ucr[2];
			$password_hash = UserController::getPasswordHash($email, $new_password);
			// check if user exists
			if($this->getUserDataByEmail($email) != Database::EMPTY_RESULT){
				throw new NoticeException(NoticeException::NOTICE_EMAIL_USED_ENTER_ANOTHER);
			}
			// check if password and email are not same
			if($new_password == $email){
				throw new NoticeException(NoticeException::NOTICE_INPUT_INVALID_FORMAT);
			}
			// save to db
			$r = $this->insertUser($email, $password_hash, $type);
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$uid = $this->getDb()->lastGeneratedId();
			// remove ucr record
			$k = $this->removeUcrRequest($ucr_id);
			// log
			$t = $this->insertActivityRecord($uid, Translate::get(Translator::LOG_USER_ACTIVATION));
			$this->getDb()->commitTransaction();
		}catch(NoticeException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new NoticeException($e->getCode());
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Set renew token to user by email.
	 *
	 * @param string $email        	
	 * @throws WarningException
	 * @throws NoticeException
	 * @return boolean
	 */
	public function setRenewToken($email){
		try{
			$this->getDb()->beginTransaction();
			$user = $this->getUserDataByEmail($email);
			if($user != Database::EMPTY_RESULT && is_array($user) && is_numeric($user[0]) && $user[0] > 0){
				// user found, generate token
				$code = System::generateRandomCode(32);
				$token = UserController::getRenewTokenHash($code);
				while($this->getUserDataByRenewToken($token) != Database::EMPTY_RESULT){
					$code = System::generateRandomCode(32);
					$token = self::getRenewTokenHash($code);
				}
				// send email with token
				$uid = $user[0];
				$args = array(
						"msg_index" => Email::MSG_RENEW_TOKEN,
						"to_email" => $email
				);
				$msg_data = array(
						"token" => $code
				);
				$e = Email::send($args, $msg_data);
				if($e === true){
					// save token and block account
					$r = $this->updateRenewToken($uid, $token);
					if($r != 1){
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
				}else{
					// error while sending email
					throw new NoticeException(NoticeException::NOTICE_RENEW_EMAIL_ERROR);
				}
			}else{
				// user not found
				throw new NoticeException(NoticeException::NOTICE_USER_NOT_FOUND);
			}
			$this->getDb()->commitTransaction();
		}catch(NoticeException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new NoticeException($e->getCode());
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Set lost password for user.
	 *
	 * @param integer $uid        	
	 * @param string $password_hash        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function setPassword($uid, $password_hash){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->updatePassword($uid, $password_hash);
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$r = $this->unblockUser($uid);
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$t = $this->insertActivityRecord($uid, Translate::get(Translator::LOG_USER_PASSWORD_CHANGED));
			$this->getDb()->commitTransaction();
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Get ucr data by email.
	 *
	 * @param string $email        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getUcrDataByEmail($email){
		$r = $this->getDb()->selectQuery(sprintf("SELECT id, email, user_type, valid_to FROM ucr WHERE (email='%s' AND valid_to>=NOW())", $email));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Get ucr data by token.
	 *
	 * @param string $token_hash        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getUcrDataByToken($token_hash){
		$r = $this->getDb()->selectQuery(sprintf("SELECT id, email, user_type, valid_to FROM ucr WHERE (token='%s' AND valid_to>=NOW())", $token_hash));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Get user data by uid.
	 *
	 * @param integer $uid        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getUserData($uid){
		$r = $this->getDb()->selectQuery(sprintf("SELECT uid, email, type, first_name, last_name, phone, last_login, language FROM user WHERE (uid='%d')", $uid));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Get user data by email.
	 *
	 * @param string $email        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getUserDataByEmail($email){
		$r = $this->getDb()->selectQuery(sprintf("SELECT uid, email FROM user WHERE (email='%s')", $email));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Get user data by renew token.
	 *
	 * @param string $token        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getUserDataByRenewToken($token){
		$r = $this->getDb()->selectQuery(sprintf("SELECT uid, email, renew_valid_to FROM user WHERE (renew_token='%s' AND renew_valid_to>=NOW())", $token));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Get user password by uid.
	 *
	 * @param integer $uid        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getUserPassword($uid){
		$r = $this->getDb()->selectQuery(sprintf("SELECT uid, password FROM user WHERE (uid='%d')", $uid));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Insert ucr request.
	 *
	 * @param string $email        	
	 * @param string $token        	
	 * @param integer $type        	
	 * @return integer
	 */
	private function insertUcrRequest($email, $token, $type){
		$r = $this->getDb()->actionQuery(sprintf("INSERT INTO ucr (id, email, token, valid_to, user_type) VALUES (default, '%s', '%s', DATE_ADD(NOW(), INTERVAL '0 %d:0:0' DAY_SECOND) , '%d')", $email, $token, UserController::USER_CREATE_TIME_LIMIT, $type));
		return $r;
	}

	/**
	 * Remove ucr request by id.
	 *
	 * @param integer $id        	
	 * @return integer
	 */
	private function removeUcrRequest($id){
		$r = $this->getDb()->actionQuery(sprintf("DELETE FROM ucr WHERE (id='%d')", $id));
		return $r;
	}

	/**
	 * Remove all expired ucr requests.
	 *
	 * @param string $email        	
	 * @throws WarningException
	 * @return boolean
	 */
	private function removeOldUcr($email){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(id) FROM ucr WHERE (email='%s')", $email));
			if($r > 0){
				$r = $this->getDb()->actionQuery(sprintf("DELETE FROM ucr WHERE (email='%s')", $email));
				if($r < 0){
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}
			$this->getDb()->commitTransaction();
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Insert user.
	 *
	 * @param string $email        	
	 * @param string $password_hash        	
	 * @param integer $type        	
	 * @return integer
	 */
	private function insertUser($email, $password_hash, $type){
		$r = $this->getDb()->actionQuery(sprintf("INSERT INTO user (uid, email, password, type, status) VALUES (default,'%s','%s','%d','%d')", $email, $password_hash, $type, UserController::USER_STATUS_ENABLED));
		return $r;
	}

	/**
	 * Update user data.
	 *
	 * @param integer $uid        	
	 * @param string $first_name        	
	 * @param string $last_name        	
	 * @param string $phone        	
	 * @param integer $lang        	
	 * @return integer
	 */
	private function updateUserData($uid, $first_name, $last_name, $phone, $lang){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE user SET first_name='%s', last_name='%s', phone='%s', language='%d' WHERE uid='%d'", $first_name, $last_name, $phone, $lang, $uid));
		return $r;
	}

	/**
	 * Update user password.
	 *
	 * @param integer $uid        	
	 * @param string $new_password_hash        	
	 * @return integer
	 */
	private function updatePassword($uid, $new_password_hash){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE user SET password='%s' WHERE uid='%d'", $new_password_hash, $uid));
		return $r;
	}

	/**
	 * Update user login time.
	 *
	 * @param integer $uid        	
	 * @return integer
	 */
	private function updateLoginTime($uid){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE user SET last_login=NOW() WHERE uid='%d'", $uid));
		return $r;
	}

	/**
	 * Update user renew token.
	 *
	 * @param integer $uid        	
	 * @param string $renew_token        	
	 * @return integer
	 */
	private function updateRenewToken($uid, $renew_token){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE user SET status='%d', renew_token='%s', renew_valid_to=DATE_ADD(NOW(), INTERVAL '0 %d:0:0' DAY_SECOND) WHERE (uid='%d')", UserController::USER_STATUS_DISABLED, $renew_token, UserController::USER_RENEW_TIME_LIMIT, $uid));
		return $r;
	}

	/**
	 * Unblock user account.
	 *
	 * @param integer $uid        	
	 * @return integer
	 */
	private function unblockUser($uid){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE user SET status='%d', renew_token=NULL, renew_valid_to=NULL WHERE (uid='%d')", UserController::USER_STATUS_ENABLED, $uid));
		return $r;
	}
}
?>