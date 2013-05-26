 <?php 
	include_once "session.php";
	if(isOnline())
	{
		include_once "rightmenu.php";
	}
	else
	{
		include_once "loginbox.php";
	}
 
 
 ?>