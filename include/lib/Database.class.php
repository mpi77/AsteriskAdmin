<?php

/**
 * Database provides interaction between this program
 * and db server.
 *
 * @version 1.9
 * @author MPI
 *        
 */
class Database{
	private $link = null;
	private $connection_params = null;
	public $status = null;
	const EMPTY_RESULT = -1;

	/**
	 * Initialize connection with db server.
	 *
	 * @param array $connection_params
	 *        	with keys[server, port, login, password, schema]
	 * @throws FailureException
	 */
	public function __construct($connection_params){
		$this->connection_params = $connection_params;
		if(empty($this->connection_params)){
			throw new FailureException(FailureException::FAILURE_MISSING_CONFIG_DB);
		}else{
			$this->connect();
		}
	}

	/**
	 * Ask SELECT query on db.
	 *
	 * @param string $sql
	 *        	SELECT single query
	 * @throws WarningException
	 * @return 2D array (more rows fetched) | 1D array (one row fetched) | EMPTY_RESULT (nothing fetched)
	 */
	public function selectQuery($sql){
		$result = $this->link->query($sql);
		if($result === false){
			throw new WarningException(WarningException::WARNING_INVALID_SQL_SELECT);
		}else{
			if($result->num_rows > 1){
				$a = array();
				while($s = $result->fetch_array(MYSQLI_NUM)){
					$a[] = $s;
				}
				return $a;
			}else if($result->num_rows == 1){
				return $result->fetch_array(MYSQL_NUM);
			}else
				return self::EMPTY_RESULT;
		}
	}

	/**
	 * Ask INSERT, UPDATE, DELETE query on db.
	 *
	 * @param string $sql
	 *        	action (INSERT, UPDATE, DELETE) single query
	 * @throws WarningException
	 * @return number of affected rows
	 */
	public function actionQuery($sql){
		$result = $this->link->query($sql);
		if($result === false){
			throw new WarningException(WarningException::WARNING_INVALID_SQL_ACTION);
		}else{
			if($this->link->affected_rows == 0){
				return 0;
			}else{
				return $this->link->affected_rows;
			}
		}
	}

	/**
	 * Close opened connection
	 */
	public function close(){
		$this->link->close();
	}

	/**
	 * Get autoincrement ID of last generated row.
	 *
	 * @return number of last generated row
	 */
	public function lastGeneratedId(){
		return $this->link->insert_id;
	}

	/**
	 * Get escaped input string.
	 *
	 * @param string $text
	 *        	input text to be escaped
	 * @return string
	 */
	public function escape($text){
		return $this->link->real_escape_string($text);
	}

	/**
	 * Start transaction.
	 * After commit/rollback must be called closeTransaction function.
	 */
	public function beginTransaction(){
		$this->link->autocommit(false);
	}

	/**
	 * Commit current transaction.
	 */
	public function commitTransaction(){
		$this->link->commit();
	}

	/**
	 * Rollback current transaction.
	 */
	public function rollbackTransactioin(){
		$this->link->rollback();
	}

	/**
	 * Close current transaction.
	 * It must be called with every usage of beginTransaction function.
	 */
	public function closeTransaction(){
		$this->link->autocommit(true);
	}

	/**
	 * Test connection to db server.
	 *
	 * @param array $connection_params
	 *        	with keys[server, login, password, schema]
	 * @throws FailureException
	 * @return true (if succesfull test) | false (if unsuccesfull test)
	 */
	public static function testConnection($connection_params){
		$link = null;
		
		if(empty($connection_params))
			return false;
		
		$auth = $connection_params;
		$link = new mysqli($auth["server"], $auth["login"], $auth["password"], $auth["schema"], $auth["port"]);
		if($link->connect_errno > 0){
			return false;
		}
		return true;
	}

	/**
	 * Connect to db server.
	 *
	 * @throws FailureException
	 */
	private function connect(){
		$auth = $this->connection_params;
		
		$this->link = new mysqli($auth["server"], $auth["login"], $auth["password"], $auth["schema"], $auth["port"]);
		if($this->link->connect_errno > 0){
			$this->status = false;
			throw new FailureException(FailureException::FAILURE_UNABLE_CONNECT_DB);
		}
		if(!$this->link->set_charset("utf8")){
			$this->status = false;
			throw new FailureException(FailureException::FAILURE_UNABLE_SET_DB_CHARSET);
		}
	}
}

?>