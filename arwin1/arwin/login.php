<?php

define('IN_LOGIN', true);

require_once("config.inc.php");

if (isset($_POST['submit'])) {
	if ($_POST['submit'] == 'login') {
		
		if (empty(trim($_POST['username'])) || strpos($_POST['username'], ' ') !== false  || empty(trim($_POST['password'])) || ctype_digit($_POST['username'])) {
			echo ALERT_INVALID_INPUT;
		} else {
			$username = strtolower($con->real_escape_string($_POST['username']));
			$password = $con->real_escape_string($_POST['password']);
			
			$hashed_password = md5($password);
			
			$query = $con->query("SELECT * FROM users WHERE Username = '{$username}' AND Password = '{$hashed_password}' AND IsActive = 1");
			if ($query->num_rows == 1) {
				$_SESSION['username'] = $username;
				header('Location: index.php');
			} else {
				echo ALERT_ACCOUNT_NOT_FOUND;
			}
		}
		
		
		
	} else if ($_POST['submit'] == 'signup') {
		if (empty(trim($_POST['username'])) || strpos($_POST['username'], ' ') !== false || empty(trim($_POST['password1'])) || empty(trim($_POST['password2'])) || ctype_digit($_POST['username'])) {
			echo ALERT_INVALID_INPUT;
		} else {
			$username = strtolower($con->real_escape_string($_POST['username']));
			$password1 = $con->real_escape_string($_POST['password1']);
			$password2 = $con->real_escape_string($_POST['password2']);
			$query = $con->query("SELECT * FROM users WHERE Username = '$username'");
			if ($query->num_rows <= 0) {
				if ($password1 == $password2) {
					$hashed_password = md5($password1);
					$con->query("INSERT INTO users (UserID, Username, Password) VALUES (NULL, '$username', '$hashed_password')");
					$con->query("INSERT INTO `user_settings` (`SettingID`, `UserID`, `TimelinePrivacy`) VALUES (NULL, '{$con->insert_id}', '1')");
					
					$_SESSION['username'] = $username;
					header('Location: index.php');
				} else {
					echo ALERT_PASS_NOT_MATCH;
				}
			} else {
				echo ALERT_ACCOUNT_EXISTS;
			}
		}
	} else {
		echo ALERT_INVALID_REQUEST;
	}
}


?>




<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/-->

<!DOCTYPE html>
<html >
<head>
<title> Instaphotos - login</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<!--web-fonts-->
<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>
<!--js-->
<script src="js/jquery.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<link href="https://fonts.googleapis.com/css?family=Montserrat:300" rel="stylesheet">
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
<!-- //end-smoth-scrolling -->
</head>
<body style="background: url('https://d2v9y0dukr6mq2.cloudfront.net/video/thumbnail/B0xO8Q47eiwdg4eb8/abstract-moving-background-for-title-text-in-the-center-white-dots-connected-with-lines-on-2016-pantone-color-mix-rose-quartz-and-serenity-gradient-background_svfx6j-cqe_thumbnail-full01.png');
    background-repeat: no-repeat;
    background-size: cover !important; font-family: 'Montserrat', sans-serif !important;">
 
<div style="font-weight: normal !important; color: white;padding-bottom: 90px;">
	<div class="container">
		<div class="contact-us-main">
			<div class="contact-us-top"> 
			<br />
			<br />
			<br />
			</div><div class="col-md-4"></div>
			<div class="col-md-4" style=" 
  background-color: rgba(0, 0, 0, 0.1);  
  filter: alpha(opacity=60); /* For IE8 and earlier */">
				<h3 style="text-align: center; padding-top: 10px;">Login</h3> <br />
				<form action="" method="post">
					<div class="input-group" style="padding-bottom: 5px;">
						<span class="input-group-addon" style="background: #e8e8e8;
						">@</span>
						<input type="text" class="form-control" id="username" name="username" placeholder="username">
					</div> 
					<div class="input-group" style="padding-bottom: 5px;">
						<span class="input-group-addon" style="background: #e8e8e8;
						"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password" placeholder="password">
					</div> 
					<button type="submit" name="submit" value="login" class="btn btn-default">login</button>
				</form>
				<hr />
				<h3 style="text-align: center">Signup</h3> <br />
				<form style="padding-bottom: 20px;" action="" method="post">
					<div class="input-group" style="padding-bottom: 5px;">
						<span class="input-group-addon" style="background: #e8e8e8;
						">@</span>
						<input type="text" class="form-control" id="username" name="username" placeholder="username">
					</div> 
					<div class="input-group" style="padding-bottom: 5px;">
						<span class="input-group-addon" style="background: #e8e8e8;
						"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password1" placeholder="password">
					</div> 
					<div class="input-group" style="padding-bottom: 5px;">
						<span class="input-group-addon" style="background: #e8e8e8;
						"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password2" placeholder="confirm password">
					</div> 
					<button type="submit" name="submit" value="signup" class="btn btn-default">signup</button>
				</form> 
			</div><div class="col-md-4"></div>
			 
			<div class="clearfix"> </div>
		</div>
	</div>
</div>
 
 
	</body>
</html>