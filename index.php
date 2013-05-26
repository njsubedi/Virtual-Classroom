<?php
    require_once "install.php";
	include_once "include/session.php";
	
	
	if(isOnline())
		header("Location: classroom.php");
	
	if(!isset($_SESSION['step']))
		$_SESSION['step'] = 0;
		
	else
	if($_SESSION['step'] > 0)	{
			$errmsg = true;
		}
		else $errmsg = false;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Welcome to Online Virtual Classroom. Connect, share and interact with your classmates.</title>
	<link rel="icon" href="imgs/favicon.ico" type="image/x-icon" /> 
    <link rel="shortcut icon" href="imgs/favicon.ico" type="image/x-icon" /> 
	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/index.css"/>
	<link rel="stylesheet" type="text/css" href="css/template.css"/>
	<link rel="stylesheet" type="text/css" href="css/login.css"/>
	<link rel="stylesheet" type="text/css" href="css/signup.css"/>
	<link rel="stylesheet" type="text/css" href="css/classes.css"/>
	<link rel="stylesheet" type="text/css" href="css/search.css"/>
	
	<script type="text/javascript" src="js/index.js"></script>
</head>

<body>

<div class="indexttopbar">
	<div class="indexttitlebar">
			<div class="indexttopleft">
				<a href="classroom.php" alt="OVCLASS" title="OVCLASS"><img src="imgs/indexlogo.png" /></a>
			</div>

			<div class="indexttopright">
				Find your friends <br />
				<?php include_once "include/searchbox.php"; ?>
			</div>
		</div><!-- ttitle bar: (common for all) ends here -->
</div><!-- ttopbar ends here, fortunately ! :) -->

<div class="indextcontainer">
	<div class="indextpagecontents">
		
			
		<!-- GRAY BAR FOR THE INTRO PAGES THROUGHOUT THE SITE -->
		<div class="indexLoginbar">
			
			<?php include "include/loginbox.php"; ?>
		
			<div class="indexSignupTitle">
				Create a new account
			</div>
		
		</div>
		
		<!-- PAGE SPECIFIC LEFT COLUMN FOR INTRO PAGES THROUGHTOUT THE SITE -->
		<div class="indexLeftColumn">
			
			<div class="indexThemeImagebox">
				
			</div>
		
		</div>
		 
	<!-- PAGE SPECIFIC RIGHT COLUMN FOR INTRO PAGES THROUGHOUT THE SITE -->
		<div class="indexRightColumn">
		
			<div class="indexSignupBox"><!-- left floating (registration + info) box inside the page body -->
				
				<?php	include "include/signupbox.php";	?>
			</div>
			
		</div>
		
	</div><!--page body ends here -->

<!-- COMMON FOOTER FOR INTRO PAGES THROUGHOUT THE SITE --> 
	<div class="bottombar">
		 Virtual Classroom is an open source project. Source code available at: http://ovclass.sourceforge.net 
		 <a href="login.php"> Login page </a>
	</div>
</div><!-- container ends here -->

<!--END OF BODY -->
</body>
</html>
