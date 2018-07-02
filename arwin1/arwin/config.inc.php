<?php
//edit
define("MYSQL_HOST", "localhost");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "");
define("MYSQL_DB", "arwin_db");

//dont edit
define("ALERT_INVALID_REQUEST", "<script>alert('Invalid request');</script><meta http-equiv=\"refresh\" content=\"1\">");
define("ALERT_INVALID_INPUT", "<script>alert('Invalid input');</script><meta http-equiv=\"refresh\" content=\"1\">");
define("ALERT_ACCOUNT_NOT_FOUND", "<script>alert('Account not found!');</script><meta http-equiv=\"refresh\" content=\"1\">");
define("ALERT_PROFILE_NOT_FOUND", "<script>alert('Profile not found!');</script><meta http-equiv=\"refresh\" content=\"0; index.php\">");
define("ALERT_PASS_NOT_MATCH", "<script>alert('Passwords dont match');</script><meta http-equiv=\"refresh\" content=\"1\">");
define("ALERT_ACCOUNT_EXISTS", "<script>alert('Account already exists!');</script><meta http-equiv=\"refresh\" content=\"1\">");
define("ALERT_ERROR_IMG_UPLOAD", "<script>alert('Error uploading image.');</script><meta http-equiv=\"refresh\" content=\"1\">");
define("INFO_ACCOUNT_PRIVATE", "<h3 style=\"text-align: center\"><b>This account is set to private.</b></h3>");
define("SUCCESS_ACCOUNT_FOLLOW", "<meta http-equiv=\"refresh\" content=\"0\">");
define("SUCCESS_CHANGED_PASSWORD", "<script>alert('Password changed.');</script><meta http-equiv=\"refresh\" content=\"0\">");
define("SUCCESS_ACCOUNT_UPDATED", "<script>alert('Account updated!');</script><meta http-equiv=\"refresh\" content=\"0\">");

$con = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
$con->set_charset('utf8mb4');
session_start();

if (defined("IN_LOGIN")) { 
	if (isset($_SESSION['username'])) {
		header('Location: index.php');
	} 
} else if (defined("IN_PROFILE")){ 
	if (isset($_GET['u'])) {
		
	} else {
		if (!isset($_SESSION['username'])) {
			header('Location: login.php');
		}  
	}
} else if (defined("IN_ADMIN")) { 
	if (isset($_SESSION['admin']) && defined("IN_ADMIN_LOGIN")) {
		header('Location: index.php');
	} else if (!isset($_SESSION['admin']) && defined("IN_ADMIN_HOME")) {
		header('Location: login.php');
	}
}
?>