<?php 
/* file: session.php
	for: starting session, checking session and checking cookies
*/
session_name("OVCLASSION");
session_regenerate_id();
@session_start();
include_once "conn.php";
include_once "time2duration.php";
		
function saveUserToSession($ur){
	
	$sql = "SELECT * FROM userinfo WHERE userid='$ur'";
	$found = mysql_query($sql) or die("Could not retrieve userdata. ".mysql_error());
	
	/* get the userinfo from the database and create session */
	while($userinfo = mysql_fetch_array($found)){
		$_SESSION["online"] = "online";
		$_SESSION["userid"] = $userinfo["userid"];
		$_SESSION["username"] = $userinfo["username"];
		$_SESSION["email"] = $userinfo["email"];
		$_SESSION["fname"] = $userinfo["firstname"];
		$_SESSION["lname"] = $userinfo["lastname"];
		$_SESSION["bday"] = $userinfo["birthday"];
		$_SESSION["gender"] = $userinfo["gender"];
		$_SESSION["classid"] = $userinfo["classid"];
		$_SESSION["token"] = $userinfo["token"];
	}
	return true; 
}

function checkCookie($ur, $tk){
		
		$sql = "SELECT * FROM sessions WHERE token='$tk' AND userid='$ur'";
		$found = mysql_query($sql) or die("Could not verify cookies. ".mysql_error());
		
		if(mysql_num_rows($found) > 0)
			return saveUserToSession($ur);
		else 
			return false;
	}

function isOnline(){
	
		if(!empty($_SESSION['online'])){
			if($_SESSION['online'] == 'online')
				return true;
		}
		elseif(!empty($_COOKIE['ovUser']) && !empty($_COOKIE['ovToken'])){
				return checkCookie($_COOKIE['ovUser'], $_COOKIE['ovToken']);
			}
		return false;
}

?>