<?php

/**
 * NoticeException is used to inform user how called action finished.
 * This final state is only INFORMATIVE for user (show message) 
 * and it doesn't mean that app crashed while processing. Also this
 * state is not logged. Typicaly thrown by invalid user input, etc.
 *
 * @version 1.6
 * @author MPI
 * */
class NoticeException extends Exception{
	const NOTICE_UNKNOWN = 0;
	const NOTICE_LOGIN_FAILED = 1;
	const NOTICE_INVALID_PARAMETERS = 2;
	const NOTICE_PERMISSION_DENIED = 3;
	const NOTICE_PASSWORD_INVALID_FORMAT = 4;
	const NOTICE_INPUT_INVALID_FORMAT = 5;
	const NOTICE_SUCCESSFULLY_SAVED = 6;
	const NOTICE_LOGIN_REQUIRED = 7;
	const NOTICE_RENEW_EMAIL_ERROR = 8;
	const NOTICE_RENEW_EMAIL_SENDED = 9;
	const NOTICE_INVALID_TOKEN = 10;
	const NOTICE_PASSWORD_CHANGED = 11;
	const NOTICE_USER_NOT_FOUND = 12;
	const NOTICE_USER_CREATE_EMAIL_ERROR = 13;
	const NOTICE_EMAIL_USED_ENTER_ANOTHER = 14;
	const NOTICE_USER_CREATE_EMAIL_SENDED = 15;
	const NOTICE_USER_ACTIVATED = 16;
	const NOTICE_LINE_IS_USED = 17;
	const NOTICE_NOTHING_TO_DISPLAY = 18;
	const NOTICE_FILE_IS_NOT_DELETABLE = 19;
	const NOTICE_PSTN_LINE_IS_USED = 20;
	const NOTICE_PSTN_NOT_FOUND = 21;
	const NOTICE_PSTN_LINE_IS_ASSIGNED = 22;
	const NOTICE_PSTN_LINE_IS_FREE = 23;
	const NOTICE_PSTN_ONE_LINE_ONE_PSTN = 24;
	
	private static $notice = array(
			self::NOTICE_UNKNOWN => Translator::NOTICE_UNKNOWN,
			self::NOTICE_LOGIN_FAILED => Translator::NOTICE_LOGIN_FAILED,
			self::NOTICE_INVALID_PARAMETERS => Translator::NOTICE_INVALID_PARAMETERS,
			self::NOTICE_PERMISSION_DENIED => Translator::NOTICE_PERMISSION_DENIED,
			self::NOTICE_PASSWORD_INVALID_FORMAT => Translator::NOTICE_PASSWORD_INVALID_FORMAT,
			self::NOTICE_INPUT_INVALID_FORMAT => Translator::NOTICE_INPUT_INVALID_FORMAT,
			self::NOTICE_SUCCESSFULLY_SAVED => Translator::NOTICE_SUCCESSFULLY_SAVED,
			self::NOTICE_LOGIN_REQUIRED => Translator::NOTICE_LOGIN_REQUIRED,
			self::NOTICE_RENEW_EMAIL_ERROR => Translator::NOTICE_RENEW_EMAIL_ERROR,
			self::NOTICE_RENEW_EMAIL_SENDED => Translator::NOTICE_RENEW_EMAIL_SENDED,
			self::NOTICE_INVALID_TOKEN => Translator::NOTICE_INVALID_TOKEN,
			self::NOTICE_PASSWORD_CHANGED => Translator::NOTICE_PASSWORD_CHANGED,
			self::NOTICE_USER_NOT_FOUND => Translator::NOTICE_USER_NOT_FOUND,
			self::NOTICE_USER_CREATE_EMAIL_ERROR => Translator::NOTICE_USER_CREATE_EMAIL_ERROR,
			self::NOTICE_EMAIL_USED_ENTER_ANOTHER => Translator::NOTICE_EMAIL_USED_ENTER_ANOTHER,
			self::NOTICE_USER_CREATE_EMAIL_SENDED => Translator::NOTICE_USER_CREATE_EMAIL_SENDED,
			self::NOTICE_USER_ACTIVATED => Translator::NOTICE_USER_ACTIVATED,
			self::NOTICE_LINE_IS_USED => Translator::NOTICE_LINE_IS_USED,
			self::NOTICE_NOTHING_TO_DISPLAY => Translator::NOTICE_NOTHING_TO_DISPLAY,
			self::NOTICE_FILE_IS_NOT_DELETABLE => Translator::NOTICE_FILE_IS_NOT_DELETABLE,
			self::NOTICE_PSTN_LINE_IS_USED => Translator::NOTICE_PSTN_LINE_IS_USED,
			self::NOTICE_PSTN_NOT_FOUND => Translator::NOTICE_PSTN_NOT_FOUND,
			self::NOTICE_PSTN_LINE_IS_ASSIGNED => Translator::NOTICE_PSTN_LINE_IS_ASSIGNED,
			self::NOTICE_PSTN_LINE_IS_FREE => Translator::NOTICE_PSTN_LINE_IS_FREE,
			self::NOTICE_PSTN_ONE_LINE_ONE_PSTN => Translator::NOTICE_PSTN_ONE_LINE_ONE_PSTN
	);

	public function __construct($code = 0){
		if(!array_key_exists($code, self::$notice)){
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
		return Translate::get(self::$notice[$code]);
	}

	/**
	 * Get name of this class.
	 */
	public function getName(){
		return get_class($this);
	}
}
?>