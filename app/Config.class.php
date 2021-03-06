<?php
/**
 * Config stores and servers required configuration values.
 *
 * @version 1.5
 * @author MPI
 * */
class Config{
	const SITE_PATH = "http://localhost/asterisk_admin/";
	const SITE_BASE = "/asterisk_admin/";
	const SHUTDOWN_PAGE = "500"; // code of error page
	const LOG_DIR = "log";
	const LOG_SIZE = 4194304; // 4 MB
	const TIME_ZONE = "Europe/Prague";
	const SERVER_FQDN = "sip.yourserver.com";
	const SERVER_INTERNAL_IP = "10.0.0.12";
	const SERVER_PORT = "5060";
	const SERVER_PROTOCOL = "SIP";
	private static $db_params = array(
			"server" => "localhost",
			"port" => "3306",
			"login" => "asterisk_admin",
			"password" => "asterisk",
			"schema" => "asterisk_admin"
	);
	private static $email = array(
			"server" => "smtp.yourserver.com",
			"username" => "asterisk@yourserver.com",
			"password" => "hdtGFDRt56",
			"port" => "25",
			"smtp_auth" => true,
			"from_name" => "AsteriskAdmin",
			"smtp_secure" => null
	);

	private function __construct(){
	}

	/**
	 * Get configuration parameters to db.
	 *
	 * @return string array
	 */
	public static function getDbParams(){
		return self::$db_params;
	}

	/**
	 * Get configuration parameters to email server.
	 *
	 * @return string array
	 */
	public static function getEmailParams(){
		return self::$email;
	}
}
?>