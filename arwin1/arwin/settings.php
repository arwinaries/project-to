<?php 
define("IN_PROFILE", true);
require_once("config.inc.php");
/**   
	SEARCH 
	
	admin
**/
$currentUsername = isset($_GET['u']) ? $_GET['u'] : $_SESSION['username'];

//load user data
$_username = $con->real_escape_string($currentUsername);
$query = $con->query("SELECT * FROM users WHERE Username = '{$_username}' LIMIT 1");

if ($query->num_rows <= 0) {
	die(ALERT_PROFILE_NOT_FOUND);
} 
$user_obj = $query->fetch_object();
$isMe = false;
if (isset($_SESSION['username'])) {
	$query = $con->query("SELECT * FROM users WHERE Username = '{$_SESSION['username']}' LIMIT 1");

	$isMe = $user_obj->UserID == $query->fetch_object()->UserID;
}

if ($isMe && isset($_GET['u'])) {
	header('Location: index.php');
}

$query = $con->query("SELECT * FROM posts WHERE Poster = '{$user_obj->UserID}'");
$post_count = $query->num_rows;

$query = $con->query("SELECT * FROM user_followers WHERE UserID = '{$user_obj->UserID}'");
$followers_count = $query->num_rows;

$query = $con->query("SELECT * FROM user_followers WHERE FollowerID = '{$user_obj->UserID}'");
$following_count = $query->num_rows;
 
$query = $con->query("SELECT * FROM user_settings WHERE UserID = '{$user_obj->UserID}'");
$settings_obj = $query->fetch_object();

$isFollowing = false;
if (!$isMe && isset($_SESSION['username'])) {
	$meUser = $con->real_escape_string($_SESSION['username']);
	$query = $con->query("SELECT * FROM users WHERE Username = '{$meUser}' LIMIT 1");
	$meObj = $query->fetch_object();

	$query = $con->query("SELECT * FROM user_followers WHERE FollowerID = {$meObj->UserID}");
	$isFollowing = $query->num_rows > 0;
} 

if (isset($_POST['submit'])) {
	if ($_POST['submit'] == 'editpass') {
		if (empty(trim($_POST['password1'])) || empty(trim($_POST['password2']))) {
			echo ALERT_INVALID_INPUT;
		} else {
			$password1 = $con->real_escape_string($_POST['password1']);
			$password2 = $con->real_escape_string($_POST['password2']);
			
			if ($password1 == $password2) {
				$hashed_password = md5($password1);
				$con->query("UPDATE `users` SET `Password` = '$hashed_password' WHERE  `UserID` = {$user_obj->UserID};");  
				echo SUCCESS_CHANGED_PASSWORD;
			} else {
				echo ALERT_PASS_NOT_MATCH;
			}
		}
	} else if ($_POST['submit'] == 'changePrivacy') {
		$level = $con->real_escape_string($_POST['privacy_level']);
		
		$con->query("UPDATE `user_settings` SET `TimelinePrivacy` = '$level' WHERE  `UserID` = {$user_obj->UserID};"); 
		echo SUCCESS_ACCOUNT_UPDATED;
	}
}

 
?>

<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/-->

<!DOCTYPE html>
<html>
<head>
<title>@<?php echo $user_obj->Username; ?> - instaphotos</title>
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
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
<!-- //end-smoth-scrolling -->
<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
<link href="css/magnific-popup.css" rel="stylesheet" type="text/css">
<script>
			$(document).ready(function() {
				$('.popup-with-zoom-anim').magnificPopup({
					type: 'inline',
					fixedContentPos: false,
					fixedBgPos: true,
					overflowY: 'auto',
					closeBtnInside: true,
					preloader: false,
					midClick: true,
					removalDelay: 300,
					mainClass: 'my-mfp-zoom-in'
			});
		});
		</script>
</head>
<body>
<!--header start here-->
 <div class="header" id="home">
       <div class="container">
		  	   <div class="header-main">
		  	    	   <div class="logo"  style="padding-top: 30px;"> 
							<form action="index.php" method="get"><input name='u' type="text" placeholder="search username" /> <button type="submit">search</button></form>
		  	           </div>
		  	           <span class="menu" >  </span> 
		  	           <div class="clear"> </div>
		  	           <div class="header-right" style="padding-bottom: 50px;">  
									<?php if (!isset($_SESSION['username'])) { ?>
										<ul class="res"> 
											<li><a href="login.php">Login</a></li> 
											<li><a href="login.php">Signup</a></li> 
										</ul> 
									<?php } else { ?>
										<ul class="res"> 
											<li><a href="index.php">Profile</a></li> 
											<li><a href="settings.php">Settings</a></li> 
											<li><a href="logout.php">Logout</a></li> 
										</ul>
									<?php } ?>
		  	             	          <script>
			                                                      $( "span.menu").click(function() {
			                                                                                        $(  "ul.res" ).slideToggle("slow", function() {
			                                                                                         // Animation complete.
			                                                                                         });
			                                                                                         });
		                                                     </script>
		                                                                   
		  	          </div>
		  	      <div class="clearfix"> </div>
		  	  </div> 
									
								 
										 
	   </div>    
 </div>
 <!---pop-up-box---->
					  <script type="text/javascript" src="js/modernizr.custom.min.js"></script>    
					<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all"/>
					<script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
			<!---//pop-up-box---->

<!--work start here-->
<div class="work">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
			</div>
			
			<div class="col-md-4">
				<h3 style="text-align: center;">Edit password</h3>
				<form action="" method="post">
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
					<button type="submit" name="submit" value="editpass" class="btn btn-default">edit</button>
				</form>
				<hr />
				<h3 style="text-align: center;">Edit privacy</h3> <br />	
				<form action="" method="post">
					<div class="form-group">
						<span class="input-group-addon" style="background: #e8e8e8;
						">Timeline privacy</span>
						<select class="form-control" name="privacy_level">
						
							<option value="1" <?php echo ($settings_obj->TimelinePrivacy == 1 ? "selected" : "");?>>Public</option>
							<option value="0" <?php echo ($settings_obj->TimelinePrivacy == 0 ? "selected" : "");?>>Private</option>
							
						</select>
						
						<button type="submit" name="submit" value="changePrivacy" class="btn btn-default">edit</button>
					</div>
				</form>
			</div>
			
			<div class="col-md-4">
			</div>
		</div>
		<!--
		
		-->
	</div>
</div>
 
 <!--footer start here-->
 <div class="footer">
 
 </div>
	</body>
</html>