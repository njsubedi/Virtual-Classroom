<?php
	/* file: savenewclass.php
	 for: saving new class and returning back to classroom 
	*/
include_once "session.php";
include_once "conn.php";

function showNewClassroom()
{
		echo '<div class="addNoticeTitle" onmouseover="this.style.display=\'none\';">Classroom successfully created.</div>';
		listAllClassrooms();
		return;		
}

$errmsg = "";
	if(isset($_POST['classTitle']))
	{
		$classTitle = ucwords(trim(mysql_real_escape_string(htmlentities($_POST['classTitle']))));
		$classTitleL = strlen($classTitle);	
		
		if(($classTitleL > 100) || ($classTitleL < 5))
		{
			$errmsg .= "Title must be 5 to 100 characters.<br />";
		}
	}
	else $errmsg.="You must provide a title.<br />";
	
	if(isset($_POST['classLoc']))
	{
		$classLoc = ucfirst(trim(mysql_real_escape_string(htmlentities($_POST['classLoc']))));
		$classLocL = strlen($classTitle);	
		
		if(($classLocL > 100) || ($classLocL < 5))
		{
			$errmsg .= "Address must be 5 to 100 characters.<br />";
		}
	}
	else $errmsg.="You must provide a location.<br />";
	
	
	if(isset($_POST['classDescription']))
	{
		$classDesc = trim(mysql_real_escape_string(htmlentities($_POST['classDescription'])));
		$classDescL = strlen($classDesc);
		
		if(($classDescL > 300) || ($classDescL < 5))
		{
			$errmsg .= "Description must be 5 to 300 characters.<br />";
		}
	}
	else $errmsg.="You must provide a description.<br />";
	
	
	if(isset($_POST['classId']))
	{
		$classId = trim(mysql_real_escape_string(htmlentities($_POST['classId'])));
		$classIdL = strlen($classId);
		
		if(($classIdL > 40) || ($classIdL < 5))
		{
			$errmsg .= "Nickname must be 5 to 300 characters.<br />";
		}
		else
		{		
			$chk = "SELECT classid FROM classrooms WHERE nickname='$classId';";
			$chk = mysql_query($chk);
			if(mysql_num_rows($chk))
			{
				$matchedId = mysql_fetch_array($chk);
				$errmsg .= "The unique name is already taken. ";
				$errmsg .= "<a href=\"classroom.php?go=view&id=".$matchedId[0]."\" target=\"_blank\">Visit.</a>.<br />";
			}
		}
	}
	else $errmsg.="You must create a new nickname.<br />";
	
	
	if(isset($_POST['classLevel']))
	{
		switch($_POST['classLevel']) 
		{
				case 1: $classLevel = 'Primary level'; break;
				case 2: $classLevel = 'Lower Secondary'; break;
				case 3: $classLevel = 'Secondary level'; break;
				case 4: $classLevel = 'Higher Secondary'; break;
				case 5: $classLevel = 'Undergraduate'; break;
				case 6: $classLevel = 'Graduate'; break;
				case 8: $classLevel = 'Post Graduate'; break;
				case 9: $classLevel = 'Formally enrolled'; break;
				case 11: $classLevel = 'Training'; break;
				case 12: $classLevel = 'Art or music'; break;
				case 13: $classLevel = 'Tution/coaching'; break;
				case 14: $classLevel = 'Sports or Dance'; break;
				case 19: $classLevel = 'Informally studying'; break;
				default: $classLevel = ''; break;
		}
	}
	else $errmsg.="You must specify type/level.<br />";
	
	if(isset($_POST['classPrivacy']))
	{
		switch($_POST['classPrivacy']) 
		{
				case 1: $classType = 'Private'; break;
				case 2: $classType = 'Protected'; break;
				default: $classType = 'Public';
		}
	}
	else $errmsg.="You must specify the privacy.<br />";

	/* input ends here.... */
/* now save all data into the session for future */
	/* SAVE ALL VALUES TO SESSION */
	$_SESSION['start']['newClassCreating'] = "yes";
	$_SESSION['start']['startTitle'] = @$classTitle;
	$_SESSION['start']['startDesc'] = @$classDesc;	
	$_SESSION['start']['startLoc'] = @$classLoc;	
	$_SESSION['start']['startId'] = @$classId;

	
/* if errors had occured */
	
	if($errmsg)
	{
			$_SESSION['start']['newClassError'] = $errmsg;
			unset($errmsg);
			include_once "start.php";
	}
	else
	{		
			$adminId = $_SESSION['userid'];
			$adminNm = $_SESSION['fname']." ".$_SESSION['lname'];
			global $time;
			$regtime = date("Y-m-d");
			
			$sql = "INSERT INTO classrooms(nickname, title, description, adminid, adminname, regtime, addr1, level, type, members)";
			$sql .= "VALUES('$classId', '$classTitle', '$classDesc', '$adminId', '$adminNm', '	$regtime', '$classLoc' ,'$classLevel', '$classType', '1')";
			
			mysql_query($sql) or die("Couldn't save classroom. ".mysql_error());
			
			$sql = "SELECT classid FROM classrooms ORDER BY classid DESC LIMIT 0,1;";			
			$found = mysql_fetch_array(mysql_query($sql));
			$found = $found[0];
			
			$sql = "INSERT INTO membership VALUES('$adminId', '$found', '$adminId', $time, '1')";
			mysql_query($sql) or die("Couldn't set membership to created classroom. ".mysql_error());
			
			$sql = "UPDATE userinfo SET classid = '$found' WHERE userid = '$adminId'; ";
			mysql_query($sql) or die("Couldn't set default classroom. ".mysql_error());
			
			$_SESSION['newClassSaved'] = "yes";
			$_SESSION['classid'] = $found;
			
		/* classroom created, so destroy current classroom creation session data */
		$_SESSION['start'] = array();
			
		showNewClassroom();
	}
	
?>
