<?php
/**
 * Extension model.
 *
 * @version 1.18
 * @author MPI
 * */
class ExtensionModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Get extension list data.
	 *
	 * @param integer $page        	
	 * @param integer $page_size        	
	 * @param integer $order_column        	
	 * @param string $order_direction        	
	 * @param boolean $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getExtensionList($page, $page_size, $order_column, $order_direction, $disable_pagging = false){
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
				0 => "id",
				1 => "context",
				2 => "exten",
				3 => "priority",
				4 => "app",
				5 => "appdata"
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		$sql = sprintf("SELECT %s FROM extensions ORDER BY %s %s %s;", implode(",", $select_columns), $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of extensions in list.
	 *
	 * @return integer
	 */
	public function getCountExtensionList(){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM extensions"));
		return intval($r[0]);
	}

	/**
	 * Get pstn extension data.
	 *
	 * @param unknown $page        	
	 * @param unknown $page_size        	
	 * @param unknown $order_column        	
	 * @param unknown $order_direction        	
	 * @param string $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getPstnNumList($page, $page_size, $order_column, $order_direction, $disable_pagging = false){
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
				0 => "id",
				1 => "SUBSTR(exten,7)",
				2 => "IF(app='Dial',SUBSTR(appdata,LOCATE('/',appdata)+1),'')"
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		$sql = sprintf("SELECT %s FROM extensions WHERE (context='%s') ORDER BY %s %s %s;", implode(",", $select_columns), ExtensionController::EXT_CONTEXT_INCOMING, $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		// System::trace($sql);
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of pstn extensions in list.
	 *
	 * @return integer
	 */
	public function getCountPstnNumList(){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM extensions WHERE (context='%s')", ExtensionController::EXT_CONTEXT_INCOMING));
		return intval($r[0]);
	}

	/**
	 * Local wrapper for ExtensionModel::getExtensionData(...).
	 *
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getData($id){
		return self::getExtensionData($this->getDb(), $id);
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
	 * Local wrapper for ExtensionModel::removeExtension(...).
	 *
	 * @param integer $uid        	
	 * @param integer $id        	
	 * @return boolean
	 */
	public function remove($uid, $id){
		$r = self::removeExtension($this->getDb(), $id);
		if($r === true){
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_EXTENSION_ITEM_DELETED), $id));
		}
		return $r;
	}

	/**
	 * Local wrapper for ExtensionModel::unregisterPstnExtension(...).
	 *
	 * @param integer $uid        	
	 * @param string $pstn_number        	
	 * @return boolean
	 */
	public function unregisterPstn($uid, $pstn_number){
		$r = self::unregisterPstnExtension($this->getDb(), $pstn_number);
		if($r === true){
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_EXTENSION_PSTN_CANCEL), $pstn_number));
		}
		return $r;
	}

	/**
	 * Create new PSTN.
	 *
	 * @param integer $uid        	
	 * @param string $pstn_number        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public function createPstn($uid, $pstn_number){
		try{
			$this->getDb()->beginTransaction();
			// check if pstn exists
			if(self::pstnNumberExists($this->getDb(), $pstn_number) !== false){
				throw new NoticeException(NoticeException::NOTICE_PSTN_LINE_IS_USED);
			}
			// create extension in incoming context
			$r = $this->insertExtension(ExtensionController::EXT_CONTEXT_INCOMING, sprintf("_%s", $pstn_number), ExtensionController::EXT_DEFAULT_PRIORITY, ExtensionController::EXT_APP_HANGUP, "");
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// create extension condition in outgoing context
			// get max priority of outgoing context
			$max_priority = $this->getDb()->selectQuery(sprintf("SELECT MAX(priority) FROM extensions WHERE (context='%s' AND exten='%s')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION));
			if($max_priority == Database::EMPTY_RESULT || !is_array($max_priority) || !is_numeric($max_priority[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$max_priority = $max_priority[0];
			// insert IF CONDITION
			$r = $this->insertExtension(ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $max_priority + 1, ExtensionController::EXT_APP_GOTOIF, sprintf("$[\"\${CALLERID(num)}\" == \"%d\"]?%d:%d", ExtensionController::EXT_OUTGOING_DEFAULT_CONDITION_LINE, $max_priority + 2, $max_priority + 4));
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// insert SET CID
			$r = $this->insertExtension(ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $max_priority + 2, ExtensionController::EXT_APP_SET, sprintf("CALLERID(num)=%s", $pstn_number));
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// insert GOTO DIAL
			$r = $this->insertExtension(ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $max_priority + 3, ExtensionController::EXT_APP_GOTO, ExtensionController::EXT_OUTGOING_DIAL_PRIORITY);
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_EXTENSION_PSTN_CREATE), $pstn_number));
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
	 * Remove PSTN.
	 *
	 * @param integer $uid        	
	 * @param string $pstn_number        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public function removePstn($uid, $pstn_number){
		try{
			$this->getDb()->beginTransaction();
			// check if pstn exists
			if(self::pstnNumberExists($this->getDb(), $pstn_number) !== true){
				throw new NoticeException(NoticeException::NOTICE_PSTN_NOT_FOUND);
			}
			// remove extension from incoming context
			$r = $this->getDb()->actionQuery(sprintf("DELETE FROM extensions WHERE (context='%s' AND exten='_%s')", ExtensionController::EXT_CONTEXT_INCOMING, $pstn_number));
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// remove extension condition from outgoing context
			// get max priority of outgoing context
			$max_priority = $this->getDb()->selectQuery(sprintf("SELECT MAX(priority) FROM extensions WHERE (context='%s' AND exten='%s')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION));
			if($max_priority == Database::EMPTY_RESULT || !is_array($max_priority) || !is_numeric($max_priority[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$max_priority = $max_priority[0];
			$cnt_conditions = intval(floor(($max_priority - 4) / 3));
			
			// find Set CID for pstn number
			$set = $this->getDb()->selectQuery(sprintf("SELECT id, priority FROM extensions WHERE (context='%s' AND exten='%s' AND app='%s' AND appdata LIKE '%%%s')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, ExtensionController::EXT_APP_SET, $pstn_number));
			if($set == Database::EMPTY_RESULT || !is_array($set) || !is_numeric($set[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// find GotoIf and Goto for pstn number
			$goto = $this->getDb()->selectQuery(sprintf("SELECT id, priority FROM extensions WHERE (context='%s' AND exten='%s' AND (priority='%d' OR priority='%d'))", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $set[1] - 1, $set[1] + 1));
			if($goto == Database::EMPTY_RESULT || !is_array($goto) || !is_array($goto[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$gotoif_id = $goto[0][0];
			$gotoif_priority = $goto[0][1];
			$goto_id = $goto[1][0];
			$goto_priority = $goto[1][1];
			// remove extensions from outgoing context (GotoIf, Set, Goto)
			$r = $this->getDb()->actionQuery(sprintf("DELETE FROM extensions WHERE (id='%d' || id='%d' || id='%d')", $set[0], $gotoif_id, $goto_id));
			if($r != 3){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			
			if($cnt_conditions > 1){
				// update other extensions priority
				$r = $this->getDb()->actionQuery(sprintf("UPDATE extensions SET priority=priority-3 WHERE (context='%s' AND exten='%s' AND priority>'%d')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $gotoif_priority));
				if($r < 0 || $r % 3 != 0){
					throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
				}
				if($r > 0){
					// update priorities in all GotoIf
					$allif = $this->getDb()->selectQuery(sprintf("SELECT id, appdata FROM extensions WHERE (context='%s' AND exten='%s' AND priority>='%d' AND app='%s')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $gotoif_priority, ExtensionController::EXT_APP_GOTOIF));
					if($allif != Database::EMPTY_RESULT && !is_array($allif)){
						throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
					}
					$allif = (is_array($allif[0])) ? $allif : array(
							$allif
					);
					for($i = 0; $i < count($allif); $i++){
						$pos_set = strrpos($allif[$i][1], "?") + 1;
						$pos_nextif = strrpos($allif[$i][1], ":") + 1;
						$val_set = substr($allif[$i][1], $pos_set, strlen($allif[$i][1]) - $pos_nextif);
						$val_nextif = substr($allif[$i][1], $pos_nextif, strlen($allif[$i][1]));
						$appdata = sprintf("%s%d:%d", substr($allif[$i][1], 0, $pos_set), $val_set - 3, $val_nextif - 3);
						
						$r = $this->getDb()->actionQuery(sprintf("UPDATE extensions SET appdata='%s' WHERE (id='%d')", $appdata, $allif[$i][0]));
						if($r != 1){
							throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
						}
					}
				}
			}
			
			// System::trace($this->getDb()->selectQuery(sprintf("SELECT * FROM extensions WHERE (context='outgoing' AND exten='%s')", ExtensionController::EXT_OUTGOING_EXTENSION)));
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_EXTENSION_PSTN_DELETE), $pstn_number));
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
	 * Create new extension.
	 *
	 * @param integer $uid        	
	 * @param string $context        	
	 * @param string $line        	
	 * @param integer $priority        	
	 * @param string $app        	
	 * @param string $appdata        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function createExtension($uid, $context, $line, $priority, $app, $appdata){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->insertExtension($context, $line, $priority, $app, $appdata);
			if($r != 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_EXTENSION_ITEM_CREATE), $this->getDb()->lastGeneratedId()));
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
	 * Save extension.
	 *
	 * @param integer $uid        	
	 * @param integer $id        	
	 * @param string $context        	
	 * @param string $line        	
	 * @param integer $priority        	
	 * @param string $app        	
	 * @param string $appdata        	
	 * @throws WarningException
	 * @return boolean
	 */
	public function saveExtension($uid, $id, $context, $line, $priority, $app, $appdata){
		try{
			$this->getDb()->beginTransaction();
			$r = $this->updateExtension($id, $context, $line, $priority, $app, $appdata);
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// log
			$t = $this->insertActivityRecord($uid, sprintf(Translate::get(Translator::LOG_EXTENSION_ITEM_CHANGED), $id));
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
	 * Insert new extension.
	 *
	 * @param string $context        	
	 * @param string $line        	
	 * @param integer $priority        	
	 * @param string $app        	
	 * @param string $appdata        	
	 * @return integer
	 */
	private function insertExtension($context, $line, $priority, $app, $appdata){
		$r = $this->getDb()->actionQuery(sprintf("INSERT INTO extensions (id,context,exten,priority,app,appdata) VALUES (default,'%s','%s','%d','%s','%s')", $context, $line, $priority, $app, $appdata));
		return $r;
	}

	/**
	 * Update extension by id.
	 *
	 * @param integer $id        	
	 * @param string $context        	
	 * @param string $line        	
	 * @param integer $priority        	
	 * @param string $app        	
	 * @param string $appdata        	
	 * @return integer
	 */
	private function updateExtension($id, $context, $line, $priority, $app, $appdata){
		$r = $this->getDb()->actionQuery(sprintf("UPDATE extensions SET context='%s', exten='%s', priority='%s', app='%s', appdata='%s' WHERE id='%d'", $context, $line, $priority, $app, $appdata, $id));
		return $r;
	}

	/**
	 * Unregister PSTN extension.
	 *
	 * @param Database $db        	
	 * @param string $pstn_number        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public static function unregisterPstnExtension(Database $db, $pstn_number){
		try{
			$db->beginTransaction();
			// check if pstn exists
			if(self::pstnNumberExists($db, $pstn_number) !== true){
				throw new NoticeException(NoticeException::NOTICE_PSTN_NOT_FOUND);
			}
			// can unregister only registered extension
			if(self::isPstnNumberRegistered($db, $pstn_number) === false){
				throw new NoticeException(NoticeException::NOTICE_PSTN_LINE_IS_FREE);
			}
			// update extension in incoming context
			$r = $db->actionQuery(sprintf("UPDATE extensions SET app='%s', appdata='%s' WHERE (context='%s' AND exten='_%s')", ExtensionController::EXT_APP_HANGUP, "", ExtensionController::EXT_CONTEXT_INCOMING, $pstn_number));
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			
			// update extension in outgoing context
			// find Set CID for pstn number
			$set = $db->selectQuery(sprintf("SELECT id, priority FROM extensions WHERE (context='%s' AND exten='%s' AND app='%s' AND appdata LIKE '%%%s')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, ExtensionController::EXT_APP_SET, $pstn_number));
			if($set == Database::EMPTY_RESULT || !is_array($set) || !is_numeric($set[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// find GotoIf for pstn number
			$goto = $db->selectQuery(sprintf("SELECT id, appdata FROM extensions WHERE (context='%s' AND exten='%s' AND priority='%d')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $set[1] - 1));
			if($goto == Database::EMPTY_RESULT || !is_array($goto) || !is_numeric($goto[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$gotoif_id = $goto[0];
			$gotoif_appdata = $goto[1];
			
			$pos_rquot = strrpos($gotoif_appdata, "\"");
			$pos_lquot = strrpos(substr($gotoif_appdata, 0, $pos_rquot), "\"");
			$val_bef = substr($gotoif_appdata, 0, $pos_lquot + 1);
			$val_aft = substr($gotoif_appdata, $pos_rquot, strlen($gotoif_appdata));
			$appdata = sprintf("%s%d%s", $val_bef, ExtensionController::EXT_OUTGOING_DEFAULT_CONDITION_LINE, $val_aft);
			
			$r = $db->actionQuery(sprintf("UPDATE extensions SET appdata='%s' WHERE (id='%d')", $appdata, $gotoif_id));
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$db->commitTransaction();
		}catch(NoticeException $e){
			$db->rollbackTransactioin();
			$db->closeTransaction();
			throw new NoticeException($e->getCode());
		}catch(WarningException $e){
			$db->rollbackTransactioin();
			$db->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$db->closeTransaction();
		return true;
	}

	/**
	 * Register PSTN extension.
	 *
	 * @param Database $db        	
	 * @param string $pstn_number        	
	 * @param integer $line_number        	
	 * @throws NoticeException
	 * @throws WarningException
	 * @return boolean
	 */
	public static function registerPstnExtension(Database $db, $pstn_number, $line_number){
		try{
			$db->beginTransaction();
			// check if pstn exists
			if(self::pstnNumberExists($db, $pstn_number) !== true){
				throw new NoticeException(NoticeException::NOTICE_PSTN_NOT_FOUND);
			}
			// can register only unregistered extension
			if(self::isPstnNumberRegistered($db, $pstn_number) === true){
				throw new NoticeException(NoticeException::NOTICE_PSTN_LINE_IS_ASSIGNED);
			}
			// can register only unregistered line
			$line_pstn = self::getLineRegisteredPstn($db, $line_number);
			if($line_pstn != Database::EMPTY_RESULT){
				throw new NoticeException(NoticeException::NOTICE_PSTN_ONE_LINE_ONE_PSTN);
			}
			
			// update extension in incoming context
			$r = $db->actionQuery(sprintf("UPDATE extensions SET app='%s', appdata='%s' WHERE (context='%s' AND exten='_%s')", ExtensionController::EXT_APP_DIAL, sprintf("SIP/%d", $line_number), ExtensionController::EXT_CONTEXT_INCOMING, $pstn_number));
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			
			// update extension in outgoing context
			// find Set CID for pstn number
			$set = $db->selectQuery(sprintf("SELECT id, priority FROM extensions WHERE (context='%s' AND exten='%s' AND app='%s' AND appdata LIKE '%%%s')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, ExtensionController::EXT_APP_SET, $pstn_number));
			if($set == Database::EMPTY_RESULT || !is_array($set) || !is_numeric($set[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			// find GotoIf for pstn number
			$goto = $db->selectQuery(sprintf("SELECT id, appdata FROM extensions WHERE (context='%s' AND exten='%s' AND priority='%d')", ExtensionController::EXT_CONTEXT_OUTGOING, ExtensionController::EXT_OUTGOING_EXTENSION, $set[1] - 1));
			if($goto == Database::EMPTY_RESULT || !is_array($goto) || !is_numeric($goto[0])){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$gotoif_id = $goto[0];
			$gotoif_appdata = $goto[1];
			
			$pos_rquot = strrpos($gotoif_appdata, "\"");
			$pos_lquot = strrpos(substr($gotoif_appdata, 0, $pos_rquot), "\"");
			$val_bef = substr($gotoif_appdata, 0, $pos_lquot + 1);
			$val_aft = substr($gotoif_appdata, $pos_rquot, strlen($gotoif_appdata));
			$appdata = sprintf("%s%d%s", $val_bef, $line_number, $val_aft);
			
			$r = $db->actionQuery(sprintf("UPDATE extensions SET appdata='%s' WHERE (id='%d')", $appdata, $gotoif_id));
			if($r != 1 && $r != 0){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$db->commitTransaction();
		}catch(NoticeException $e){
			$db->rollbackTransactioin();
			$db->closeTransaction();
			throw new NoticeException($e->getCode());
		}catch(WarningException $e){
			$db->rollbackTransactioin();
			$db->closeTransaction();
			throw new WarningException($e->getCode());
		}
		$db->closeTransaction();
		return true;
	}

	/**
	 * Check if PSTN number exists.
	 *
	 * @param Database $db        	
	 * @param string $pstn_number        	
	 * @return boolean
	 */
	public static function pstnNumberExists(Database $db, $pstn_number){
		$r = $db->selectQuery(sprintf("SELECT COUNT(id) FROM extensions WHERE (context='%s' AND exten='_%s')", ExtensionController::EXT_CONTEXT_INCOMING, $pstn_number));
		return ($r[0] == 0) ? false : true;
	}

	/**
	 * Check if PSTN number is registered.
	 *
	 * @param Database $db        	
	 * @param string $pstn_number        	
	 * @return boolean
	 */
	public static function isPstnNumberRegistered(Database $db, $pstn_number){
		$r = $db->selectQuery(sprintf("SELECT COUNT(id) FROM extensions WHERE (context='%s' AND exten='_%s' AND app='%s')", ExtensionController::EXT_CONTEXT_INCOMING, $pstn_number, ExtensionController::EXT_APP_DIAL));
		return ($r[0] == 1) ? true : false;
	}

	/**
	 * Get PSTN unregistered list.
	 *
	 * @param Database $db        	
	 * @return 2D array or null
	 */
	public static function getPstnUnregistredList(Database $db){
		$a = $db->selectQuery(sprintf("SELECT id,exten FROM extensions WHERE (context='%s' AND app='%s')", ExtensionController::EXT_CONTEXT_INCOMING, ExtensionController::EXT_APP_HANGUP));
		if($a == Database::EMPTY_RESULT || !is_array($a)){
			return null;
		}
		$a = (is_array($a[0])) ? $a : array(
				$a
		);
		$r = array();
		for($i = 0; $i < count($a); $i++){
			$r[] = array(
					$a[$i][0],
					substr($a[$i][1], 6)
			);
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get extension item from db by id.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public static function getExtensionData(Database $db, $id){
		$r = $db->selectQuery(sprintf("SELECT id,context,exten,priority,app,appdata FROM extensions WHERE (id='%d')", $id));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Get registered PSTN number for internal line.
	 *
	 * @param Database $db        	
	 * @param integer $line_number        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public static function getLineRegisteredPstn(Database $db, $line_number){
		$r = $db->selectQuery(sprintf("SELECT id,context,exten,priority,app,appdata FROM extensions WHERE (context='%s' AND app='%s' AND appdata LIKE 'SIP/%s')", ExtensionController::EXT_CONTEXT_INCOMING, ExtensionController::EXT_APP_DIAL, $line_number));
		return ($r != Database::EMPTY_RESULT && is_array($r) && System::isArrayMultidimensional($r) === false) ? $r : Database::EMPTY_RESULT;
	}

	/**
	 * Remove extension from db by id.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @return integer
	 */
	public static function removeExtensionDb(Database $db, $id){
		$r = $db->actionQuery(sprintf("DELETE FROM extensions WHERE (id='%d')", $id));
		return $r;
	}

	/**
	 * Remove extension from dialplan by id.
	 *
	 * @param Database $db        	
	 * @param integer $id        	
	 * @throws WarningException
	 * @return boolean
	 */
	public static function removeExtension(Database $db, $id){
		try{
			// remove from db
			$db->beginTransaction();
			$r = self::removeExtensionDb($db, $id);
			if($r < 1){
				throw new WarningException(WarningException::WARNING_UNABLE_VERIFY_RESULT);
			}
			$db->commitTransaction();
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