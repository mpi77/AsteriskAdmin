<?php

/**
 * Email class provides sending emails.
 *
 * @version 1.4
 * @author MPI
 * */
class Email{
	const MSG_RENEW_TOKEN = 1;
	const MSG_UCR_TOKEN = 2;

	private function Email(){
	}

	/**
	 * Send email.
	 *
	 * @param array $args
	 *        	values given to address email recipient
	 * @param array $msg_data
	 *        	values given to print in email
	 * @return boolean
	 */
	public static function send($args, $msg_data){
		date_default_timezone_set(Config::TIME_ZONE);
		$account = Config::getEmailParams();
		$msg_data["timestamp"] = date("d.m.Y H:i:s");
		$msg_data["server"] = Config::SERVER_FQDN;
		$subject = null;
		$body = null;
		$altbody = null;
		
		switch($args["msg_index"]){
			case self::MSG_RENEW_TOKEN:
				require_once ("gui/email/send_renew_token.php");
				break;
			case self::MSG_UCR_TOKEN:
				require_once ("gui/email/send_ucr_token.php");
				break;
		}
		
		require_once ("include/phpmailer/class.phpmailer.php");
		$mail = new PHPMailer(true);
		$mail->IsSMTP();
		try{
			$mail->Host = $account["server"];
			$mail->SMTPDebug = 0;
			if(isset($account["smtp_secure"]) && !empty($account["smtp_secure"])){
				$mail->SMTPSecure = $account["smtp_secure"];
			}
			$mail->SMTPAuth = $account["smtp_auth"];
			$mail->Port = $account["port"];
			$mail->Username = $account["username"];
			$mail->Password = $account["password"];
			$mail->AddAddress($args["to_email"]);
			$mail->SetFrom($account["username"], $account["from_name"]);
			$mail->Subject = $subject;
			$mail->AltBody = $altbody;
			$mail->CharSet = "utf-8";
			$mail->MsgHTML($body);
			$mail->Send();
			$mail->clearAddresses();
			return true;
		}catch(phpmailerException $e){
			return false;
		}catch(Exception $e){
			return false;
		}
	}
}
?>