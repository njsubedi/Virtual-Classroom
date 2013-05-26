<?php
include_once "include/session.php";
if(!isOnline())
	header("Location: login.php");

function getPollStatus($pollid, $userid)
{
	$res = mysql_query("SELECT * FROM ovvotes WHERE userid = '$userid' AND pollid='$pollid' ;") or die(" cant get pollinfo");
	if(mysql_num_rows($res))
		return true;
	else
		return false;
}


function vote($pollid, $option)
{
	$alreadyVoted = getPollStatus($pollid, $_SESSION['userid']);
	if($alreadyVoted)
	{
		return false;
	}
	
	else{
		if($option != '1' && $option != '2' && $option != '3' && $option != '4')
		{
			return false;
		}
		
	mysql_query("INSERT INTO ovvotes VALUES('$pollid', '".$_SESSION['userid']."', '$option');") or die("Couldnt vote because: ".mysql_error());
	return true;
	}
}

if(vote ($_POST['target'], $_POST['option']))
	header ("Location: classroom.php?go=acts&view=poll&pollid=".$_POST['target']);

else
	header("Location: classroom.php?go=acts&view=poll");


?>