<?php
require_once "session.php";
require_once "conn.php";

/* 
	my globals:
	*/
$userIsAdmin = false;
	
/*
	my functions:
*/

{/************************************************************************************* NOTICE BLOCK STARTS HERE ************/

function addNotice($array)
{
	$usetable = 'notices';
	$time = time();
	
	$sql = "INSERT INTO $usetable(classid, topic, description,  regtime,  validity, urgency)";
	$sql .= "VALUES('".$array['classid']."' . '".$array['topic']."' . '".$array['desc']."' . '".$time."' . '".$array['validity']."' . '".$array['urgency']."';";
	
	$sent = mysql_query($sql);
	return mysql_insert_id($sent);
}

function showAllNotices($id)
{	

	echo '<div class="fieldHeading">';
		echo '<a href="classroom.php?go=acts&view=notice">Recent Notices</a>';
	echo '</div>';
	
global $userIsAdmin;
	$usetable = 'notices';
	
		$sql = "SELECT * FROM $usetable WHERE classid = '$id' ORDER BY id DESC;";
		$result = mysql_query($sql);
		
		if(!mysql_num_rows($result))
		{
			echo '<div class="errbox"> There are no notices to display.</div>';
		}
			while($resset = mysql_fetch_assoc($result))
			{
				echo '<div class="class_notice2">';
				
				$noticeTitle = '<a href="classroom.php?go=acts&view=notice&notice='.$resset['id'].'" title="View Details">';
				$noticeTitle .= htmlspecialchars($resset['topic']).'</a>';
				
				echo '<div class="noticeHeading2">'.$noticeTitle.'</div>';
				if($userIsAdmin){
						echo '<div style="float:right;">';
						echo '<a style=" font-weight:bold; color: #FF0000; " ';
						echo 'onclick = "return confirm(\'Are you sure?\')" ';
						echo ' href="classroom.php?go=acts&view=notice&delete='.$resset['id'].'"> X </a>';
						echo '</div>';
					}
					echo '<div class="noticeSuppinfo2">';
					switch($resset['urgency'])
					{
						case 1:
							echo '<div class="urgencya">General Notice</div>';
							break;
						case 2:
							echo '<div class="urgencyb">Important Notice</div>';
							break;
						case 3:
							echo '<div class="urgencyc">Urgent Notice</div>';
							break;
					}
					echo '<div class="noticeDatetime">Published '.time_since($resset['regtime']).'</div>';
					echo '</div>'; // subinfo
					
				echo '</div>'; // heading
			}
}

function showOneNotice($id)
{
	
global $userIsAdmin;
	$usetable  =  'notices';
		$sql = "SELECT * FROM $usetable WHERE id = '$id' ORDER BY id DESC;";
		$result = mysql_query($sql);
		while($resset = mysql_fetch_assoc($result))
		{
			echo '<div class="class_notice">';
				echo '<div class="noticeHeading">'.htmlspecialchars($resset['topic'], ENT_NOQUOTES).'</div>';
				if($userIsAdmin){
						echo '<div style="float:right;">';
						echo '<a style=" font-weight:bold; color: #FF0000; " ';
						echo 'onclick = "return confirm(\'Do you really want to delete the notice?\')" ';
						echo ' href="classroom.php?go=acts&view=notice&delete='.$resset['id'].'"> X </a>';
						echo '</div>';
					}
				echo '<div class="noticeSuppinfo">';
					switch($resset['urgency'])
					{
						case 1:
							echo '<div class="urgencya">General Notice</div>';
							break;
						case 2:
							echo '<div class="urgencyb">Important Notice</div>';
							break;
						case 3:
							echo '<div class="urgencyc">Urgent Notice</div>';
							break;
					}
					echo '<div class="noticeDatetime"> Published '.time_since($resset['regtime']).'</div>';
				echo '</div>';
				echo '<div class="noticeContent">'.nl2br(htmlspecialchars($resset['description'], ENT_NOQUOTES)).'</div>';
			echo '</div>';
		}
}

function showNoticeForm($userRequest)
{
$values = false;
if($userRequest)
{
	if(isset($_SESSION['noticeerr']))
	{
		echo $_SESSION['noticeerr'];
		unset($_SESSION['noticeerr']);
	}
	if(isset($_POST['addnotice']))
	{
		$_SESSION['start']['noticeTitle'] = @$_POST['noticeTabe'];
		$_SESSION['start']['noticeContent'] = @$_POST['noticeContent'];
		$values = true;
		
		$title = mysql_real_escape_string(@$_POST['noticeTitle']);
		$content = mysql_real_escape_string(@$_POST['noticeContent']);
		$urgency = @$_POST['noticeUrgency'];
		
		$noticeErr = '';
		if(!$title){
			$noticeErr .= "You must enter topic for your notice.<br />";
		}else if(strlen($title) > 200)
		{
			$noticeErr .= "The Title is too long (longer than 200 letters)<br />";
		}
		
		if(!$content){
			$noticeErr .= "You must provide content of your notice.<br />";
		}else if(strlen($title) > 5000)
		{
			$noticeErr = "The Notice is too long (longer than 5000 letters)<br />";
		}
		
		if($urgency != 2 && $urgency != 3){
			$urgency = 1;
		}
		if(!$noticeErr)
		{
			global $time;
			$class = $_SESSION['currentClass'];
			
			$sql = 'INSERT INTO notices(classid, topic, description, regtime, validity, urgency) VALUES';
			$sql .= "('$class', '$title', '$content', '$time', '1', '$urgency'); ";
			mysql_query($sql) or die("Cannot add notice because: ".mysql_error());
			
			$_SESSION['start'] = array();
			showNoticeForm(false);
			return true;
		}
		else{
			echo '<div class="errbox">';
			echo $noticeErr;
			echo '</div>';
			
		showNoticeForm(false);	/* false is for recursive calls,, ie request not made by user.. */
		return false;
		}
	
	return true;
	}else $values = false;
}

?>
<div>
	<div class="addHeading" onClick="showNewNoticeForm()"><img src="imgs/class_plus.gif"> Add a new notice</div>
</div>
<div id="newNoticeBox" style="display:block;">
							<script type="text/javascript">
								function hideNewNoticeForm(){document.getElementById('newNoticeBox').style.display ='none';}
								function showNewNoticeForm(){document.getElementById('newNoticeBox').style.display='block';}
								hideNewNoticeForm();
							</script>
<form method="post" action="classroom.php?go=acts&view=notice">
	<input type="hidden" name="addnotice" value="yes" />
	
	<table cellspacing="0" cellpadding="0" border="0">
		 <tr>
			<td class="createRows">
				<b>Title</b><br />
					<input class="createInputLong" onBlur="checkClassId(this.value)" type="text" name="noticeTitle" value="<?php if($values) echo $_SESSION['start']['noticeTitle']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="createRows">
			<b>Content</b><br />
					<textarea wrap="physical" class="textareaHolder" name="noticeContent" id="ovTr" rows="3" cols="55" onkeyup="sizeBox(this)"><?php
						 if($values) echo $_SESSION['start']['noticeContent']; 
					?></textarea>
			</td>
		</tr>
		<tr>
			<td class="createRows">Is the notice important?<br />
				<select name="noticeUrgency">
                  	<option selected="selected" value='1'>No, it is a general notice.</option>
                    <option value="2">
						Yes, it is an important notice.
                    </option>
                    <option value="3">
						It is an urgent notice indeed.
                    </option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" class="ovButton" value="Save" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="ovButton2" value="Cancel" onclick="hideNewNoticeForm()" />
			</td>
		</tr><tr><td><br /></td></tr>
	</table>
</form>
</div>
<?php
}

}
/************************************************************************************** NOTICE BLOCK ENDS HERE   ************/

{/************************************************************************************* NOTICE BLOCK STARTS HERE ************/

function addevent($array)
{
	$usetable = 'events';
	
	$sql = "INSERT INTO $usetable(classid, topic, description,  regtime)";
	$sql .= "VALUES('".$array['classid']."' . '".$array['topic']."' . '".$array['desc']."' . '".$array['time']."';";
	
	$sent = mysql_query($sql);
	return mysql_insert_id($sent);
}

function showAllevents($id)
{	

	echo '<div class="fieldHeading">';
		echo '<a href="classroom.php?go=acts&view=event">Recent events</a>';
	echo '</div>';
	
global $userIsAdmin;
	$usetable = 'events';
	
		$sql = "SELECT * FROM $usetable WHERE classid = '$id' ORDER BY id DESC;";
		$result = mysql_query($sql);
		if(!mysql_num_rows($result))
		{
			echo '<div class="errbox"> There are no upcoming events.</div>';
		}
			while($resset = mysql_fetch_assoc($result))
			{
				echo '<div class="class_notice2">';
				
				$noticeTitle = '<a href="classroom.php?go=acts&view=event&event='.$resset['id'].'" title="View Details">';
				$noticeTitle .= htmlspecialchars($resset['topic']).'</a>';
				
				echo '<div class="noticeHeading2">'.$noticeTitle.'</div>';
				if($userIsAdmin){
						echo '<div style="float:right;">';
						echo '<a style=" font-weight:bold; color: #FF0000; " ';
						echo 'onclick = "return confirm(\'Are you sure?\')" ';
						echo ' href="classroom.php?go=acts&view=event&delete='.$resset['id'].'"> X </a>';
						echo '</div>';
					}
					echo '<div class="noticeSuppinfo2">';
					echo '<div class="noticeDatetime">Happening '.$resset['regtime'].'</div>';
					echo '</div>'; // subinfo
					
				echo '</div>'; // heading
			}
			
}

function showOneevent($id)
{
	
global $userIsAdmin;
	$usetable  =  'events';
		$sql = "SELECT * FROM $usetable WHERE id = '$id' ORDER BY id DESC;";
		$result = mysql_query($sql);
		while($resset = mysql_fetch_assoc($result))
		{
			echo '<div class="class_notice">';
				echo '<div class="noticeHeading">'.htmlspecialchars($resset['topic'], ENT_NOQUOTES).'</div>';
				if($userIsAdmin){
						echo '<div style="float:right;">';
						echo '<a style=" font-weight:bold; color: #FF0000; " ';
						echo 'onclick = "return confirm(\'Do you really want to remove the event?\')" ';
						echo ' href="classroom.php?go=acts&view=event&delete='.$resset['id'].'"> X </a>';
						echo '</div>';
					}
				echo '<div class="noticeSuppinfo">';
					echo '<div class="noticeDatetime"> Published '.$resset['regtime'].'</div>';
				echo '</div>';
				echo '<div class="noticeContent">'.nl2br(htmlspecialchars($resset['description'], ENT_NOQUOTES)).'</div>';
			echo '</div>';
		}
}

function showeventForm($userRequest)
{
$values = false;
if($userRequest)
{
	if(isset($_SESSION['eventerr']))
	{
		echo $_SESSION['eventerr'];
		unset($_SESSION['eventerr']);
	}
	if(isset($_POST['addevent']))
	{
		$_SESSION['start']['eventTitle'] = @$_POST['eventTabe'];
		$_SESSION['start']['eventContent'] = @$_POST['eventContent'];
		$values = true;
		
		$title = mysql_real_escape_string(@$_POST['eventTitle']);
		$content = mysql_real_escape_string(@$_POST['eventContent']);
		$urgency = @$_POST['eventUrgency'];
		
		$eventErr = '';
		if(!$title){
			$eventErr .= "You must enter topic for your event.<br />";
		}else if(strlen($title) > 200)
		{
			$eventErr .= "The Title is too long (longer than 200 letters)<br />";
		}
		
		if(!$content){
			$eventErr .= "You must provide content of your event.<br />";
		}else if(strlen($title) > 5000)
		{
			$eventErr .= "The event is too long (longer than 5000 letters)<br />";
		}
		
		$upb = '';
		if(isset($_POST['eventYear']))
		{
			$upbd = @$_POST['eventDay'];
			$upbm = @$_POST['eventMonth'];
			$upby = @$_POST['eventYear'];
			
			
			$todayYr = date("Y");
			$todayMm = date("m");
			$todayDd = date("d");
			$valid = true;
			
			if(!@checkdate($upbm, $upbd, $upby))
			{
				$eventErr .= 'Choose a valid date';
			}
			
			else if($upby >= $todayYr)
			{
				if($upbm >= $todayMm)
				{
					if($upbd >= $todayDd)
						$valid = true;
					else 
						$valid = false;
				}
				else
					$valid = false;
			}
			else
				$valid = false;
			
			//if($valid === false)
				//$eventErr .= 'Choose a future date';
			
			switch($upbm){
				case 1: $mmm = 'Jan'; break;
				case 2: $mmm = 'Feb'; break;
				case 3: $mmm = 'Mar'; break;
				case 4: $mmm = 'Apr'; break;
				case 5: $mmm = 'May'; break;
				case 6: $mmm = 'Jun'; break;
				case 7: $mmm = 'Jul'; break;
				case 8: $mmm = 'Aug'; break;
				case 9: $mmm = 'Sep'; break;
				case 10: $mmm = 'Oct'; break;
				case 11: $mmm = 'Nov'; break;
				case 12: $mmm = 'Dec'; break;
			}
				
		}else
		{
			$eventErr .= "Please specify date of the event.<br />";
		}
		
		if(!$eventErr)
		{
			$datestring = $upbd.' '.$mmm.' '.$upby;
			$class = $_SESSION['currentClass'];
			
			$sql = 'INSERT INTO events(classid, topic, description, regtime) VALUES';
			$sql .= "('$class', '$title', '$content', '$datestring'); ";
			mysql_query($sql) or die("Cannot add event because: ".mysql_error());
			
			$_SESSION['start'] = array();
			showeventForm(false);
			return true;
		}
		else{
			echo '<div class="errbox">';
			echo $eventErr;
			echo '</div>';
			
		showeventForm(false);	/* false is for recursive calls,, ie request not made by user.. */
		return false;
		}
	
	return true;
	}else $values = false;
}

?>
<div>
	<div class="addHeading" onClick="showNeweventForm()"><img src="imgs/class_plus.gif"> Add a new event</div>
</div>
<div id="neweventBox" style="display:block;">
							<script type="text/javascript">
								function hideNeweventForm(){document.getElementById('neweventBox').style.display ='none';}
								function showNeweventForm(){document.getElementById('neweventBox').style.display='block';}
								hideNeweventForm();
							</script>
<form method="post" action="classroom.php?go=acts&view=event">
	<input type="hidden" name="addevent" value="yes" />
	
	<table cellspacing="0" cellpadding="0" border="0">
		 <tr>
			<td class="createRows">
				<b>Title</b><br />
					<input class="createInputLong" onBlur="checkClassId(this.value)" type="text" name="eventTitle" value="<?php if($values) echo $_SESSION['start']['eventTitle']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="createRows">
			<b>Content</b><br />
					<textarea wrap="physical" class="textareaHolder" name="eventContent" id="ovTr" rows="3" cols="55" onkeyup="sizeBox(this)"><?php
						 if($values) echo $_SESSION['start']['eventContent']; 
					?></textarea>
			</td>
		</tr>
		<tr>
			<td class="createRows">Date:<br />
				<table>
					<tr style="font-size: 9pt;"><td>day</td><td>month</td><td>year</td></tr>
					<tr>
						<td><input type="text" name="eventDay" size="2" value="01" /> - </td>
						<td><input type="text" name="eventMonth" size="2" value="01" /> - </td>
						<td><input type="text" name="eventYear" size="2" value="2012" /></td>
				</table>
				<br />
				<input type="submit" class="ovButton" value="Save" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="ovButton2" value="Cancel" onclick="hideNeweventForm()" />
			</td>
		</tr>
		<tr><td><br /></td></tr>
	</table>
</form>
</div>
<?php
}

}
/************************************************************************************** event BLOCK ENDS HERE   ************/


{/************************************************************************************* ASSIGNMENT BLOCK STARTS HERE ************/

function addAssignment($array)
{
	$usetable = 'assignments';
	$time = time();
	
	$sql = "INSERT INTO $usetable(classid, topic, description,  regtime,  duedate, type)";
	$sql .= "VALUES('".$array['classid']."' . '".$array['topic']."' . '".$array['desc']."' . '".$time."' . '".$array['duedate']."' . '".$array['type']."';";
	
	$sent = mysql_query();
	return mysql_insert_id($sent);
}

function getAssignmentStatus($id)
{
	$res = mysql_query("SELECT * FROM assignmentinfo WHERE assignment = '$id' AND user='".$_SESSION["userid"]."'") or die(" cant get assignmentinfo");
	if(mysql_num_rows($res))
	{
		$assignmentSet = mysql_fetch_assoc($res);
			return $assignmentSet['contents'];
	}	
	
	else
		return false;
}

function saveTextAssignment($text, $target){

	if(!$text)
		return false;
	
	if(strlen($text) < 5000)
	{
		$filename = "userdata/assignments/text_".$_SESSION['userid'].time().".txt";	
		
		$fhandle = fopen($filename, "w+");
		fwrite($fhandle, $text);
		fclose($fhandle);
		$times = time();
		mysql_query("INSERT INTO assignmentinfo(assignment, user, type, contents, regtime) VALUES('$target', '".$_SESSION['userid']."', '2', '$filename', '$times' ) ")
				or die ("Cannot submit text assignment. ".mysql_error());
		return true;
	}
	else return false;
}
function saveDocumentAssignment($doc, $target){
	if(!$doc)
		return false;
	
	$ext = @$doc['name'];
	$ext = @end(explode(".", $ext));
	
	$valid = array('jpg', 'png', 'gif', 'bmp', 'doc', 'pdf', 'txt', 'c', 'cpp');
	if(!in_array($ext, $valid))
		return false;
	
	if($doc['size'] > 512000)
		return false;
	
	$targetPath = "userdata/assignments/".$target.time().'_'.$doc['name'];
	$moved = move_uploaded_file($doc['tmp_name'], $targetPath);
	if(!$moved)
		return false;
	
	$times = time();
	mysql_query("INSERT INTO assignmentinfo(assignment, user, type, contents, regtime) VALUES('$target', '".$_SESSION['userid']."', '2', '$targetPath', '$times' ) ")
				or die ("Cannot submit document assignment. ".mysql_error());
}

function showAllAssignments($id)
{	
	echo '<div class="fieldHeading">';
		echo '<a href="classroom.php?go=acts&view=assignment">Recent Assignments</a>';
	echo '</div>';

	global $userIsAdmin;
	$usetable = 'assignments';
	
		$sql = "SELECT * FROM $usetable WHERE classid = '$id' ORDER BY id DESC;";
		$result = mysql_query($sql);
		
			while($resset = mysql_fetch_assoc($result))
			{
				echo '<div class="class_notice2">';
				
				$assignmentTitle = '<a href="classroom.php?go=acts&view=assignment&assignment='.$resset['id'].'" title="View Details">';
				$assignmentTitle .= htmlspecialchars($resset['topic']).'</a>';
				
				echo '<div class="noticeHeading2">'.$assignmentTitle.'</div>';
				if($userIsAdmin){
						
						echo '<div style="float:right;">';
						echo '<a href="classroom.php?go=acts&view=assignment&check='.$resset['id'].'">';
						echo 'View Submissions</a>&nbsp;&nbsp;&nbsp;';
						echo '<a style=" font-weight:bold; color: #FF0000;" ';
						echo 'onclick = "return confirm(\'Are you sure?\')" ';
						echo ' href="classroom.php?go=acts&view=assignment&delete='.$resset['id'].'">X</a>';
						echo '</div>';
					}
					echo '<div class="noticeSuppinfo2">';
					
					switch($resset['type'])
					{
						case '1':
							echo '<div class="urgencyb"><b>Text</b></div>';
							break;
						case '2':
							echo '<div class="urgencyb"><b>Document</b></div>';
							break;
					}
					echo '<div class="noticeDatetime">Provided '.time_since($resset['regtime']).'</div>';
					echo '</div>'; // subinfo
					
				echo '</div>'; // heading
			}
}

function showOneAssignment($id)
{

global $userIsAdmin;
global $userIsMember;

	$usetable  =  'assignments';
		$sql = "SELECT * FROM $usetable WHERE id = '$id' ORDER BY id DESC;";
		$result = mysql_query($sql);
		
		$resset = mysql_fetch_assoc($result);
		
			echo '<div class="class_notice">';
				echo '<div class="noticeHeading">'.htmlspecialchars($resset['topic'], ENT_NOQUOTES).'</div>';
				echo '<div class="noticeSuppinfo">';
					switch($resset['type'])
					{
						case 1:
							echo 'Type <div class="urgencyb"><b>Text</b></div>';
							break;
						case 2:
							echo '<div class="urgencyb"><b>Document</b></div>';
							break;
					}
					echo '<div class="noticeDatetime"> Provided '.time_since($resset['regtime']).'</div>';
					if($userIsAdmin){
						echo '<div style="float:right;">';
						echo '<a href="classroom.php?go=acts&view=assignment&check='.$resset['id'].'">';
						echo 'View Submissions</a>&nbsp;&nbsp;&nbsp;';
						echo '<a style="font-weight:bold; color: #FF0000;  margin-left:5px; " ';
						echo 'onclick = "return confirm(\'Are you sure?\')" ';
						echo ' href="classroom.php?go=acts&view=assignment&delete='.$resset['id'].'">Remove</a>';
						echo '</div>';
					}
				echo '</div>';
				echo '<div class="noticeContent">'.nl2br(htmlspecialchars($resset['description'], ENT_NOQUOTES)).'</div>';
				echo '<div class = "noticeSuppinfo">Due date: '.date("l, dS F Y", $resset['duedate']).'</div>' ;
			echo '</div>';
			
		/************* FORM TO SUBMIT THE ASSIGNMENT **********************/
			
			if(isUserMember())
			{
				
				$submitted = getAssignmentStatus($resset["id"]);			
				
				// echo $resset['type'];
				switch ($resset['type']){
					
					case '1':
						if(!$submitted)
						{
							echo '<div class="infobox">Type in your answer and submit.';
							echo '<form onsubmit="return confirm(\'You cannot submit an assignment more than once.\nDo you want to submit the assignment?\')" method="post" action="classroom.php?go=acts&view=assignment">';
							echo '<input type="hidden" name="submitassignment" value="yes" />'; 
							echo '<input type="hidden" name="type" value="text" />'; 
							echo '<input type="hidden" name="target" value="'.$resset['id'].'" />'; 
							echo '<textarea style= "overflow:auto;" name="text" class="textAreaHolder" wrap="physical" rows="6" cols="55" ></textarea>';
							echo '<input type="submit" value="Submit Assignment" class="ovButton" />';
							echo '</form>';
							echo "</div>";
						}
						else
						{
							echo '<div class="infobox">You have already submitted this assignment.';
							echo ' <b><a href="'.$submitted.'" target="_blank">View</a></b>';
							echo '</div>';
						}
						break;
					
					case '2':
						if(!$submitted)
						{
							echo '<div class="infobox">Please upload a document file. <div class="infobox"> Photos: &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;jpg and png only<br/>Documents: &nbsp;&nbsp; &nbsp; txt, doc, c, cpp, pdf<br />Maximum size: 512 KB</div>';
							echo '<form onsubmit="return confirm(\'You cannot submit an assignment more than once.\nDo you want to submit the assignment?\')" method="post" enctype="multipart/form-data" action="classroom.php?go=acts&view=assignment">';
							echo '<input type="hidden" name="submitassignment" value="yes" />'; 
							echo '<input type="hidden" name="type" value="document" />'; 
							echo '<input type="hidden" name="target" value="'.$resset['id'].'" />'; 
							echo '<input type="file" name="document"/>';
							echo '<input type="submit" value="Submit Assignment" class="ovButton" />';
							echo '</form>';
							echo "</div>";
						}
						else
						{
							echo '<div class="infobox">You have already submitted this assignment.';
							echo $submitted;
							echo '</div>';
						}
						break;
				}
			}		
			else
				{
					echo '<div class="errbox">You must join the classroom to submit this assignment.</div>';
				}
}

function showAssignmentForm($userRequest)
{
$values = false;
if($userRequest)
{
	if(isset($_SESSION['assignmenterr']))
	{
		echo $_SESSION['assignmenterr'];
		unset($_SESSION['assignmenterr']);
	}
	if(isset($_POST['addassignment']))
	{
		$_SESSION['start']['assignmentTitle'] = @$_POST['assignmentTabe'];
		$_SESSION['start']['assignmentContent'] = @$_POST['assignmentContent'];
		$values = true;
		
		$title = mysql_real_escape_string(@$_POST['assignmentTitle']);
		$content = mysql_real_escape_string(@$_POST['assignmentContent']);
		$type = @$_POST['assignmentType'];
		
		$assignmentErr = '';
		if(!$title){
			$assignmentErr .= "You must enter topic for your assignment.<br />";
		}else if(strlen($title) > 200)
		{
			$assignmentErr .= "The Title is too long (longer than 200 letters)<br />";
		}
		
		if(!$content){
			$assignmentErr .= "You must provide content of your assignment.<br />";
		}else if(strlen($title) > 5000)
		{
			$assignmentErr = "The Assignment is too long (longer than 5000 letters)<br />";
		}
		
		if($type != 2 && $type != 3){
			$type = 1;
		}
		if(!$assignmentErr)
		{
			global $time;
			
			if((!is_int(@$_POST['numdays'])) || (@$_POST['numdays'] < 0) || (@$_POST['numdays'] > 365))
			{
				$numdays = 15;
			}
			else
			{
				$numdays = @$_post['numdays'];
			}
				$duetime = $time + ($numdays * 3600 * 24);
		
			$class = $_SESSION['currentClass'];
			
			$sql = 'INSERT INTO assignments(classid, topic, description, regtime, duedate, type) VALUES';
			$sql .= "('$class', '$title', '$content', '$time', '$duetime', '$type'); ";
			mysql_query($sql) or die("Cannot add assignment because: ".mysql_error());
			
			$_SESSION['start'] = array();
			showAssignmentForm(false);
			return true;
		}
		else{
			echo '<div class="errbox">';
			echo $assignmentErr;
			echo '</div>';
			
		showAssignmentForm(false);	/* false is for recursive calls,, ie request not made by user.. */
		return false;
		}
	
	return true;
	}else $values = false;
}

?>
<div>
	<div class="addHeading" onClick="showNewAssignmentForm()"><img src="imgs/class_plus.gif"> Add a new assignment</div>
</div>
<div id="newAssignmentBox" style="display:block;">
							<script type="text/javascript">
								function hideNewAssignmentForm(){document.getElementById('newAssignmentBox').style.display ='none';}
								function showNewAssignmentForm(){document.getElementById('newAssignmentBox').style.display='block';}
								hideNewAssignmentForm();
							</script>
<form method="post" action="classroom.php?go=acts&view=assignment">
	<input type="hidden" name="addassignment" value="yes" />
	
	<table cellspacing="0" cellpadding="0" border="0">
		 <tr>
			<td class="createRows">
				<b>Heading</b><br />
					<input class="createInputLong" onBlur="checkClassId(this.value)" type="text" name="assignmentTitle" value="<?php if($values) echo $_SESSION['start']['assignmentTitle']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="createRows">
			<b>Description</b><br />
					<textarea wrap="physical" class="textareaHolder" name="assignmentContent" id="ovTr" rows="3" cols="55" onkeyup="sizeBox(this)"><?php
						 if($values) echo $_SESSION['start']['assignmentContent']; 
					?></textarea>
			</td>
		</tr>
		<tr>
			<td class="createRows"><b>Type</b><br />
				<select name="assignmentType">
                  	<option selected="selected" value='1'>Plain text </option>
                    <option value="2">
						Document
                    </option>
				</select>
					&nbsp; in <input type="text" name="numdays" value="15" size="2" /> days
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" class="ovButton" value="Save" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="ovButton2" value="Cancel" onclick="hideNewAssignmentForm()" />
			</td>
		</tr><tr><td><br /></td></tr>
	</table>
</form>
</div>
<?php
}

function getUserFromId($usr){
		$sql = "SELECT * FROM userinfo WHERE userid = '$usr'";
	
		$query = mysql_query($sql) or die('Error fetching user. '. mysql_error());
		
		if(mysql_num_rows($query))
		{
			$userinfo = mysql_fetch_assoc($query);
			return $userinfo;
		}
		else
			return false;
}

function checkAssignments($assignment)
{
	$doers = mysql_query("SELECT user, type, contents, regtime FROM assignmentinfo WHERE assignment='$assignment';");
	if(!mysql_num_rows($doers))
	{
		echo '<div class="errbox">Nobody has submitted this assignment.</div>';
	}
	else
	{
		echo '<div class="ovMenuLeftUl">Following students have submitted the assignment </div>';
		while($userinfo = mysql_fetch_assoc($doers))
		{
			$array = getUserFromId($userinfo['user']);
			?>
			<div class="stories">
				<div class="storyLeft">
					<div class="storyLeftPic">
						<img class="img_author" src="<?php echo OV_ROOT.'/userdata/thumbs/thumb_'.$array['userid']; ?>.jpg" />
					</div>
					<div class="storyLeftInfo">
					</div>
				</div>

				<div class="storyMiddle">
				
					<div class="storyMidTitle">
						<strong><?php
							$name = '<a href="profile.php?go=view&id='.$array['userid'].'">'.$array['firstname'].' '.$array['lastname'].'</a>';
							echo $name;
							if($array['userid'] == $_SESSION['userid'])
								echo '<span class="suppinfo"> (myself) </span>';
						
						?></strong>
					</div>
					
					<div class="storyMidText">
						Submitted the assignment
						<?php echo time_since($userinfo['regtime']); ?> 
					</div>
					
					<script type="text/javascript">
						function showAssignment()
						{
							window.open("<?php echo $userinfo['contents']; ?>");
							return false;
						}
					</script>
					<div class="storyMidText">
						<a onclick = "return showAssignment();" href="<?php echo $userinfo['contents']; ?>" target="_blank" ><button class="ovButton2"> Check assignment </button></a>
					</div>
				</div>
			</div>
			<?php
		}
	}
}


}
/************************************************************************************** ASSIGNMENT BLOCK ENDS HERE   ************/


{/************************************************************************************* POLL BLOCK STARTS HERE ************/

function addNewPoll()
{
	if(!isset($_POST['pollTitle']) || !isset($_POST['opt1']) || !isset($_POST['opt2']) || !isset($_POST['opt3']) || !isset($_POST['opt4']))
	{
		echo "Couldn't add new poll. Question or options missing.";
		return;
	}
	$polltext = trim(mysql_real_escape_string($_POST['pollTitle']));
	$classid = $_SESSION['currentClass'];
	
	$pollErr = '';
		if(!$polltext)
		{
			$pollErr .= "You must enter title for your poll.<br />";
		}
		else if(strlen($polltext) > 200)
		{
			$pollErr .= "The Topic is too long (longer than 200 letters)<br />";
		}
		
	$sql = "INSERT INTO ovpolls(classid, question) ";
	$sql .= "VALUES('$classid' , '$polltext');";

		$options = 'polloptions';
		$opt1= trim(mysql_real_escape_string($_POST['opt1']));
		$opt2= trim(mysql_real_escape_string($_POST['opt2']));
		$opt3= trim(mysql_real_escape_string($_POST['opt3']));
		$opt4= trim(mysql_real_escape_string($_POST['opt4']));
		
		if(!$opt1 || !$opt2 || !$opt3 || !$opt4)
		{
			$pollErr .= "You must provide all appropriate options.<br />";
		}
		else if(strlen($opt1) > 200 || strlen($opt2) > 200 || strlen($opt3) > 200 || strlen($opt4) > 200)
		{
			$pollErr = "The Options are too long (longer than 200 letters)<br />";
		}
	
		if($pollErr)
		{
			echo '<div class="errbox" onmouseover="this.style.display=\'none\';">'.$pollErr.'</div>';
			return;
		}
		
	$pollid = mysql_query($sql) or die("cannot save questions because: ".mysql_error());
	$pollid = mysql_insert_id();
	
		$sql  = "INSERT INTO $options(value, pollid, optnum)  VALUES('$opt1' , '$pollid' , '1');";
			mysql_query($sql) or die("1cannot save options because: ".mysql_error());
		$sql = "INSERT INTO $options(value, pollid, optnum)  VALUES('$opt2' , '$pollid' , '2');";
			mysql_query($sql) or die("2cannot save options because: ".mysql_error());
		$sql = "INSERT INTO $options(value, pollid, optnum)  VALUES('$opt3' , '$pollid' , '3');";
			mysql_query($sql) or die("3cannot save options because: ".mysql_error());
		$sql = "INSERT INTO $options(value, pollid, optnum)  VALUES('$opt4' , '$pollid' , '4');";
			mysql_query($sql) or die("4cannot save options because: ".mysql_error());
		
		unset($_POST);
	return true;
}

function getPollStatus($pollid, $userid)
{
	$res = mysql_query("SELECT * FROM ovvotes WHERE userid = '$userid' AND pollid='$pollid' ;") or die(" cant get pollinfo");
	if(mysql_num_rows($res))
		return true;
	else
		return false;
}


function showAllPolls()
{	
global $userIsAdmin;
	echo '<div class="fieldHeading">';
		echo '<a href="classroom.php?go=acts&view=poll">Recent Polls</a>';
	echo '</div>';
	
	$id = $_SESSION['currentClass'];
	
		$sql = "SELECT * FROM ovpolls WHERE classid = '$id' ORDER BY pollid DESC;";
		$result = mysql_query($sql) or die("Poll search fails because: ".mysql_error());
		
		if(!mysql_num_rows($result))
		{
			echo '<div class="errbox">';
				echo 'There are no polls to display.';
			echo '</div>';
			return;
		}	
		
			while($allpolls = mysql_fetch_assoc($result))
			{
				$pollid = $allpolls['pollid'];
				$question = $allpolls['question'];
				
			
				echo '<div class="class_notice2">';
					echo '<div class="noticeHeading2">';
						echo '<a href="classroom.php?go=acts&view=poll&pollid='.$pollid.'" title="View Details">';
						echo htmlspecialchars($question).'</a>';
					echo '</div>';
					
					if($userIsAdmin){
						echo '<div style="float:right;">';
						echo '<a style="font-weight: bold; color: #FF0000;" ';
						echo 'onclick = "return confirm(\'Are you sure?\')" ';
						echo ' href="classroom.php?go=acts&view=poll&delete='.$allpolls['pollid'].'"> X </a>';
						echo '</div>';
					}
					
				echo '</div>'; //class_notice2 ends
			}
}

function showOnePoll()
{
	global $userIsAdmin;
	$userIsMember = isUserMember();
	$id = $_GET['pollid'];
	
		$sql = "SELECT * FROM ovpolls WHERE pollid = '$id' ORDER BY pollid DESC;";
		$result = mysql_query($sql) or die("Poll search fails because: ".mysql_error());
		
		if(!mysql_num_rows($result))
		{
			echo '<div class="errbox">';
				echo 'The requested poll doesn\'t belong to this classroom.';
			echo '</div>';
			return;
		}	
		
			$allpolls = mysql_fetch_assoc($result);
			
				$pollid = $allpolls['pollid'];
				$question = $allpolls['question'];
				
			
					echo '<div class="class_notice">';
					
					echo '<div class="noticeHeading">'.htmlspecialchars($question).'</div>';
					if($userIsAdmin){
						
						echo '<div style="float:right;">';
						echo '<a style=" font-weight:bold; color: #FF0000; " ';
						echo 'onclick = "return confirm(\'Are you sure?\')" ';
						echo ' href="classroom.php?go=acts&view=poll&delete='.$allpolls['pollid'].'"> X </a>';
						echo '</div>';
					}
					echo '<div class="noticeContent">';
					
					$sqlq = mysql_query("SELECT * FROM polloptions WHERE pollid='$pollid' ");
					
					$index = 1;
					
					if($userIsMember)
						$alreadyVoted = getPollStatus($pollid, $_SESSION['userid']);
					else
						$alreadyVoted = true;
					
					if(!$alreadyVoted){
						echo '<form method="post" action="votepoll.php">';
						echo '<input type="hidden" name="target" value="'.$pollid.'" />';
					}
					while($opts = mysql_fetch_assoc($sqlq))
					{					
						echo '<div style="padding:5px; margin:5px; background-color: #F5F5F5;">';
								if(!$alreadyVoted)
									echo '<input type="radio" name="option" value="'.$index.'" />&nbsp;';
								echo htmlspecialchars($opts['value']);
								
								echo "<span style='color: #FF3300'>&nbsp;&nbsp;&nbsp; Votes: ";
								
								$tocount = mysql_query("SELECT * FROM ovvotes WHERE pollid='$pollid' AND optnum = '$index' ; ");
								$count = mysql_num_rows($tocount);
								echo $count;
								echo '</span>';
								
						echo '</div>';
						
					$index++;
					}
					
					if(!$userIsMember)
						echo '<div class="errbox">You must join this classroom to vote.</div>';
					else if(!$alreadyVoted)
						echo '<input type="submit" class="ovButton2" value="Vote the selected"></form>';
					else
						echo '<div class="infobox">You have already voted for this poll. </div>';
					echo '</div>'; // noticeContents ends
					echo '</div>'; //class_notice2 ends
}

function showPollForm($userRequest)
{
?>
		<div>
			<div class="addHeading" onClick="showNewPollForm()"><img src="imgs/class_plus.gif"> Add a new poll</div>
		</div>
		<div id="newPollBox" style="display:block;">
							<script type="text/javascript">
								function hideNewPollForm(){document.getElementById('newPollBox').style.display ='none';}
								function showNewPollForm(){document.getElementById('newPollBox').style.display='block';}
								hideNewPollForm();
							</script>
		<form method="post" action="classroom.php?go=acts&view=poll">
			<input type="hidden" name="addpoll" value="yes" />
			
			<table cellspacing="0" cellpadding="0" border="0">
				 <tr>
					<td class="createRows">
						<b>Topic of Poll:</b><br />
							<input class="createInputLong" onBlur="checkClassId(this.value)" type="text" name="pollTitle" value="<?php if(isset($_POST['pollTitle'])) echo $_POST['pollTitle']; ?>" />
					</td>
				</tr>
				 <tr>
					<td class="createRows">
						<b>Option 1:</b>
							<input class="createInput" onBlur="checkClassId(this.value)" type="text" name="opt1" value="<?php if(isset($_POST['opt1'])) echo $_POST['opt1']; ?>" />
					</td>
				</tr>
				 <tr>
					<td class="createRows">
						<b>Option 2:</b>
							<input class="createInput" onBlur="checkClassId(this.value)" type="text" name="opt2" value="<?php if(isset($_POST['opt2'])) echo $_POST['opt2']; ?>" />
					</td>
				</tr>
				 <tr>
					<td class="createRows">
						<b>Option 3:</b>
							<input class="createInput" onBlur="checkClassId(this.value)" type="text" name="opt3" value="<?php if(isset($_POST['opt3'])) echo $_POST['opt3']; ?>"/>
					</td>
				</tr>
				 <tr>
					<td class="createRows">
						<b>Option 4:</b>
							<input class="createInput" onBlur="checkClassId(this.value)" type="text" name="opt4" value="<?php if(isset($_POST['opt4'])) echo $_POST['opt4']; ?>" />
					</td>
				</tr>
				<tr>
					<td class="createRows">
						<input type="submit" class="ovButton" value="Save" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" class="ovButton2" value="Cancel" onclick="hideNewPollForm()" />
					</td>
				</tr>
			</table>
		</form>
		</div>
<?php
}

function getUsrFromId($usr){
		$sql = "SELECT * FROM userinfo WHERE userid = '$usr'";
	
		$query = mysql_query($sql) or die('Error fetching user. '. mysql_error());
		
		if(mysql_num_rows($query))
		{
			$userinfo = mysql_fetch_assoc($query);
			return $userinfo;
		}
		else
			return false;
}

}/************************************************************************************** POLL BLOCK ENDS HERE   ************/


/********************************************************************** MEMBER MANAGEMENT STARTS HERE . . . EASIEST :D *******/

function showMembers($class)
{
$classid = $class['classid'];
$userIsAdmin = ($class['adminid'] == $_SESSION['userid'])? true: false;
//die($class['adminid'] . "a.....a". $_SESSION['userid']);
	$getEm = mysql_query("SELECT * FROM membership WHERE classid='$classid' AND type = '1';");
	if(!mysql_num_rows($getEm))
	{
		echo '<div class="errbox"> There are no any members in this classroom.</div>';
	}
	else
	{
		echo '<div class="ovMenuLeftUl"> Members of this classroom</div>';
		while($foundIds = mysql_fetch_array($getEm))
		{
			$array = getUserFromId($foundIds['userid']); ?>
				<div class="stories">
					<div class="storyLeft">
						<div class="storyLeftPic">
							<img class="img_author" src="<?php echo OV_ROOT.'/userdata/thumbs/thumb_'.$array['userid']; ?>.jpg" />
						</div>
						<div class="storyLeftInfo">
						</div>
					</div>

					<div class="storyMiddle">
					
						<div class="storyMidTitle">
							<strong><?php
								$name = '<a href="profile.php?go=view&id='.$array['userid'].'">'.$array['firstname'].' '.$array['lastname'].'</a>';
								echo $name;
								if($array['userid'] == $_SESSION['userid'])
									echo '<span class="suppinfo"> (myself) </span>';
							
							?></strong>
						</div>
						
						<div class="storyMidText">
							<?php echo ($array['gender']=='1')? 'Male' : 'Female'; ?>, 
							<?php $yrs = $array['birthday']; 
								$yrs = explode('-', $yrs);
								$yrs = $yrs[0];
								$now = date("Y");
								echo ($now - $yrs).' years'; 
							?>
								<br /><br />
						</div>
					<?php if($array['userid'] != $_SESSION['userid'])
							if($class['adminid'] == $_SESSION['userid'])
							{ ?>
							<div class="commentMiddle">
								<a onclick = "return confirm('You cannot add a deleted member again.\nDo you really want to remove this person?')" href="classroom.php?go=acts&view=members&delete=<?php echo $array['userid']; ?>" style="font-weight:bold;color:#FF0000;">
								Remove this member</a>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php
		}
	}
}

function showRequests($class){
$classid = $class['classid'];

	$getEm = mysql_query("SELECT * FROM membership WHERE classid='$classid' AND type = '0';");
	if(!mysql_num_rows($getEm))
	{
		echo '<div class="ovMenuLeftUl"> There are no any pending Requests.</div>';
	}
	else
	{
		echo '<div class="ovMenuLeftUl"> Pending requests <br /><br /></div>';
		while($foundIds = mysql_fetch_assoc($getEm))
		{
			$friendArray = getUserFromId($foundIds['userid']); ?>
				<div class="profileThumbs">
					<div class="profileThumbLeft">
						<div class="profileLeftPic">
							<img width="30px"  src="<?php echo OV_ROOT.'/userdata/thumbs/thumb_'.$friendArray['userid']; ?>.jpg" />
						</div>
					</div>

					<div class="profileThumbMiddle">
						<div class="profileThumbMidTitle">
							<strong><?php
								$name = '<a href="profile.php?go=view&id='.$friendArray['userid'].'">'.$friendArray['firstname'].' '.$friendArray['lastname'].'</a>'; 
								echo $name;
							?></strong>
						</div>
						
						<div class="profileThumbMidText">
							<?php echo ($friendArray['gender'] == '1')? 'Male' : 'Female'; ?>,
							<?php 
								$yrs = $friendArray['birthday']; 
								$yrs = explode('-', $yrs);
								$yrs = $yrs[0];
								$now = date("Y");
								echo $now - $yrs;
							?> years
						</div>
						<?php if($class['adminid'] == $_SESSION['userid']){ ?>
							<div class="profileActions">
								<a href="classroom.php?go=acts&view=members&accept=<?php echo $friendArray['userid']; ?>">
								<button class="ovButton">Accept user</button></a>
								
								<a onclick = "return confirm('You cannot add a deleted member again.\nDo you really want to remove this person?')" href="classroom.php?go=acts&view=members&delete=<?php echo $friendArray['userid']; ?>">
								<button class="ovButton2">Reject request</button></a>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php 
		}
	}
}

/************************************************************** MEMBER MANAGEMENT ENDS HERE ***********************************/


/****************************************************** INFORMATION UPDATE STARTS HERE ***********************************/
function showInfo($array)
{
global $userIsAdmin;
if($userIsAdmin)
	echo '<div class="fieldHeading">Update Classroom Details</div>';
else
	echo '<div class="fieldHeading">Classroom Details</div>';
	
/****** info starts here *******/
	?>
	<div style="background-color: #F5F5F5; padding-left: 30px;">
	<div class="infobox" style="padding:10px;">
		<div class="addHeading">Name: </div><a>
		<?php if(!$userIsAdmin) echo $array['title'].'</a> ( '.$array['nickname'].' ) '; ?>
		
		<?php if($userIsAdmin){ ?>
		<div style="clear:both; margin:10px;">
			<form action="updateclassinfo.php" method="post" >
			<input type="text" class="createInputLong" value="<?php echo $array['title']; ?>"/><br />
		</div>
		<?php } ?>
	</div>
	
	<div class="infobox" style="padding:10px;">
		<div class="addheading">Description: </div>
		<?php if(!$userIsAdmin) echo $array['description']; ?>
	
		<?php if($userIsAdmin){ ?>
		<div style="clear:both;margin:10px;">
			<textarea class="textAreaHolder" wrap="physical" rows="3"><?php echo $array['description']; ?></textarea><br />
		</div>	
		<?php } ?>
	</div>
	
	<div class="infobox" style="padding:10px;">
		<div class="addHeading">Address: </div>
		<?php if(!$userIsAdmin) echo $array['addr1']; ?>
	
		<?php if($userIsAdmin){ ?>
		<div style="clear:both;margin:10px;">
			<input type="text" class="createInputLong" value="<?php echo $array['addr1']; ?>"/><br />
		</div>	
		<?php } ?>
	</div>
	
	<div class="class_notice2">	
		<?php if($userIsAdmin){ ?>
		<div class="infobox">
			Please make sure the changes made are valid.<br />
			If any information is not appropriate, changes will not be saved.<br />
			<input type="submit" class="ovButton" value="Save Changes" />
			</form>
			<br /><br />
		</div>	
		<?php } ?>
	</div>
	</div>
<?php
}

/****************************************************** INFORMATION UPDATE ENDS HERE  ***********************************/



/********************************************************* NOTIFICATIONS START HERE  ***********************************/

function show_notifications()
{
	echo '<div class="fieldHeading">What\'s Happening?</div>';
	
	$sql = "SELECT * FROM notifications WHERE CLASSID = '".$_SESSION['currentClass']."'ORDER BY id DESC ";
	$sql = mysql_query($sql) or die("Couldn't fetch notifications because: ".mysql_error());
	
	if(!mysql_num_rows($sql))
	{
		echo '<div class="infobox">There are no any activities to display right now.</div>';
		return;	
	}
		
	while($resset = mysql_fetch_assoc($sql))
	{	
		echo '<div class="class_notice2">';
					
					echo '<div class="noticeHeading2">';
					echo '<img src="userdata/thumbs/thumb_'.$resset['userid'].'.jpg" width="30px" align="top" /><br />';
					echo $resset['content'];
					echo '</div>';
					
					echo '<div class="noticeSuppinfo2">'.time_since($resset['regtime']).'</div>';
					
		echo '</div>';
	}
}

/********************************************************* NOTIFICATIONS END HERE ***********************************/

function getNameFromId($id)
{
	$usr = mysql_fetch_array(mysql_query("SELECT firstname, lastname FROM userinfo WHERE userid = '$id' ;"), MYSQL_NUM);
	return $usr[0].$usr[1];
}
function getTitleFromId($id)
{
	$class = mysql_fetch_array(mysql_query("SELECT title FROM classrooms WHERE classid = '$id' ;"), MYSQL_NUM);
	return $class[0];
}

/*###################################### PARSING STARTS HERE ###########################################*/

function display_acts($array)
{
	global $userIsAdmin;
	$userIsAdmin = ($_SESSION['userid'] == $array['adminid'])?true:false;
	
	$flag = true;
	
	/************************************ SWITCH THE 'VIEW' PARAMETER *****/
	switch(@$_GET['view'])
	{
		case 'notice': 						/****** NOTICE *******/
			echo '<div class="tmidbody">';
			if($userIsAdmin)
			{
				$saved = showNoticeForm(true); /* true means we are actually calling the function because of request by parent page */
				if($saved){
					
					echo '<div class="fieldHeading" onmouseover="this.style.display =\'none\';" >';
					echo 'Notice published successfully.';
					echo '</div>';
				 }
				
				if(isset($_GET['delete']) && is_numeric($_GET['delete']))
					mysql_query("DELETE FROM notices WHERE id='".$_GET['delete']."'");
			}
	
			if(isset($_GET['notice']) && is_numeric($_GET['notice']))
			{
				showOneNotice($_GET['notice']);
			}
			else
			{
				$flag = false;
				showAllNotices($array['classid']);
			}
			
			echo '</div>'; // tmidbody ends here		
			
			if($flag)
			{
				echo '<div class="trightbody">';
				showAllNotices($array['classid']);
				echo '</div>';
				return true; /* true means right div is also filled up */
			}
			else
			{
				echo '<div class="trightbody">';
				show_notifications($array);
				echo '</div>';
				return false; /* fals means right div has not been filled up */
			}
			
		break;
		
		case 'event': 						/****** event *******/
			echo '<div class="tmidbody">';
			if($userIsAdmin)
			{
				$saved = showeventForm(true); /* true means we are actually calling the function because of request by parent page */
				if($saved){
					
					echo '<div class="addeventTitle" onmouseover="this.style.display =\'none\';" >';
					echo 'event published successfully.';
					echo '</div>';
				 }
				
				if(isset($_GET['delete']) && is_numeric($_GET['delete']))
					mysql_query("DELETE FROM events WHERE id='".$_GET['delete']."'");
			}
	
			if(isset($_GET['event']) && is_numeric($_GET['event']))
			{
				showOneevent($_GET['event']);
			}
			else
			{
				$flag = false;
				showAllevents($array['classid']);
			}
			
			echo '</div>'; // tmidbody ends here		
			
			if($flag)
			{
				echo '<div class="trightbody">';
				showAllevents($array['classid']);
				echo '</div>';
				return true; /* true means right div is also filled up */
			}
			else
			{
				echo '<div class="trightbody">';
				show_notifications($array);
				echo '</div>';
				return false; /* fals means right div has not been filled up */
			}
			
		break;
		
/***/	case 'assignment':
		
			echo '<div class="tmidbody">';
			
			if($userIsAdmin)
			{
				$saved = showAssignmentForm(true); /* true means we are actually calling the function because of request by parent page */
				if($saved){
					
					echo '<div class="addAssignmentTitle" onmouseover="this.style.display =\'none\';" >';
					echo 'Assignment published successfully.';
					echo '</div>';
				 }
			}
			/* endif */
			
			if(isset($_GET['delete']) && is_numeric($_GET['delete']))
				mysql_query("DELETE FROM assignments WHERE id='".$_GET['delete']."'");
			
			else if(isset($_POST['submitassignment']))
			{
				if(@$_POST['type'] == 'text'){
					saveTextAssignment(@$_POST['text'], @$_POST['target']);
				}
				else if(@$_POST['type'] == 'document'){
					saveDocumentAssignment(@$_FILES['document'], @$_POST['target']);
				}
			}
					
			if(isset($_GET['assignment']) && is_numeric($_GET['assignment']))
			{
				showOneAssignment($_GET['assignment']);
			}
			
			else if(isset($_GET['check']) && is_numeric($_GET['check']))
			{
				checkAssignments($_GET['check']);
			}
			else
			{
				$flag = false;
				showAllAssignments($array['classid']);
			}
			
			echo '</div>'; // tmid body ends here 
			
			if($flag)
			{
				echo '<div class="trightbody">';
				showAllAssignments($array['classid']);
				echo '</div>';
				return true; /* true means right div is also filled up */
			}
			else
			{
				echo '<div class="trightbody">';
				show_notifications($array);
				echo '</div>';
				return false; /* fals means right div has not been filled up */
			}
		break;
		
/****/	case 'members':
			if($userIsAdmin)
			{
				if(isset($_GET['delete']) && is_numeric($_GET['delete']))
					mysql_query("DELETE FROM membership WHERE userid='".$_GET['delete']."' AND classid='".$array['classid']."';") or die("Deletion fails");
				
				if(isset($_GET['accept']) && is_numeric($_GET['accept']))
				{
					mysql_query("UPDATE membership SET type='1' WHERE userid='".$_GET['accept']."' AND classid='".$array['classid']."';") or die("aCCEPT fails");
					mysql_query("UPDATE membership SET adminid='".$_SESSION['userid']."' WHERE userid='".$_GET['accept']."' AND classid='".$array['classid']."';") or die("aCCEPT fails");
				}
			}
			
			echo '<div class="tmidbody">';
				showMembers($array);
			echo '</div>';
			
			echo '<div class="trightbody">';
				showRequests($array);
				show_notifications($array);
			echo '</div>';
			
		break;
		
		
/****/	case 'poll':
		
			$flag = true;
			
			echo '<div class="tmidbody">';
			
			if($array['adminid'] == $_SESSION['userid'])
			{
				
				if(isset($_GET['delete']) && is_numeric($_GET['delete']))
				{
					mysql_query("DELETE FROM ovpolls WHERE pollid='".$_GET['delete']."'");
					mysql_query("DELETE FROM ovvotes WHERE pollid='".$_GET['delete']."'");
					mysql_query("DELETE FROM polloptions WHERE pollid='".$_GET['delete']."'");
				}
				
				showPollForm(true); /* true means we are actually calling the function because of request by parent page */
				
				if(isset($_GET['delete']) && is_numeric($_GET['delete']))
				mysql_query("DELETE FROM assignments WHERE id='".$_GET['delete']."'");
				
				if(isset($_POST['addpoll']))
					addNewPoll();
					
			}
			/* endif */
			
			if(isset($_GET['pollid']) && is_numeric($_GET['pollid']))
			{
				showOnePoll($_GET['pollid']);
				$flag = false;
			}
			
			else if(isset($_GET['check']) && is_numeric($_GET['check']))
			{
				checkPolls($_GET['check']);
			}
			else if(isset($_POST['vote']) && is_numeric(@$_POST['target']) && is_numeric(@$_POST['opt']))
			{
				vote($_POST['target'], $_POST['opt']);
			}
			
			else
			{
				showAllPolls();
				$flag = true;
			}
			
			echo '</div>'; // tmidbody ends here		
			
			if(!$flag)
			{
				echo '<div class="trightbody">';
					showAllPolls();
				echo '</div>';
				
			}
			
			else
			{
				echo '<div class="trightbody">';
				show_notifications($array);
				echo '</div>';
				return false; /* fals means right div has not been filled up */
			}
			
			break;
			
/****/	case 'members':
			
			echo '<div class="tmidbody">';
				showMembers($array);
			echo '</div>';
			
			echo '<div class="trightbody">';
				showRequests($array);
			echo '</div>';
			
			break;

/****/  case 'information':
			showInfo($array);
			
			break;
			
		case 'blog':
			include_once "blog.php";
			echo '<div class="tmidbody">';
			echo '<div class="fieldHeading">Teacher\'s Latest Blog Updates</div>';
			getBlog($array['adminid']);
			echo '</div>';
			
			echo '<div class="trightbody">';
				show_notifications($array);
			echo '</div>';
			break;
		}
}