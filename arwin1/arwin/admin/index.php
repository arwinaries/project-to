<?php
define("IN_ADMIN_HOME", true);
define("IN_ADMIN", true);
require_once("../config.inc.php");

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
				<a class="nav-link" href="#">Home  </a>
			  </li> 
			</ul> 
		  </div>
		</nav>
	</div>
	
	<div class="container" style="padding-top: 20px;">
		<div class="row"> 
		<div class="col-md-12">
			<h3>Users</h3>
		<div class="table-responsive">
			<table class="table table-dark">
			  <thead>
				<tr>
				  <th scope="col">#</th>
				  <th scope="col">Username</th>
				  <th scope="col"></th> 
				</tr>
			  </thead>
			  <tbody> 
				<?php 
				
					$query = $con->query("SELECT * FROM users");
					while ($uObj = $query->fetch_object()) {
						echo "<tr>";
						echo "<th scope=\"row\">{$uObj->UserID}</th>";
						echo "<th><a target='_blank' href='../index.php?u={$uObj->Username}'>@{$uObj->Username}</a></th>";
						echo "<th><a class=\"btn btn-primary\" href=\"edituser.php?u={$uObj->UserID}\" role=\"button\">Edit</a></th>";
						echo "</tr>";
					}
				
				?>
			  </tbody>
			</table>
		</div>
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