<?php
 
define('IN_ADMIN', true);
define('IN_ADMIN_LOGIN', true);

require_once("../config.inc.php");

if (isset($_POST['login'])) {
	if (empty(trim($_POST['username'])) || strpos($_POST['username'], ' ') !== false  || empty(trim($_POST['password'])) || ctype_digit($_POST['username'])) {
		echo ALERT_INVALID_INPUT;
	} else {
		$username = strtolower($con->real_escape_string($_POST['username']));
		$password = $con->real_escape_string($_POST['password']); 
		
		
		$hashed_password = md5($password);
				
		$query = $con->query("SELECT * FROM users WHERE Username = '{$username}' AND Password = '{$hashed_password}' AND IsAdmin = 1");
		if ($query->num_rows == 1) {
			$_SESSION['admin'] = $username;
			header('Location: index.php');
		} else {
			echo ALERT_ACCOUNT_NOT_FOUND;
		}
	}
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

    <title>Login</title>
  </head>
  <body style="background: url('https://image.freepik.com/free-vector/gradient-blue-abstract-background_1159-3090.jpg') no-repeat !important; background-size: cover !important;">
	
	<div class="container">
		<div class="row" style="padding-top: 50px;">
			<div class="col-md-4"></div>
			<div class="col-md-4" style=" 
  background-color: rgba(0, 0, 0, 0.1);  
  filter: alpha(opacity=60); color: white;">
				<form style="padding: 30px;" action="" method="post">
				  <div class="form-group">
					<label for="inputUsername">Username</label>
					<input type="text" class="form-control" id="inputUsername" aria-describedby="emailHelp" name="username" placeholder="Enter username"> 
				  </div>
				  <div class="form-group">
					<label for="inputPassword">Password</label>
					<input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password">
				  </div> 
				  <button type="submit" name="login" value="1" class="btn btn-primary">Submit</button>
				</form>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>