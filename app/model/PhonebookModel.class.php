<?php
/**
 * Phonebook model.
 *
 * @version 1.4
 * @author MPI
 * */
class PhonebookModel extends Model{

	public function __construct($db){
		parent::__construct($db);
	}

	public function getName(){
		return get_class($this);
	}

	/**
	 * Get phonebook list data.
	 *
	 * @param integer $page        	
	 * @param integer $page_size        	
	 * @param integer $order_column        	
	 * @param string $order_direction        	
	 * @param boolean $disable_pagging        	
	 * @return 2D array or null
	 */
	public function getPhonebookList($page, $page_size, $order_column, $order_direction, $disable_pagging = false){
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
				0 => "sip.name",
				1 => "sip.callerid",
				2 => "IF(LENGTH(extensions.exten) > 0,SUBSTR(extensions.exten, 7),'')",
				3 => "IF(LENGTH(user.first_name) > 0 AND LENGTH(user.last_name) > 0, CONCAT(user.first_name, ' ', user.last_name), user.email)"
		);
		$order_by = array_key_exists($order_column, $select_columns) ? $select_columns[$order_column] : $select_columns[0];
		$order_dir = ($order_direction == System::SORT_DES) ? System::SORT_DES_FULL : System::SORT_ASC_FULL;
		$sql = sprintf("SELECT %s FROM sip INNER JOIN user ON sip.uid=user.uid LEFT JOIN extensions ON sip.name = SUBSTR(extensions.appdata, 5) ORDER BY %s %s %s;", implode(",", $select_columns), $order_by, $order_dir, (!is_null($offset) && !is_null($limit)) ? sprintf("LIMIT %d,%d", $offset, $limit) : "");
		$r = $this->getDb()->selectQuery($sql);
		if($r == Database::EMPTY_RESULT || !is_array($r)){
			return null;
		}
		return (System::isArrayMultidimensional($r)) ? $r : array(
				$r
		);
	}

	/**
	 * Get count of phonebook list items.
	 * 
	 * @return integer
	 */
	public function getCountPhonebookList(){
		$r = $this->getDb()->selectQuery(sprintf("SELECT COUNT(*) FROM sip INNER JOIN user ON sip.uid=user.uid LEFT JOIN extensions ON sip.name = SUBSTR(extensions.appdata, 5)"));
		return intval($r[0]);
	}
}
?>