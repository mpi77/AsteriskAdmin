<?php
/**
 * Script to download voicemail file.
 * 
 * @version 1.3
 * @author MPI
 * */

session_start();
include "../lib/System.class.php";
include "../lib/Database.class.php";
include "../lib/Acl.class.php";
include "../../app/Config.class.php";
include "../../app/Model.class.php";
include "../../app/Controller.class.php";
include "../../app/model/LineModel.class.php";
include "../../app/model/VoicemailModel.class.php";
include "../../app/controller/VoicemailController.class.php";

System::initSession();
$db = new Database(Config::getDbParams());
$args = System::trimSlashArray1dAssociative($_GET, true, true);

if(Acl::isLoggedin() === true){
	if(!empty($args["line_id"]) && !empty($args["msg_id"]) && is_numeric($args["line_id"]) && is_numeric($args["msg_id"]) && intval($args["msg_id"]) >= VoicemailController::ASTERISK_STARTING_INDEX && LineModel::isLineOwner($db, $args["line_id"], $_SESSION["user"]["uid"]) && LineModel::hasLineVoicemail($db, $args["line_id"])){
		$line = LineModel::getLineData($db, $args["line_id"]);
		$filename = sprintf("%s%04d%s", VoicemailController::ASTERISK_FILENAME_PREFIX, intval($args["msg_id"]) - VoicemailController::ASTERISK_STARTING_INDEX, VoicemailController::ASTERISK_WAV_SUFFIX);
		$path = sprintf("%s%s%s", VoicemailController::ASTERISK_VOICEMAIL_PATH, sprintf(VoicemailController::ASTERISK_LINE_INBOX_PATH, $line[7], $line[2]), $filename);
		if(file_exists($path)){
			$handle = fopen($path, "rb");
			header("Content-Description: File Transfer");
			header("Content-Transfer-Encoding: binary");
			header("Content-Type: audio/x-wav, audio/wav");
			header("Content-length: " . filesize($path));
			header("Content-Disposition: attachment;filename=\"" . $filename . "\"");
			while(!feof($handle)){
				echo fread($handle, 4096);
				flush();
			}
			fclose($handle);
		}else{
			System::redirect(sprintf("%s404", Config::SITE_PATH));
		}
	}else{
		System::redirect(sprintf("%s404", Config::SITE_PATH));
	}
}else{
	System::redirect(sprintf("%s401", Config::SITE_PATH));
}
exit();
?>