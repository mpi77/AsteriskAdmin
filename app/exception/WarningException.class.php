<?php

/**
 * WarningException is used to inform user that called action crashed
 * due to internal program error. This final state is CRITICAL for user 
 * because it means that some program function crashed, but whole program 
 * may process ahead. It is possible to make step back, but this
 * state is logged. In normal running program, this type of exception 
 * should not be thrown. Typicaly thrown by incorect SQL query, bad ACL, etc. 
 * 
 * @version 1.4
 * @author MPI
 * */
class WarningException extends Exception{
	const WARNING_UNKNOWN = 0;
	const WARNING_CLASS_NOT_FOUND = 1;
	const WARNING_ACTION_IS_NOT_CALLABLE = 2;
	const WARNING_INVALID_ROUTE = 3;
	const WARNING_INVALID_SQL_SELECT = 4;
	const WARNING_INVALID_SQL_ACTION = 5;
	const WARNING_UNABLE_VERIFY_RESULT = 6;
	const WARNING_UNABLE_COMPLETE_TRANSACTION = 7;
	
	private static $error = array(
			self::WARNING_UNKNOWN => Translator::WARNING_UNKNOWN,
			self::WARNING_CLASS_NOT_FOUND => Translator::WARNING_CLASS_NOT_FOUND,
			self::WARNING_ACTION_IS_NOT_CALLABLE => Translator::WARNING_ACTION_IS_NOT_CALLABLE,
			self::WARNING_INVALID_ROUTE => Translator::WARNING_INVALID_ROUTE,
			self::WARNING_INVALID_SQL_SELECT => Translator::WARNING_INVALID_SQL_SELECT,
			self::WARNING_INVALID_SQL_ACTION => Translator::WARNING_INVALID_SQL_ACTION,
			self::WARNING_UNABLE_VERIFY_RESULT => Translator::WARNING_UNABLE_VERIFY_RESULT,
			self::WARNING_UNABLE_COMPLETE_TRANSACTION => Translator::WARNING_UNABLE_COMPLETE_TRANSACTION
	);

	public function __construct($code = 0){
		if(!array_key_exists($code, self::$error)){
			$code = 0;
		}
		parent::__construct(null, $code);
	}

	public function __toString(){
		return __CLASS__ . ": [{$this->code}]: " . self::getTextMessage($this->code) . "\n";
	}

	/**
	 * Get message of exception.
	 *
	 * @param integer $code        	
	 * @return string
	 */
	public static function getTextMessage($code){
		return Translate::get(self::$error[$code]);
	}

	/**
	 * Get name of this class.
	 */
	public function getName(){
		return get_class($this);
	}
}
?>