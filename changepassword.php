<?php
	include_once "include/session.php";
	if(!isOnline())
		header("Location: index.php");
	
	/* validate passwords */
		$upp = '';
		if(isset($_POST['oldPass']) && ((@$_SESSION['pass'] == md5($_POST['oldPass'])) || @$_SESSION['verification'] == $_POST['oldPass']))
		{
			$upp = @$_POST['upPass1'];
			$up2 = @$_POST['upPass2'];
			
				if($upp != $up2)
				{
					$_SESSION['editProfileErr'] = " New Passwords didn't match. Retry!";
					header("Location: profile.php?go=edit");
				}
				else if(strlen($upp) < 6)
				{		$_SESSION['editProfileErr'] = "Your password must be at least 6 characters long.";
						header("Location: profile.php?go=edit");
				}
				
				else
					$upp = md5($upp);
				
		}
		else
		{
			$_SESSION['editProfileErr'] = 'You must provide your correct current password.';
			header("Location: profile.php?go=edit");
		}
	
	include_once "include/conn.php";
	
	if(empty($_SESSION['editProfileErr']))
	{
	mysql_query("UPDATE userinfo SET pass = '$upp' WHERE userid='".$_SESSION['userid']."';") or die("Could not change password because.. ".mysql_error());
	$_SESSION['editProfileErr'] = "<b>Your password has been changed.</b>";
	$_SESSION['pass'] = $upp;
	}
	
	header("Location: profile.php?go=edit");
?>