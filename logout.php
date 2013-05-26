<?php
include_once "include/session.php";

if(!isOnline()){
	header("Location: login.php");
}
	$tm = time() - 3600;
	
	$_SESSION = array();
	session_destroy();
	setcookie("ovUser", "", $tm);
	setcookie("ovToken", "", $tm);
	setcookie("PHPSESSID", "", $tm);
	
	$addr = 'index.php';
	
	if(isset($_GET['redir'])){
		$targetmatch = end(explode("/",$_REQUEST['redir']));
		switch($_GET['redir']){
		
			case 'classroom.php':
				$addr = 'classroom.php';
				break;
			
			case 'profile.php':
				$addr = 'profile.php';
				break;
	}
	header("Location: ".$addr);
		
	}
	else
		header("Location: index.php");
	exit();
?>