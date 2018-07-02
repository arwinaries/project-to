<?php
define("IN_ADMIN_HOME", true);
define("IN_ADMIN", true);
require_once("../config.inc.php");

if (!isset($_GET['u'])) {
	header('Location: index.php');
}
$_username = $con->real_escape_string($_GET['u']);
$query = $con->query("SELECT * FROM users WHERE UserID = '{$_username}' LIMIT 1");

if ($query->num_rows <= 0) {
	die(ALERT_PROFILE_NOT_FOUND);
} 
$user_obj = $query->fetch_object();

$query = $con->query("SELECT * FROM user_settings WHERE UserID = '{$user_obj->UserID}'");
$settings_obj = $query->fetch_object();


$query = $con->query("SELECT * FROM posts WHERE Poster = '{$user_obj->UserID}'");
$post_count = $query->num_rows;

$query = $con->query("SELECT * FROM user_followers WHERE UserID = '{$user_obj->UserID}'");
$followers_count = $query->num_rows;

$query = $con->query("SELECT * FROM user_followers WHERE FollowerID = '{$user_obj->UserID}'");
$following_count = $query->num_rows;

if (isset($_POST['submit'])) {
	$passChanged = false;
	if (empty(trim($_POST['password1'])) || empty(trim($_POST['password2']))) { 
	} else {
		$password1 = $con->real_escape_string($_POST['password1']);
		$password2 = $con->real_escape_string($_POST['password2']);
			
		if ($password1 == $password2) {
			$hashed_password = md5($password1);
			$con->query("UPDATE `users` SET `Password` = '$hashed_password' WHERE  `UserID` = {$user_obj->UserID};");  
			$passChanged = true;
		} else {
			die( ALERT_PASS_NOT_MATCH);
		}
	}
	
	$level = $con->real_escape_string($_POST['privacy_level']);
	$setAdmin = $con->real_escape_string($_POST['setAdmin']);
	$setActive = $con->real_escape_string($_POST['setActive']);
	if ($level != -1) {	
		$con->query("UPDATE `user_settings` SET `TimelinePrivacy` = '$level' WHERE  `UserID` = {$user_obj->UserID};"); 
	}
	if ($setAdmin != -1)
		$con->query("UPDATE `users` SET `IsAdmin` = '$setAdmin' WHERE  `UserID` = {$user_obj->UserID};"); 
	if ($setActive != -1)
	$con->query("UPDATE `users` SET `IsActive` = '$setActive' WHERE  `UserID` = {$user_obj->UserID};"); 
	echo SUCCESS_ACCOUNT_UPDATED;
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Admin</title>
  </head>
  <body>
    <div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <a class="navbar-brand" href="#">Admin</a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class="collapse navbar-collapse" id="navbarText">
			<ul class="navbar-nav mr-auto">
			  <li class="nav-item">
				<a class="nav-link" href="index.php">Home  </a>
			  </li> 
			</ul> 
		  </div>
		</nav>
	</div>
	
	<div class="container" style="padding-top: 20px;">
		<div class="row"> 
		<div class="col-md-12">
			<h3><b>@<?php echo $user_obj->Username; ?></b></h3> <small> <a href="switchuser.php?u=<?php echo $user_obj->UserID; ?>">Login as @<?php echo $user_obj->Username; ?></a> (use this to delete user posts)</small> <br /><br />
			<p><b><?php echo $post_count; ?> posts</b></p> 
			<p><b><?php echo $following_count; ?> following</b></p> 
			<p><b><?php echo $followers_count; ?> followers</b></p> 
			
			 
			<form action="" method="post">
					<div class="input-group" style="padding-bottom: 5px;"> 
						<input type="password" class="form-control" id="password" name="password1" placeholder="password (ignore to leave the password as is)">
					</div> 
					<div class="input-group" style="padding-bottom: 5px;"> 
						<input type="password" class="form-control" id="password" name="password2" placeholder="confirm password (ignore to leave the password as is)">
					</div> 
					<div class="form-group">
						<span class="input-group-addon" style="background: #e8e8e8;
						">Timeline privacy (current value: <?php echo ($settings_obj->TimelinePrivacy == 1 ? "Public" : "Private");?>)</span>
						<select class="form-control" name="privacy_level">
							<option value="-1">dont change</option>
							<option value="1" >Public</option>
							<option value="0" >Private</option>
							
						</select>
						 
					</div>
					
					<div class="form-group">
						<span class="input-group-addon" style="background: #e8e8e8;
						">Set as admin?</span>
						<select class="form-control" name="setAdmin"> 
							<option value="-1">dont change</option>
							<option value="0" >No</option> <option value="1" >Yes</option>
						</select>
						 
					</div>
					
					<div class="form-group">
						<span class="input-group-addon" style="background: #e8e8e8;
						">Set active (current state: <?php echo $user_obj->IsActive == 1 ? "active" : "banned";?>)</span>
						<select class="form-control" name="setActive">
						<option value="-1">dont change</option>
							<option value="1">Yes</option>
							<option value="0" >No</option> 
						</select>
						
					</div>
					<button type="submit" name="submit" value="edit" class="btn btn-default">update</button>
				</form>
		</div> 
		
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>