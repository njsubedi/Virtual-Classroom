 <?php
include_once "include/session.php";

if(!isOnline() ){
	header("Location: login.php?redir=profile.php");
	exit();
}
/* has the user sent the token? */
		if(@$_REQUEST['regToken'] == $_SESSION['token'])
		{
				include_once "include/conn.php";
				$query = "UPDATE userinfo SET token='0' WHERE userid='".$_SESSION['userid']."';";
				$changed = mysql_query($query) or die("Cannot verify user: ".mysql_error());
				
				$_SESSION["token"] = '0';
				
			/*	$query = "SELECT token FROM userinfo WHERE userid='".$_SESSION['userid']."';";
				$found = mysql_query($query) or die("cannt check");
				
				$found = mysql_fetch_array($found);
					die($found[0]);
			*/	
				header("Location: profile.php");
		}
?>