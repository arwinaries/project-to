<?php
define("IN_ADMIN_HOME", true);
define("IN_ADMIN", true);
require_once("../config.inc.php");

if (!isset($_GET['u'])) {
	header('Location: index.php');
}
 
 
$uid = $con->real_escape_string($_GET['u']);

$query = $con->query("SELECT * FROM users WHERE UserID = '{$uid}'");
if ($query->num_rows == 1) {
	$uobj = $query->fetch_object();
	$_SESSION['username'] = $uobj->Username; 
	unset($_SESSION['admin']); 
	header('Location: ../index.php');
} else {
	echo ALERT_ACCOUNT_NOT_FOUND;
}

?>