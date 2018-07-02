<?php 
define("IN_PROFILE", true);
require_once("config.inc.php");
/**    
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

if ($isMe) {
	$meUser = $con->real_escape_string($_SESSION['username']);
	$query = $con->query("SELECT * FROM users WHERE Username = '{$meUser}' LIMIT 1");
	$meObj = $query->fetch_object();
	
	if ($meObj->IsActive == 0) { 
		header('Location: logout.php');
	}
}

function doFollow($uObj) {
	global $con;
	$meUser = $con->real_escape_string($_SESSION['username']);
	$query = $con->query("SELECT * FROM users WHERE Username = '{$meUser}' LIMIT 1");
	$meObj = $query->fetch_object();
	
	$tofollow = $con->real_escape_string($uObj->UserID);
	$query = $con->query("SELECT * FROM users WHERE UserID = '{$tofollow}' LIMIT 1"); 
	$toFollowObj = $query->fetch_object();
	
	$con->query("INSERT INTO user_followers (ID, UserID, FollowerID) VALUES (NULL, {$toFollowObj->UserID}, {$meObj->UserID})");
	echo SUCCESS_ACCOUNT_FOLLOW;
}

function doUnFollow($uObj) {
	global $con;
	$meUser = $con->real_escape_string($_SESSION['username']);
	$query = $con->query("SELECT * FROM users WHERE Username = '{$meUser}' LIMIT 1");
	$meObj = $query->fetch_object();
	
	$tofollow = $con->real_escape_string($uObj->UserID);
	$query = $con->query("SELECT * FROM users WHERE UserID = '{$tofollow}' LIMIT 1"); 
	$toFollowObj = $query->fetch_object();
	$con->query("DELETE FROM user_followers WHERE UserID = {$toFollowObj->UserID} AND FollowerID = {$meObj->UserID}"); 
	echo SUCCESS_ACCOUNT_FOLLOW;
}

function displayPosts($uObj, $isMe = false) {
	global $con;
	$post_tpl = file_get_contents("tpl/tpl_posts_row.php");
	$post_tpl_content = file_get_contents("tpl/tpl_posts_content.php");
	
	$query = $con->query("SELECT * FROM posts WHERE Poster = '{$uObj->UserID}' ORDER BY PostedOn DESC");
	$i = 0; 
	$content = $post_tpl; 
	$colcontent = "";
	$ctr = 1; 
	while ($obj = $query->fetch_object()) {
		$output = $post_tpl_content;
		
		$output = str_replace("{POST_COUNT}", $i, $output);
		$output = str_replace("{POST_DELETE}", $isMe ? "<span  style=\"position: relative;  z-index: 999;\"><form action=\"\" method=\"post\"><button name=\"delete_post\" value=\"{$obj->PostID}\">delete</button></form></span>" : "", $output);
		$output = str_replace("{POSTER}", !$isMe ? "<a href='index.php?u={$uObj->Username}'>@{$uObj->Username}</a>" : "@{$uObj->Username}", $output);
		$output = str_replace("{POST_DESC}", $obj->PostDesc, $output);
		
		$q2 = $con->query("SELECT * FROM photos WHERE PhotoID = {$obj->PhotoID}");
		$img_obj = $q2->fetch_object();
		$output = str_replace("{IMAGE_LOCATION}", $img_obj->PhotoLocation, $output); 
		$output = str_replace("{POST_ID}", $obj->PostID, $output); 
		
		$comment = "";
		
		$q3 = $con->query("SELECT * FROM comments WHERE PostID = {$obj->PostID}");
		while ($commentData = $q3->fetch_object()) {
			$uq = $con->query("SELECT * FROM users WHERE UserID = {$commentData->UserID}"); 
			$ud = $uq->fetch_object();
			$comment .= "<p><span style=\"padding-right: 10px; \"><b><a href=\"index.php?u={$ud->Username}\">@{$ud->Username}</a></b></span> {$commentData->CommentDesc}</p> ";
		}
		$output = str_replace("{POST_COMMENTS}", $comment, $output); 
		$colcontent .= $output; 
		$ctr++; 
		
		
		if ($ctr >= 4) {
			echo str_replace("{CONTENT}", $colcontent, $content);
			$colcontent = "";
			$ctr = 1;
			$content = $post_tpl;
		}
		$i++;
	}
	if ($query->num_rows <= 2 || $colcontent != "") {
		echo str_replace("{CONTENT}", $colcontent, $content);
	}
	
}

function addPost() {
	global $user_obj, $con;
	
	$uploaddir = 'photos/';
	$uploadfile = $uploaddir . md5(basename($_FILES['file']['name']) . time());
	
	// never assume the upload succeeded
	if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
	   die(ALERT_ERROR_IMG_UPLOAD);
	}

	$info = getimagesize($_FILES['file']['tmp_name']);
	if ($info === FALSE) {
		die(ALERT_ERROR_IMG_UPLOAD);
	}

	if (($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
	   die(ALERT_ERROR_IMG_UPLOAD);
	}
		
	
	echo "<p>";

	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		$con->query("INSERT INTO photos (PhotoID, PhotoLocation) VALUES (NULL, '$uploadfile')");
		$img_id  = $con->insert_id;
		$text = $con->real_escape_string($_POST['post_text']);
		$con->query("INSERT INTO posts (PostID, PhotoID, PostDesc, Poster) VALUES (NULL, '$img_id', '$text', {$user_obj->UserID})");
	  echo SUCCESS_ACCOUNT_FOLLOW;
	} else {
	   die(ALERT_ERROR_IMG_UPLOAD);
	}

}



function changePic() {
	global $user_obj, $con;
	
	$uploaddir = 'photos/';
	$uploadfile = $uploaddir . md5(basename($_FILES['file']['name']) . time());
	
	// never assume the upload succeeded
	if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
	   die(ALERT_ERROR_IMG_UPLOAD);
	}

	$info = getimagesize($_FILES['file']['tmp_name']);
	if ($info === FALSE) {
		die(ALERT_ERROR_IMG_UPLOAD);
	}

	if (($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
	   die(ALERT_ERROR_IMG_UPLOAD);
	}
		
	
	echo "<p>";

	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		$con->query("UPDATE `users` SET `ProfilePicture` = '$uploadfile' WHERE `UserID` = {$user_obj->UserID}"); 
	  echo SUCCESS_ACCOUNT_FOLLOW;
	} else {
	   die(ALERT_ERROR_IMG_UPLOAD);
	}

}

function deletePost() {
	global $con;
	$id = $con->real_escape_string($_POST['delete_post']);
	
	$con->query("DELETE FROM posts WHERE PostID = {$id}");
	header('Location: index.php');
} 

function addComment() {
	global $con;
	$meUser = $con->real_escape_string($_SESSION['username']);
	$query = $con->query("SELECT * FROM users WHERE Username = '{$meUser}' LIMIT 1");
	$meObj = $query->fetch_object();
	
	$id = $con->real_escape_string($_POST['post_id']);
	$write_comment = htmlentities($con->real_escape_string($_POST['write_comment']));
	
	if (empty(trim($write_comment))) {
		die(ALERT_INVALID_INPUT);
	}
	
	$con->query("INSERT INTO comments (CommentID, UserID, PostID, CommentDesc) VALUES (NULL, {$meObj->UserID}, $id, '$write_comment')");  
	echo SUCCESS_ACCOUNT_FOLLOW;
}


if (isset($_POST['follow'])) {
	
	doFollow($user_obj);
}

if (isset($_POST['unfollow'])) { 
	doUnFollow($user_obj);
}

if (isset($_POST['add_post'])) {
	addPost();
}

if (isset($_POST['delete_post'])) {
	deletePost();
}

if (isset($_POST['changepic'])) {
	changePic();
}

if (isset($_POST['write_comment'])) {
	addComment();
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
		  	    	   <div class="logo"> 
						<div class="logo"  style="padding-top: 30px;">
							
							<form action="index.php" method="get"><input name='u' type="text" placeholder="search username" /> <button type="submit">search</button></form>
		  	           </div>
		  	           </div>
		  	           <span class="menu">  </span> 
		  	           <div class="clear"> </div>
		  	           <div class="header-right">  
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
									
									<div class="flex-container"> 
									  <div style="padding-bottom: 20px;"><a onclick="$('#change_pic').click();" href="#"><img style="border-radius: 50%; max-width: 150px; max-height: 150px; min-height: 90px; min-width: 90px;" src="<?php echo $user_obj->ProfilePicture; ?>"/></a></div>
									  <div style="padding-bottom: 20px;"><span style="font-size: 30px; padding-right: 15px;"> @<?php echo $user_obj->Username; ?></span> <?php if (!$isMe && !$isFollowing && isset($_SESSION['username'])) {?><form action="" method="post"><button name="follow" value="1">follow</button></form><?php } else if ($isFollowing) { ?><form action="" method="post"><button name="unfollow" value="1" alt="click to unfollow">following</button></form><?php } ?></div>
									  <div style="padding-bottom: 20px; "><span style="padding-right: 20px"><b><?php echo $post_count; ?></b> posts</span><a href="#" onclick="$('#followers_link').click();"><span style="padding-left: 20px"><b><?php echo $followers_count; ?></b> followers</span></a><a href="#" onclick="$('#following_link').click();"><span style="padding-left: 20px"><b><?php echo $following_count; ?></b> following</span></a></div>
									  <div><?php echo $user_obj->ProfileDesc; ?></div>  
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
		<div id="small-dialog-following" class="mfp-hide small-dialog" style="width: 300px; min-height: 300px !important; height: 300px !important;">
				<div class="image-top" >  
						<div class="col-md-12" style="max-height: 200px; overflow-y: auto; overflow-x: hidden; ">
						
						<?php 
							//<p style="font-size: 14px;  text-align: center !important;"><span style="padding-right: 10px; "><b>{POSTER}</b></span></p>  
							$query = $con->query("SELECT * FROM user_followers WHERE FollowerID = '{$user_obj->UserID}'");
							while ($u = $query->fetch_object()) {
								$q2 = $con->query("SELECT * FROM users WHERE UserID = {$u->UserID}");
								$u2 = $q2->fetch_object();
								echo "<p style=\"font-size: 14px;  text-align: center !important;\"><span style=\"padding-right: 10px; \"><b><a href='index.php?u={$u2->Username}'>@{$u2->Username}</a></b></span></p>  ";
							}
							
						?>
						</div>
				</div>
			</div>
		<a style="display: none;" href="#small-dialog-following" class="thickbox play-icon popup-with-zoom-anim" id="following_link">cantseeme</a>
		
		<div id="small-dialog-followers" class="mfp-hide small-dialog" style="width: 300px; min-height: 300px !important; height: 300px !important;">
				<div class="image-top" >  
						<div class="col-md-12" style="max-height: 200px; overflow-y: auto; overflow-x: hidden; ">
						
						<?php 
							//<p style="font-size: 14px;  text-align: center !important;"><span style="padding-right: 10px; "><b>{POSTER}</b></span></p>  
							$query = $con->query("SELECT * FROM user_followers WHERE UserID = '{$user_obj->UserID}'");
							while ($u = $query->fetch_object()) {
								$q2 = $con->query("SELECT * FROM users WHERE UserID = {$u->FollowerID}");
								$u2 = $q2->fetch_object();
								echo "<p style=\"font-size: 14px;  text-align: center !important;\"><span style=\"padding-right: 10px; \"><b><a href='index.php?u={$u2->Username}'>@{$u2->Username}</a></b></span></p>  ";
							}
							
						?>
						</div>
				</div>
			</div>
		<a style="display: none;" href="#small-dialog-followers" class="thickbox play-icon popup-with-zoom-anim" id="followers_link">cantseeme</a>
		
		
		<div id="small-dialog-add" class="mfp-hide small-dialog" style="width: 300px; min-height: 300px !important; height: 300px !important;">
				<div class="image-top" >  
						<div class="col-md-12" style="max-height: 250px; overflow-y: auto; overflow-x: hidden; ">
							<form  enctype="multipart/form-data" action="" method="post">
								<div class="input-group">
									<label for="file">Image</label>
									<input class="form-control"  type="file" name="file">
								</div>
								<br />
								<div class="input-group">
									<label for="file">Text</label> <br />
									<textarea  class="form-control" name="post_text" style="width: 250px !important"></textarea>
								</div>
								<br />
								<div class="input-group">
									<button class="form-control" name="add_post" value="1">Post</button>
								</div>
							</form>
						</div>
				</div>
		</div>
		
		<div id="small-dialog-changepic" class="mfp-hide small-dialog" style="width: 300px; min-height: 300px !important; height: 300px !important;">
				<div class="image-top" >  
						<div class="col-md-12" style="max-height: 250px; overflow-y: auto; overflow-x: hidden; ">
						
						<h3>Profile picture</h3>
							<form  enctype="multipart/form-data" action="" method="post">
								<div class="input-group">
									<label for="file">Image</label>
									<input class="form-control"  type="file" name="file">
								</div> 
								<br />
								<div class="input-group">
									<button class="form-control" name="changepic" value="1">Change</button>
								</div>
							</form>
						
						</div>
				</div>
		</div>
		<?php if ($isMe) { ?><a style="display: none;" href="#small-dialog-changepic" class="thickbox play-icon popup-with-zoom-anim" id="change_pic">cantseeme</a><?php } ?>
		
		<?php 
			if ($isMe) {
		?>
			<a style="text-align: center !important; display:block;" href="#small-dialog-add" class="thickbox play-icon popup-with-zoom-anim" id="followers_link"><img width="10%"src="images/addbutton.png"></a> <br />
		<?php } else { ?>
			
		<?php } ?>
		<?php
			if (!isset($_GET['u'])) { 
				displayPosts($user_obj, true);
			} else {
				if ($settings_obj->TimelinePrivacy == 0) {
					$query = $con->query("SELECT * FROM users WHERE Username = '" . $con->real_escape_string(isset($_SESSION['username']) ? $_SESSION['username']: "") . "' LIMIT 1");
					if ($query->num_rows <= 0) {
						echo INFO_ACCOUNT_PRIVATE;
					} else {
						$meObj = $query->fetch_object();
						$query = $con->query("SELECT * FROM user_followers WHERE   FollowerID = {$meObj->UserID}"); 
						if ($query->num_rows <= 0) {
							echo INFO_ACCOUNT_PRIVATE;
						} else {
							displayPosts($user_obj, false);
						}
					}
					
				} else { 
					displayPosts($user_obj);
				}
			}
			
			if (!isset($_SESSION['username'])) {
				echo "<script>$('#comment_form').hide();</script>";
			}
		?>
		
		<!--
		
		-->
	</div>
</div>
 
 <!--footer start here-->
 <div class="footer">
 
 </div>
	</body>
</html>