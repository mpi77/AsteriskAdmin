<?php
/**
 * Line model.
 *
 * @version 1.17
 * @author MPI
 * */
class LineModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 *
	 * @param integer $uid        	
	 * @param integer $page        	
	 * @param integer $page_size        	
	 * @param integer $order_column        	
	 * @param string $order_direction        	
	 * @param boolean $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getUserLinesList($uid, $page, $page_size, $order_column, $order_direction, $disable_pagging = false){
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
				0 => "sip.id",
				1 => "sip.name",
				2 => "sip.callerid",
				3 => "IF(LENGTH(extensions.exten) > 0,SUBSTR(extensions.exten, 7),'')"
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		$sql = sprintf("SELECT %s FROM sip LEFT JOIN extensions ON sip.name = SUBSTR(extensions.appdata, 5) WHERE (sip.uid='%d') ORDER BY %s %s %s;", implode(",", $select_columns), $uid, $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of lines in list.
	 *
	 * @param integer $uid        	
	 * @return integer
	 */
	public function getCountUserLinesList($uid){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM sip WHERE (uid='%d')", $uid));
		return intval($r[0]);
	}

	/**
	 * Check if line number is free to use.
	 *
	 * @param integer $line_number        	
	 * @return boolean
	 */
	public function isLineFree($line_number){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(id) FROM sip WHERE (name='%d')", $line_number));
		return ($r[0] == 0) ? true : false;
	}

	/**
	 * Save line name.
	 *
	 * @param integer $uid        	
	 * @param integer $line_id        	
	 * @param integer $line_number        	
	 * @param string $new_name        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function saveLineName($uid, $line_id, $line_number, $new_name){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->updateLineName($line_id, $new_name);
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_TITLE_CHANGED), $line_number));
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
	 * Save line secret.
	 *
	 * @param integer $uid        	
	 * @param integer $line_id        	
	 * @param integer $line_number        	
	 * @param string $new_secret        	
	 * @param string $new_md5secret        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function saveLineSecret($uid, $line_id, $line_number, $new_secret, $new_md5secret){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->updateLineSecret($line_id, $new_secret, $new_md5secret);
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_SECRET_CHANGED), $line_number));
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
	 * Save line parameters.
	 *
	 * @param integer $uid        	
	 * @param integer $line_id        	
	 * @param integer $line_number        	
	 * @param string $nat        	
	 * @param string $canforward        	
	 * @param string $deny        	
	 * @param string $permit        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function saveLineParams($uid, $line_id, $line_number, $nat, $canforward, $deny, $permit){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->updateLineParams($line_id, $nat, $canforward, $deny, $permit);
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_PARAMETERS_CHANGED), $line_number));
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
	 * Insert new line.
	 *
	 * @param integer $uid        	
	 * @param integer $line_number        	
	 * @param string $name        	
	 * @param string $secret        	
	 * @param string $context        	
	 * @param string $canreinvite        	
	 * @param string $host        	
	 * @param string $md5secret        	
	 * @param string $nat        	
	 * @param string $deny        	
	 * @param string $permit        	
	 * @param string $type        	
	 * @param string $disallow        	
	 * @param string $allow        	
	 * @param string $cancallforward        	
	 * @param string $qualify        	
	 * @param string $fromuser        	
	 * @param string $fromdomain        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public function createLine($uid, $line_number, $name, $secret, $context, $canreinvite, $host, $md5secret, $nat, $deny, $permit, $type, $disallow, $allow, $cancallforward, $qualify, $fromuser, $fromdomain){
		try{
			$this->getDb()->beginTransaction();
			if($this->isLineFree($line_number) === false){
				throw new NoticeException(NoticeException::NOTICE_LINE_IS_USED);
			}
			$r = $this->getDb()->actionQuery(sprintf("INSERT INTO sip (id, uid, name, callerid, defaultuser, regexten, secret, context, canreinvite, host, md5secret, nat, deny, permit, type, disallow, allow, regseconds, cancallforward, lastms, qualify, fromuser, fromdomain)
					 VALUES (default,'%d','%d','%s','%d','','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',default,'%s',default,'%s','%s','%s')", $uid, $line_number, $name, $line_number, $secret, $context, $canreinvite, $host, $md5secret, $nat, $deny, $permit, $type, $disallow, $allow, $cancallforward, $qualify, $fromuser, $fromdomain));
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_CREATE), $line_number));
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
	 * Remove line.
	 *
	 * @param integer $uid        	
	 * @param integer $id
	 *        	line id
	 * @throws WarningException
	 * @return boolean
	 */
	public function removeLine($uid, $id){
		try{
			$line = $this->getData($id);
			$line_number = $line[2];
			$context = $line[7];
			$voicemail = $this->getVoicemailData($line_number, $context);
			$is_voicemail = (!empty($voicemail) && $voicemail != Database::EMPTY_RESULT && is_array($voicemail));
			
			$this->getDb()->beginTransaction();
			// delete from voicemail
			if($is_voicemail === true){
				$r = VoicemailModel::removeVoicemailDb($this->getDb(), $voicemail[0]);
				if($r != 1){
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}
			// delete from cdr
			$r = CdrModel::removeCdrDb($this->getDb(), $line_number);
			if($r < 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			
			// delete from extensions and pstn (unregister line from pstn)
			$registered_pstn = $this->getLineRegisteredPstn($line_number);
			if($registered_pstn != Database::EMPTY_RESULT && is_array($registered_pstn)){
				$r = $this->unregisterPstnExtension($uid, substr($registered_pstn[2], 1));
				if($r !== true){
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}
			
			// delete from sip
			$r = $this->getDb()->actionQuery(sprintf("DELETE FROM sip WHERE (id='%d')", $id));
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_DELETE), $line_number));
			$this->getDb()->commitTransaction();
			
			if($is_voicemail === true){
				$r = VoicemailModel::removeVoicemailFiles($line_number, $context);
				if($r !== true){
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
			}
		}catch(WarningException $e){
			$this->getDb()->rollbackTransactioin();
			$this->getDb()->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$this->getDb()->closeTransaction();
		return true;
	}

	/**
	 * Local wrapper for LineModel::getLineData(...).
	 *
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getData($id){
		return self::getLineData($this->getDb(), $id);
	}

	/**
	 * Local wrapper for VoicemailModel::getVoicemailData(...).
	 *
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getVoicemailData($line_number, $context){
		return VoicemailModel::getVoicemailData($this->getDb(), $line_number, $context);
	}

	/**
	 * Local wrapper for VoicemailModel::createVoicemail(...).
	 *
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @param string $password        	
	 * @param string $email        	
	 * @param string $pager        	
	 * @param string $tz        	
	 * @param string $attach        	
	 * @param string $saycid        	
	 * @param string $dialout        	
	 * @param string $callback        	
	 * @return integer
	 */
	public function createVoicemail($line_number, $context, $password, $email, $pager, $tz, $attach, $saycid, $dialout, $callback){
		return VoicemailModel::createVoicemail($this->getDb(), $line_number, $context, $password, $email, $pager, $tz, $attach, $saycid, $dialout, $callback);
	}

	/**
	 * Local wrapper for VoicemailModel::updateVoicemail(...).
	 *
	 * @param integer $id        	
	 * @param string $password        	
	 * @return integer
	 */
	public function updateVoicemail($id, $password){
		return VoicemailModel::updateVoicemail($this->getDb(), $id, $password);
	}

	/**
	 * Local wrapper for VoicemailModel::removeVoicemail(...).
	 *
	 * @param integer $id        	
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @return boolean
	 */
	public function removeVoicemail($id, $line_number, $context){
		return VoicemailModel::removeVoicemail($this->getDb(), $id, $line_number, $context);
	}

	/**
	 * Local wrapper for ExtensionModel::getPstnUnregistredList(...).
	 *
	 * @return 2D array or null
	 */
	public function getPstnUnregistredList(){
		return ExtensionModel::getPstnUnregistredList($this->getDb());
	}

	/**
	 * Local wrapper for ExtensionModel::getLineRegisteredPstn(...).
	 *
	 * @param integer $line_number        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getLineRegisteredPstn($line_number){
		return ExtensionModel::getLineRegisteredPstn($this->getDb(), $line_number);
	}

	/**
	 * Local wrapper for ExtensionModel::registerPstnExtension(...).
	 *
	 * @param string $pstn_number        	
	 * @param integer $line_number        	
	 * @return boolean
	 */
	public function registerPstnExtension($uid, $pstn_number, $line_number){
		$r = ExtensionModel::registerPstnExtension($this->getDb(), $pstn_number, $line_number);
		if($r === true){
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_PSTN_ASSIGNED), $pstn_number, $line_number));
		}
		return $r;
	}

	/**
	 * Local wrapper for ExtensionModel::unregisterPstnExtension(...).
	 *
	 * @param string $pstn_number        	
	 * @return boolean
	 */
	public function unregisterPstnExtension($uid, $pstn_number){
		$r = ExtensionModel::unregisterPstnExtension($this->getDb(), $pstn_number);
		if($r === true){
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_LINE_PSTN_CANCEL), $pstn_number));
		}
		return $r;
	}

	/**
	 * Local wrapper for ExtensionModel::getExtensionData(...).
	 *
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getExtensionData($id){
		return ExtensionModel::getExtensionData($this->getDb(), $id);
	}

	/**
	 * Update line name by id.
	 *
	 * @param integer $id        	
	 * @param string $new_name        	
	 * @return integer
	 */
	private function updateLineName($id, $new_name){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE sip SET callerid='%s' WHERE id='%d'", $new_name, $id));
		return $r;
	}

	/**
	 * Update line secret by id.
	 *
	 * @param integer $id        	
	 * @param string $new_secret        	
	 * @param string $new_md5secret        	
	 * @return integer
	 */
	private function updateLineSecret($id, $new_secret, $new_md5secret){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE sip SET secret='%s', md5secret='%s' WHERE id='%d'", $new_secret, $new_md5secret, $id));
		return $r;
	}

	/**
	 * Update line parameters by id.
	 *
	 * @param integer $id        	
	 * @param string $nat        	
	 * @param string $canforward        	
	 * @param string $deny        	
	 * @param string $permit        	
	 * @return integer
	 */
	private function updateLineParams($id, $nat, $canforward, $deny, $permit){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE sip SET nat='%s', cancallforward='%s', deny='%s', permit='%s' WHERE id='%d'", $nat, $canforward, $deny, $permit, $id));
		return $r;
	}

	/**
	 * Get lines for user by user id.
	 *
	 * @param Database $db        	
	 * @param integer $uid        	
	 * @return 2D array or null
	 */
	public static function getUserLines(Database $db, $uid){
		$r = $db->selectQuery(sprintf("SELECT id, name FROM sip WHERE (uid='%d')", $uid));
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get line data by line id.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public static function getLineData(Database $db, $id){
		$r = $db->selectQuery(sprintf("SELECT id, uid, name, callerid, defaultuser, regexten, secret, context, canreinvite, fromuser, fromdomain, fullcontact, host, insecure, md5secret, nat, deny, permit, port, qualify, type, disallow, allow, regseconds, ipaddr, cancallforward, lastms, useragent FROM sip WHERE (id='%d')", $id));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Check if user is line owner.
	 *
	 * @param Database $db        	
	 * @param integer $line_id        	
	 * @param integer $owner_uid        	
	 * @return boolean
	 */
	public static function isLineOwner(Database $db, $line_id, $owner_uid){
		$line = LineModel::getLineData($db, $line_id);
		return ($line != Database::EMPTY_RESULT && is_array($line) && $line[1] == $owner_uid) ? true : false;
	}

	/**
	 * Check if line has voicemail.
	 *
	 * @param Database $db        	
	 * @param integer $line_id        	
	 * @return boolean
	 */
	public static function hasLineVoicemail(Database $db, $line_id){
		$line = LineModel::getLineData($db, $line_id);
		if($line != Database::EMPTY_RESULT && is_array($line)){
			$vm = VoicemailModel::getVoicemailData($db, $line[2], $line[7]);
			return ($vm != Database::EMPTY_RESULT && is_array($vm)) ? true : false;
		}
		return false;
	}
}
?>