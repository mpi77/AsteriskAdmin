<?php

/**
 * Acl class controls that user has privilege to do action.
 *
 * @version 1.1
 * @author MPI
 *
 */
class Acl{
	const ACL_USER_LOGIN = 1;

	public static function check($acl_module, $args = null){
		switch($acl_module){
			case self::ACL_USER_LOGIN:
				break;
			default:
				echo "not implemented yet";
		}
	}

	public static function isLoggedin(){
		return (isset($_SESSION["user"]["auth"]) && $_SESSION["user"]["auth"]);
	}

	public static function isRoot(){
		return (isset($_SESSION["user"]["type"]) && $_SESSION["user"]["type"] === UserController::USER_PERM_ROOT);
	}

	public static function isUser(){
		return (isset($_SESSION["user"]["type"]) && $_SESSION["user"]["type"] === UserController::USER_PERM_GROUP_ADMIN);
	}
}
?>