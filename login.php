<?php

include_once "include/session.php";

	
function makePersist(){
	
	$nameCookie = "ovUser";
	$nameValue = $_SESSION['userid'];
	
	$validTime = time() + 604800;
	
	$tokenCookie = "ovToken";
	$tokenValue = md5($validTime."randomtext");
	
		$sql = "INSERT INTO sessions VALUES('$tokenValue', '$nameValue', $validTime)";
		mysql_query($sql) or die("Couldn't make persist: ".mysql_error());
		
		setcookie($nameCookie, $nameValue, $validTime);
		setcookie($tokenCookie, $tokenValue, $validTime);
		
		return true;
	}

function checkLogin($usr, $pas){
	$usr = @explode('"', $usr);
	$usr = $usr[0];
	$usr = mysql_real_escape_string($usr);
	
	/* look for the email/username in the database */
		$sql = "SELECT * FROM userinfo WHERE username = \"$usr\" OR email = \"$usr\";";
		$query = mysql_query($sql) or die("Server Error: ".mysql_error());
		
		/*if no such username/email exists... error! Function returns error and terminates */
		if(!mysql_num_rows($query))
			return "<strong>Error:</strong> Incorrect Username/Email.";
		
	/* The username/email is valid one; now proceed */
	else{
		/* check for password tally */
			$userinfo = mysql_fetch_array($query, MYSQL_ASSOC);
			
		/*passwords do not match, so terminate by returning error message */
			if((md5($pas) != $userinfo['pass']) && ($pas != $userinfo['verification'])){
				return "<strong>Error:</strong> Incorrect Password.";
			}
		
		/* password is correct.. now save the session for the user */
		$_SESSION["online"] = "online";
		$_SESSION["userid"] = $userinfo["userid"];
		$_SESSION["username"] = $userinfo["username"];
		$_SESSION["email"] = $userinfo["email"];
		$_SESSION["fname"] = $userinfo["firstname"];
		$_SESSION["lname"] = $userinfo["lastname"];
		$_SESSION["bday"] = $userinfo["birthday"];
		$_SESSION["gender"] = ($userinfo["gender"] == 1)? "Male" : "Female";
		$_SESSION["classid"] = $_SESSION['currentClass'] = $userinfo["classid"];
		$_SESSION["classname"] = "Favourite";
		$_SESSION["token"] = $userinfo["token"];
		$_SESSION["pass"] = $userinfo["pass"];
		$_SESSION["verification"] = $userinfo["verification"];
		$_SESSION["picture"] = 'userdata/thumbs/'.$userinfo["picture"];
			if(!$_SESSION['picture']) $_SESSION['picture'] = 'userdata/thumbs/user.gif';
		
		if(isset($_POST['remember'])){
			makePersist();
		}
		
		/* if user is logged in and session is saved, why are we here?
			let's redirect the user to his/her classroom, isn't it? */
		if(isset($_REQUEST['redir'])){
			switch($_REQUEST['redir']){
				
				case 'verify.php':
					header("Location: verify.php");
					break;
				case 'classroom.php':
					header("Location: classroom.php");
					break;
				case 'profile.php':
					header("Location: profile.php");
					break;
				default:
					break;
			}
		
		}
		header("Location: classroom.php");
	}
}

/* parsing starts here */
/* If user is already online, and username sent as cookie matches userId in cookie.. send to profile */
	if(isOnline()){
			header ("Location: profile.php");
	}

/* if user has sent username and password by post method, have a look at them */
	if(isset($_POST['username'])){
		$user = $_POST['username'];
		$pass = $_POST['password'];
		
		$_SESSION['username'] = $user;
		
	/* try to login the user.. which, on success will be redirected 
		or else the function returns an error message to be saved as
		errmsg.. */
		$_SESSION['badlogin'] = checkLogin($user, $pass);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
  <head>
	<title>Template Page for ovclass</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/template.css" />
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<link rel="stylesheet" type="text/css" href="css/classes.css" />
	<link rel="stylesheet" type="text/css" href="css/search.css" />
  </head>
<body>

<div class="ttopbar">
	<div class="ttitlebar">
			<div class="ttopleft">
				<a href="index.php" alt="OVCLASS" title="OVCLASS"><img src="imgs/ovclasslogodark.png" width="150px" height="55px"/></a>
			</div>
			
		</div><!-- ttitle bar: (common for all) ends here -->
</div><!-- ttopbar ends here, fortunately ! :) -->

<div class="tpagetitle">
	<div class="tpagetitleflow" style="padding:5px">
		<?php if(!empty($_SESSION['newuser']))
			echo '<h2>Hi! Welcome To OVclass. Please login to continue.</h2>';
		else
			echo 'Login to ovclass with your username / email and password.';
		?>
	</div>
</div>

<div class="tcontainer">
	<div class="tpagecontents">
		<div class="loginBoxBig">
			<?php
				 include "include/loginboxbig.php" ;
			 ?>
			<div class="loginFooter">
				<?php include "include/searchbox.php"; ?>
			</div>
		</div>
		
	</div>	
	
	<div class="bottombar">
	Virtual Classroom is an open source project. Source code available at: http://ovclass.sourceforge.net
	</div>
	
</div><!-- div closes here : </container > -->
</body>
</html>
