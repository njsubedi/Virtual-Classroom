<?php
include_once "include/session.php";
include_once "include/conn.php";

$online = isOnline();

function isvalid_name($ufn, $uln)
{
	if(@eregi("^([a-z]){1,50}$", $ufn))	return @eregi("^([a-z]){1,50}$", $uln);
	return false;
}

function isvalid_email($a)
{
	return @eregi("^([a-zA-Z0-9_]+)([\.a-zA-Z0-9\_-]+)@([\.a-zA-Z0-9-]+)(\.[a-zA-Z0-9-]+)+$", $a);
}

function isavail_email($a){
	
	if(!empty($_SESSION['step1em']))
	if($a == $_SESSION['step1em'])
		return true;	
		
	$sql = "SELECT * FROM userinfo WHERE email='$a'";
	if(mysql_num_rows(mysql_query($sql)))
		return false;
	return true;
}


function isvalid_user($a){
	return @eregi("^([a-z0-9\.-\_]){5,30}", $a);
}

function isavail_user($a)
{
	if(!empty($_SESSION['step1ur']))
		if($a == $_SESSION['step1ur'])
			return true;	
	
	$sql = "SELECT * FROM userinfo WHERE username='$a'";
	
	if(mysql_num_rows(mysql_query($sql)))
		return false;
		
	return true;
}
/* *********************** REGISTER NEW USER ******************/
function register_user($sFn, $sLn, $sEm, $sPs, $sUsr, $sGn, $sBd)
{
global $resource;
	$sFn = ucfirst($sFn);
	$sLn = ucfirst($sLn);
		$sTm = time();
		$sIp = $_SERVER['REMOTE_ADDR'];
		$sTk = md5($sTm.$sIp.$sUsr);
	
	$sql = "INSERT INTO userinfo(username,email,pass,regtime,regip,firstname,lastname,gender,birthday,token, picture)";
	$sql .="VALUES('$sUsr', '$sEm', '$sPs', '$sTm', '$sIp', '$sFn', '$sLn', '$sGn', '$sBd', '$sTk', 'userdata/thumbs/user.gif'); ";
	
	mysql_query($sql) or die("Registration fails: ". mysql_error());
	$newId= mysql_query("SELECT LAST_INSERT_ID();");
	$newId = mysql_fetch_array($newId);
	$newId = $newId[0];
	
	copy('userdata/thumbs/user.jpg', 'userdata/thumbs/pic_'.$newId.'.jpg');
	copy('userdata/thumbs/thumb.jpg', 'userdata/thumbs/thumb_'.$newId.'.jpg');
	
	$file = fopen("tokens/$sUsr.txt", "w");
	fwrite($file, "Your verification code is\n\n $sTk");
	fclose($file);
	
	$_SESSION = array();
	$_SESSION['newuser'] = 'yes';
	
	header("Location: profile.php");
}

/*********************** VARIABLES TO BE USED ****************/
$errmsg =  array();
$errmsg["name"]=$errrmsg["user"]=$errmsg["email"]=$errmsg["pass"]=$errmsg["dob"]=$errmsg["gender"]=$errmsg["tos"] = "";
$errmsg['count'] = 'no';

/* first step of registration : DATA VERIFICATION */

if(@$_POST['registering'] == 1)
{
		/* get and verify user input */
		$ufn = $uln = '';
		if(isset($_POST['upNamef']) && isset($_POST['upNamel']))
		{
			$ufn = @$_POST['upNamef'];
			$uln = @$_POST['upNamel'];
				if(!isvalid_name($ufn, $uln))
				{
					$errmsg["name"] = " Enter a proper name";
					$errmsg['count'] = 'yes';
				}
												
		}else	
		{
			$errmsg["name"] = 'Fill up your full name';
			$errmsg['count'] = 'yes';
		}
		
/* validate passwords */
		$upp = '';
		if(isset($_POST['upPass1']))
		{
			$upp = $_POST['upPass1'];
			$up2 = @$_POST['upPass2'];
				if($upp != $up2){
					$errmsg["pass"] = " Passwords didn't match. Retry!";
					$errmsg['count'] = 'yes';
				}
				else if(strlen($upp) < 6)
				{		$errmsg["pass"] = " Enter a longer password";
						$errmsg['count'] = 'yes';
				}
				else
					$upp = md5($upp);
				
		}else
		{
			$errmsg["pass"] = '';
			$errmsg['count'] = 'yes';
		}
		
/* validate gender */
		$upg = '';
		if(isset($_POST['upGender']))
		{
			$upg = $_POST['upGender'];
				if($upg!=1 && $upg!=2)
				{	
					$errmsg["gender"] = " Specify your gender.";
					$errmsg['count'] = 'yes';
				}
		}else
		{
			$errmsg["gender"] = '';
			$errmsg['count'] = 'yes';
		}
		
/* check and create birthday */
		$upb = '';
		if(isset($_POST['upByear']))
		{
			$upbd = @$_POST['upBday'];
			$upbm = @$_POST['upBmonth'];
			$upby = @$_POST['upByear'];
			
			if(!@checkdate($upbm, $upbd, $upby))
			{
				$errmsg["dob"] = ' Choose a valid date. ';
				$errmsg['count'] = 'yes';
			}
			
			$upb = $upby."-".$upbm."-".$upbd;
				
		}else
		{
			$errmsg["dob"] = '';
			$errmsg['count'] = 'yes';
		}
		
/* check for acceptance of TOS */
		if(isset($_POST['upTOS']))
		{
			if(!$_POST['upTOS'])
			{
				$errmsg["tos"] = ' You must read and accept the TOS.';
				$errmsg['count'] = 'yes';
			}
		}else
		{
			$errmsg["tos"] = '';
			$errmsg['count'] = 'yes';
		}
		
/* validate email */
		$uem = '';
		if(isset($_POST['upEmail']))
		{
			$uem = $_POST['upEmail'];
			
				if(!isvalid_email($uem))
				{		$errmsg["email"] = " Enter a valid email.";
						$errmsg['count'] = 'yes';
				}
				else if(!isavail_email($uem))
				{
					$errmsg["email"] = " Enter different email.";
					$errmsg['count'] = 'yes';
				}
		}else
		{
			$errmsg["email"] = '';
			$errmsg['count'] = 'yes';
		}
		
/* validate username */
		$uur = '';
		if(isset($_POST['upUser']))
		{
			$uur = $_POST['upUser'];
				if(!isvalid_user($uur))	
				{		
					$errmsg["user"] = " Choose proper username";
					$errmsg['count'] = 'yes';
				}
				else if(!isavail_user($uur))
				{
					$errmsg["user"] = " Choose different username";
					$errmsg['count'] = 'yes';
				}
		}else
		{
			$errmsg["user"] = '';
			$errmsg['count'] = 'yes';
		}
		
/* validation ends here */
		
/* SAVE ALL THE VARIABLES INTO THE SESSION  */

		$_SESSION['step'] = array();
		
		$_SESSION['step1fn'] = $ufn;
		$_SESSION['step1ln'] = $uln;
		$_SESSION['step1em'] = $uem;
		$_SESSION['step1ps'] = $upp;
		$_SESSION['step1ur'] = $uur;
		$_SESSION['step1gn'] = $upg;
		$_SESSION['step1bd'] = $upb;
		
	/*	foreach($_SESSION['step'] as $demo)
		{
			echo $demo;
		}
		die('done');
	*/
	
	/* NOW ANALYZE THE ABOVE VARIABLES AND CREATE OUTPUTS FOR DISPLAY */
	if($errmsg['count'] == 'no')
	{
		register_user(ucfirst($ufn), ucfirst($uln), $uem, $upp, $uur, $upg, $upb);
	}
	/* if there are no errors, display the profile instead */
	else 
	{
	?>
		<html>
		  <head>
			<title>Register a new account -ovclass</title>
			<link rel="stylesheet" type="text/css" href="css/reset.css" />
			<link rel="stylesheet" type="text/css" href="css/template.css" />
			<link rel="stylesheet" type="text/css" href="css/signup.css" />
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

					<div class="ttopright">
						<?php
							if($online)
							{
								include_once "include/topleftmenu.php";
							}
							else
							{
								include_once "include/loginbox.php";
							}
						?>
					</div>
				</div><!-- ttitle bar: (common for all) ends here -->
		</div><!-- ttopbar ends here, fortunately ! :) -->

		<div class="tpagetitle">
			<div class="tpagetitleflow" style="padding: 5px;">
				Fill up the form details to create a new account.
			</div>
		</div>

		<div class="tcontainter">
			<div class="tpagecontents">
					<div class="signupBox"><!-- left floating (registration + info) box inside the page body -->
						<div class="signupTitle">
							Registration form
						</div>
						<?php
							include "include/signupbox.php";
						?>
						<div class="signupFooter">
							<?php include_once "include/searchbox.php"; ?>
						</div>
					</div>
			</div> <!-- closes tpagecontents -->
			
			<div class="bottombar">
			Virtual Classroom is an open source project. Source code available at: http://ovclass.sourceforge.net
			</div>
		</div> <!-- closes tcontainer -->
		
		</body>
		</html>

		<?php
	}/* this ends the if condition for errmsg count */
	
} /* this ends the case if post_registering is 1 */
else 
{
	header("Location: index.php");
}