<?php
/**
 * Cdr model.
 *
 * @version 1.4
 * @author MPI
 * */
class CdrModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Get cdr list data for line.
	 * 
	 * @param integer $line_number        	
	 * @param integer $page        	
	 * @param integer $page_size        	
	 * @param integer $order_column        	
	 * @param string $order_direction        	
	 * @param boolean $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getCdrList($line_number, $page, $page_size, $order_column, $order_direction, $disable_pagging = false){
		if(empty($line_number) || empty($page) || empty($page_size)){
			return null;
		}
		$offset = Paginator::getStartRow($page, $page_size);
		$limit = $page_size;
		if($disable_pagging === true){
			$offset = null;
			$limit = null;
		}
		$select_columns = array(
				0 => "calldate",
				1 => sprintf("IF(src='%d','%s','%s')", $line_number, Translate::get(Translator::CDR_LIST_INCOMING), Translate::get(Translator::CDR_LIST_OUTGOING)),
				2 => "src",
				3 => "dst",
				4 => "duration",
				5 => "billsec",
				6 => sprintf("IF(disposition='%s','%s',IF(disposition='%s','%s',IF(disposition='%s','%s','%s')))", CdrController::DISPOSITION_NO_ANSWER, Translate::get(Translator::CDR_DISPOSITION_NO_ANSWER), CdrController::DISPOSITION_BUSY, Translate::get(Translator::CDR_DISPOSITION_BUSY), CdrController::DISPOSITION_ANSWERED, Translate::get(Translator::CDR_DISPOSITION_ANSWERED), Translate::get(Translator::CDR_DISPOSITION_FAILED))
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		$sql = sprintf("SELECT %s FROM cdr WHERE (src='%d' OR dst='%d') ORDER BY %s %s %s;", implode(",", $select_columns), $line_number, $line_number, $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of cdr list items for line.
	 *
	 * @param integer $uid        	
	 * @return integer
	 */
	public function getCountCdrList($line_number){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM cdr WHERE (src='%d' OR dst='%d')", $line_number, $line_number));
		return intval($r[0]);
	}

	/**
	 * Local wrapper for CdrModel::getCdrData($line_number).
	 *
	 * @param integer $line_number        	
	 * @return 2D array or null
	 */
	public function getData($line_number){
		return self::getCdrData($this->getDb(), $line_number);
	}

	/**
	 * Local wrapper for LineModelModel::getUserLines($db, $uid).
	 *
	 * @param integer $uid        	
	 * @return 2D array or null
	 */
	public function getUserLines($uid){
		return LineModel::getUserLines($this->getDb(), $uid);
	}

	/**
	 * Local wrapper for LineModel::getLineData($db, $id).
	 *
	 * @param integer $id        	
	 * @return 1D array or Database::EMPTY_RESULT
	 */
	public function getLineData($id){
		return LineModel::getLineData($this->getDb(), $id);
	}

	/**
	 * Local wrapper for CdrModel::removeCdr($db, $line_number).
	 *
	 * @param integer $line_number        	
	 * @return boolean
	 */
	public function remove($line_number){
		return self::removeCdr($this->getDb(), $line_number);
	}

	/**
	 * Get cdr list data by line number.
	 *
	 * @param Database $db        	
	 * @param integer $line_number        	
	 * @return 2D array or null
	 */
	public static function getCdrData(Database $db, $line_number){
		$r = $db->selectQuery(sprintf("SELECT id, DATE_FORMAT(calldate,'%%d.%%m.%%Y %%H:%%i:%%s'), clid, src, dst, dcontext, lastapp, lastdata, duration, billsec, disposition, amaflags FROM cdr WHERE (src='%d' OR dst='%d')", $line_number, $line_number));
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
		return $r;
	}

	/**
	 * Remove all cdr items in db by line number.
	 *
	 * @param Database $db        	
	 * @param integer $line_number        	
	 * @return integer
	 */
	public static function removeCdrDb(Database $db, $line_number){
		$r = $db->actionQuery(sprintf("DELETE FROM cdr WHERE (src='%d' OR dst='%d')", $line_number, $line_number));
		return $r;
	}

	/**
	 * Remove all cdr items by line number.
	 *
	 * @param Database $db        	
	 * @param integer $line_number        	
	 * @throws WarningException
	 * @return boolean
	 */
	public static function removeCdr(Database $db, $line_number){
		try{
			// remove from db
			$db->beginTransaction();
			$r = self::removeCdrDb($db, $line_number);
			if($r < 0){
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