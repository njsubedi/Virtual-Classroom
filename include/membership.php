<?php 
/********************************************** request to join **************************/
function getAdmId($cls)
{
	$a = mysql_fetch_array(mysql_query("SELECT adminid FROM classrooms WHERE classid='$cls';"));
	return $a[0];
}

function requestJoin($userid ,$classid){
	
	if(!mysql_num_rows(mysql_query("SELECT classid FROM classrooms WHERE classid='$classid';")))
		return false;
		
	if(!mysql_num_rows(mysql_query("SELECT * FROM membership WHERE classid='$classid' AND userid='$userid';")))
	{
		$sql = "INSERT INTO membership(userid, classid, adminid, type) VALUES('$userid', '$classid', '".getAdmId($classid)."' , '0')";
		$sent = mysql_query($sql) or die("Cannot process request to join: ".mysql_error());
	}
	
	echo '<div class="infobox">You have requested to join the classroom.</div>';
	return true;
}

/********************************************** cancel join request **************************/
function cancelJoin($userid, $classid){
	$sql = "DELETE FROM membership WHERE userid = '$userid' AND classid='$classid';";
	$sent = mysql_query($sql) or die("Cannot cancel join request: ".mysql_error());
	echo '<div class="infobox">You have cancelled the request to join the classroom.</div>';
	return true;
}

/********************************************** reject join request **************************/
function rejectUser($userid, $class){
	if($class['adminid'] == $userid)
		return false;
	$sql = "DELETE FROM membership WHERE adminid = '".$_SESSION['userid']."' AND userid = '$userid' AND classid='".$class['classid']."';";
	$sent = mysql_query($sql) or die("Cannot reject join request: ".mysql_error());
	return true;
}

/********************************************** accept join reqeust ************************/
function getNameFromIdForNotif($id)
{
	$usr = mysql_fetch_array(mysql_query("SELECT firstname, lastname FROM userinfo WHERE userid = '$id' ;"));
	return $usr[0].$usr[1];
}
function getTitleFromIdForNotif($id)
{
	$class = mysql_fetch_array(mysql_query("SELECT title FROM classrooms WHERE classid = '$id' ;"));
	return $class[0];
}

function acceptUser($userid, $classid){
	$username = "<a href=\"profile.php?go=view&id=$userid\">".getNameFromIdForNotif($userid)."</a>";
	$classname = "<a href=\"classroom.php/go=view&id=$classid\">".getTitleFromIdForNotif($classid)."</a>";
	
	global $time; 
	
	$sql = "UPDATE membership SET type='1' , adminid='".$_SESSION['userid']."' WHERE adminid = '".$_SESSION['userid']."' AND userid='$userid' AND classid='$classid' ;  ";
	mysql_query($sql) or die ("Cannot process acceptance: ".mysql_error());
	
	$sql = " INSERT INTO notifications(userid, classid, content, regtime) VALUES('$userid','$classid',";
	$content = "<b>$username</b> was was enrolled to the classroom <b> $classname </b>";

	$sql .= " '$content', '$time'); ";
	
	mysql_query($sql) or die ("Cannot process notification: ".mysql_error());
	return true;
}

/********************************************** delete user ************************/
function removeUser($userid, $classid)
{
	$sql = "DELETE FROM membership WHERE adminid = '".$_SESSION['userid']."' AND userid = '$userid' AND userid !='".$_SESSION['userid']."'AND classid='$classid';";
	$sent = mysql_query($sql) or die("Cannot reject join request: ".mysql_error());
	
	echo '<div class="createTitle">The user has been removed.</div>';
	return true;
}
?>