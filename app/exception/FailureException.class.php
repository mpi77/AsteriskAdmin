<?php

/**
 * FailureException is used to inform user that called action crashed.
 * This final state is CRITICAL for user because it means that app crashed 
 * and it is impossible to make step back. This state is logged and redirected to 
 * shutdown page, because it is impossible to continue witch prosessing.
 * Typicaly thrown by unavailable database, etc.
 *
 * @version 1.4
 * @author MPI
 * */
class FailureException extends Exception {
	const FAILURE_UNKNOWN = 0;
	const FAILURE_MISSING_CONFIG_DB = 1;
	const FAILURE_UNABLE_CONNECT_DB = 2;
	const FAILURE_UNABLE_SET_DB_CHARSET = 3;
	const FAILURE_UNABLE_SAVE_WARNING = 4;
	private static $error = array(
			self::FAILURE_UNKNOWN => Translator::FAILURE_UNKNOWN,
			self::FAILURE_MISSING_CONFIG_DB => Translator::FAILURE_MISSING_CONFIG_DB,
			self::FAILURE_UNABLE_CONNECT_DB => Translator::FAILURE_UNABLE_CONNECT_DB,
			self::FAILURE_UNABLE_SET_DB_CHARSET => Translator::FAILURE_UNABLE_SET_DB_CHARSET,
			self::FAILURE_UNABLE_SAVE_WARNING => Translator::FAILURE_UNABLE_SAVE_WARNING 
	);

	public function __construct($code = 0) {
		if (! array_key_exists($code, self::$error)) {
			$code = 0;
		}
		parent::__construct(null, $code);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: " . self::getTextMessage($this->code) . "\n";
	}

	/**
	 * Get message of exception.
	 *
	 * @param integer $code        	
	 * @return string
	 */
	public static function getTextMessage($code) {
		return Translate::get(self::$error[$code]);
	}

	/**
	 * Get name of this class.
	 */
	public function getName() {
		return get_class($this);
	}
}
?>