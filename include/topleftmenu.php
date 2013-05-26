<!-- menu for Classroom -->
<?php 
		$online = false;
			if(!empty($_SESSION['online']))
			$online = true;
?>
	<!-- menu for personal info -->
	<div id="ovMenuTopLeftImg">
		
		<img src="<?php echo OV_ROOT.'/userdata/thumbs/thumb_'.$_SESSION['userid']; ?>.jpg" height="48px" align="top" alt="Personal" title="Personal" />
			<!--?php 
			
				if($_SESSION['token'] != 0)
					echo "<li><a href=\"verify.php\" title=\"Verify your account\"><b>Verify Account</b></a></li>";
															
			?-->
	</div>
	
	<div id="ovMenuTopLeft">
		<ul>
			<li><a href="profile.php" title="My profile"><b><?php echo $_SESSION['username']; ?></b></a></li>
					
			<li><a href="profile.php?go=activities" title="Review your activities">Activities</a></li>
			<!--li><a href="profile.php?go=autograph" title="View your desk">Autograph</a></li-->
			<li><a href="profile.php?go=friends" title="View your classmates">Friends</a></li>
			<li><a href="classroom.php?go=list" title="Browse your classrooms">Classrooms</a></li>
			<li><a href="profile.php?go=edit" title="Edit your account information">Edit profile</a></li>
			<li><a href="logout.php?redir=<?php echo $_SERVER['PHP_SELF']; ?>" title="Log out">Logout</a></li>
		</ul>
	</div><!-- ovmenu ends -->
	
	<div id="ovMenuTopLeftSearch">
		<?php include_once "searchbox.php"; ?>
	</div>