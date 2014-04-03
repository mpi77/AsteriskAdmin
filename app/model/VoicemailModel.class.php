<?php
/**
 * Voicemail model.
 *
 * @version 1.6
 * @author MPI
 * */
class VoicemailModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 *
	 * @param string $context        	
	 * @param integer $line_number        	
	 * @return 2D array or null
	 */
	public function getVoicemailMessages($context, $line_number){
		$f = System::findAllFiles(sprintf("%s%s/%d", VoicemailController::ASTERISK_VOICEMAIL_PATH, $context, $line_number), array(
				".",
				".."
		));
		if(empty($f)){
			return null;
		}
		$msg_names = array();
		foreach($f as $k => $v){
			if(preg_match("/\.txt$/i", $v)){
				$msg_names[] = array(
						0 => substr($v, strrpos($v, "/") + 4, 7),
						1 => $v
				);
			}
		}
		if(empty($msg_names)){
			return null;
		}
		$r = array();
		foreach($msg_names as $k => $v){
			if(file_exists($v[1])){
				$file = file_get_contents($v[1]);
			}
			$pos_callerid = strpos($file, "callerid") + 9;
			$pos_date = strpos($file, "origdate") + 9;
			$pos_duration = strpos($file, "duration") + 9;
			$r[] = array(
					intval($v[0]) + VoicemailController::ASTERISK_STARTING_INDEX,
					substr($file, $pos_date, strpos($file, "\n", $pos_date) - $pos_date),
					substr($file, $pos_callerid, strpos($file, "\n", $pos_callerid) - $pos_callerid),
					substr($file, $pos_duration, strpos($file, "\n", $pos_duration) - $pos_duration)
			);
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Check if voicemail is used.
	 *
	 * @param integer $line_number        	
	 * @return boolean
	 */
	public function isVoicemailUsed($line_number){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(id) FROM voicemail_users WHERE (name='%d')", $line_number));
		return ($r[0] == 0) ? true : false;
	}

	/**
	 * Remove message from voicemail inbox folder.
	 *
	 * @param integer $uid        	
	 * @param integer $line_id        	
	 * @param integer $msg_id        	
	 * @throws NoticeException
	 * @return boolean
	 */
	public function removeMessage($uid, $line_id, $msg_id){
		if($this->isLineOwner($line_id, $uid) && $this->hasLineVoicemail($line_id)){
			$line = $this->getLineData($line_id);
			$file_id = sprintf("%04d", $msg_id - VoicemailController::ASTERISK_STARTING_INDEX);
			$path = sprintf("%s%s", VoicemailController::ASTERISK_VOICEMAIL_PATH, sprintf(VoicemailController::ASTERISK_LINE_INBOX_PATH, $line[7], $line[2]));
			$files_to_delete = array(
					sprintf("%s%s%s%s", $path, VoicemailController::ASTERISK_FILENAME_PREFIX, $file_id, VoicemailController::ASTERISK_WAV_SUFFIX),
					sprintf("%s%s%s%s", $path, VoicemailController::ASTERISK_FILENAME_PREFIX, $file_id, VoicemailController::ASTERISK_TXT_SUFFIX),
					sprintf("%s%s%s%s", $path, VoicemailController::ASTERISK_FILENAME_PREFIX, $file_id, VoicemailController::ASTERISK_GSM_SUFFIX),
					sprintf("%s%s%s%s", $path, VoicemailController::ASTERISK_FILENAME_PREFIX, $file_id, strtoupper(VoicemailController::ASTERISK_WAV_SUFFIX))
			);
			// System::trace($files_to_delete);
			System::removeFiles($files_to_delete);
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_VOICEMAIL_DELETE_MSG), $line[2]));
			return true;
		}else{
			throw new NoticeException(NoticeException::NOTICE_FILE_IS_NOT_DELETABLE);
		}
		return false;
	}

	/**
	 * Local wrapper for VoicemailModel::createVoicemail(...).
	 *
	 * @param Database $db        	
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
	public function create($line_number, $context, $password, $email, $pager, $tz, $attach, $saycid, $dialout, $callback){
		return self::createVoicemail($this->getDb(), $line_number, $context, $password, $email, $pager, $tz, $attach, $saycid, $dialout, $callback);
	}

	/**
	 * Local wrapper for VoicemailModel::updateVoicemail(...).
	 *
	 * @param integer $id        	
	 * @param string $password        	
	 * @return integer
	 */
	public function update($id, $password){
		return self::updateVoicemail($this->getDb(), $id, $password);
	}

	/**
	 * Local wrapper for VoicemailModel::removeVoicemail(...).
	 *
	 * @param integer $id        	
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @return boolean
	 */
	public function remove($id, $line_number, $context){
		return self::removeVoicemail($this->getDb(), $id, $line_number, $context);
	}

	/**
	 * Local wrapper for LineModel::getUserLines(...).
	 *
	 * @param integer $uid        	
	 * @return 2D array or null
	 */
	public function getUserLines($uid){
		return LineModel::getUserLines($this->getDb(), $uid);
	}

	/**
	 * Local wrapper for LineModel::getLineData(...).
	 *
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getLineData($id){
		return LineModel::getLineData($this->getDb(), $id);
	}

	/**
	 * Local wrapper for LineModel::isLineOwner(...).
	 *
	 * @param integer $line_id        	
	 * @param integer $owner_uid        	
	 * @return boolean
	 */
	public function isLineOwner($line_id, $owner_uid){
		return LineModel::isLineOwner($this->getDb(), $line_id, $owner_uid);
	}

	/**
	 * Local wrapper for LineModel::hasLineVoicemail(...).
	 *
	 * @param integer $line_id        	
	 * @return boolean
	 */
	public function hasLineVoicemail($line_id){
		return LineModel::hasLineVoicemail($this->getDb(), $line_id);
	}

	/**
	 * Get voicemail data by line number and context.
	 *
	 * @param Database $db        	
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public static function getVoicemailData(Database $db, $line_number, $context){
		$r = $db->selectQuery(sprintf("SELECT uniqueid, customer_id, context, mailbox, password, fullname, email, tz, attach, saycid, review, operator, envelope, sayduration, saydurationm, sendvoicemail, nextaftercmd, forcename, forcegreetings, hidefromdir, stamp FROM voicemail_users WHERE (mailbox='%d' AND context='%s')", $line_number, $context));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Create new voicemail box.
	 *
	 * @param Database $db        	
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
	public static function createVoicemail(Database $db, $line_number, $context, $password, $email, $pager, $tz, $attach, $saycid, $dialout, $callback){
		$r = $db->actionQuery(sprintf("INSERT INTO voicemail_users (uniqueid, customer_id, context, mailbox, password, fullname, email, pager, tz, attach, saycid, dialout, callback)
				VALUES (default,'%d','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s')", $line_number, $context, $line_number, $password, $line_number, $email, $pager, $tz, $attach, $saycid, $dialout, $callback));
		return $r;
	}

	/**
	 * Update voicemail box by id.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @param string $password        	
	 * @return integer
	 */
	public static function updateVoicemail(Database $db, $id, $password){
		$r = $db->actionQuery(sprintf("UPDATE voicemail_users SET password='%s' WHERE uniqueid='%d'", $password, $id));
		return $r;
	}

	/**
	 * Remove voicemail from db by id.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @return integer
	 */
	public static function removeVoicemailDb(Database $db, $id){
		$r = $db->actionQuery(sprintf("DELETE FROM voicemail_users WHERE (uniqueid='%d')", $id));
		return $r;
	}

	/**
	 * Remove files in voicemail inbox folder.
	 *
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @return boolean
	 */
	public static function removeVoicemailFiles($line_number, $context){
		// delete all files in voicemail inbox
		$path = sprintf("%s%s", VoicemailController::ASTERISK_VOICEMAIL_PATH, sprintf(VoicemailController::ASTERISK_LINE_INBOX_PATH, $context, $line_number));
		$files_to_delete = glob($path . "*");
		// System::trace($files_to_delete);
		System::removeFiles($files_to_delete);
		return true;
	}

	/**
	 * Remove voicemail from db and remove all files in inbox folder.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @param integer $line_number        	
	 * @param string $context        	
	 * @throws WarningException
	 * @return boolean
	 */
	public static function removeVoicemail(Database $db, $id, $line_number, $context){
		try{
			// remove from db
			$db->beginTransaction();
			$r = self::removeVoicemailDb($db, $id);
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$db->commitTransaction();
			
			// delete files on disk
			$r = self::removeVoicemailFiles($line_number, $context);
			if($r !== true){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
		}catch(WarningException $e){
			$db->rollbackTransactioin();
			$db->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$db->closeTransaction();
		return true;
	}
}
?>