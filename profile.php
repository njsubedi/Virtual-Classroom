<?php

	include_once "include/session.php";
	include_once "include/conn.php";
	
	$online = isOnline();
	if(!$online){
		header ("Location: login.php?redir=profile.php");
		exit(0);
	}
	
/************ globals? *************/
$time = time();
$currentProfile = array();
/***********************************/

function showFriendshipActions($id)
{
global $online;

	if(!$online)
		return;
		
	if($id == @$_SESSION['userid'])
		return;
	else
		{
			$frn = $id;
			$me = @$_SESSION['userid'];
		}
	
	$sql = "SELECT * FROM friends WHERE (myself='$me' AND friend='$frn') OR (myself='$frn' AND friend='$me') ; ";
	$listEm = mysql_query($sql) or die("Cannot list friends because ".mysql_error());
	if(mysql_num_rows($listEm))
	{
		while($friendDetail = mysql_fetch_assoc($listEm))
		{
			//$friendDetail = getUserFromId($foundRelation['friend']);
			switch($friendDetail['type'])
			{
				case '0': // pending request is there: request
					
					if($friendDetail['myself'] == $_SESSION['userid']){ // this is me: and my id is the first one
					
						$linkText = '<a class="buttonLinks" href=profile.php?go=friends&cancel='.$id.'>';
						$linkText .= 'Cancel friend request</a>';
						echo $linkText;
						
						break;
					}
					else if($friendDetail['friend'] == $_SESSION['userid']){
						$linkText = '<a class="buttonLinks" href=profile.php?go=friends&accept='.$id.'>';
						$linkText .= 'Confirm friend request</a>';
						echo $linkText;
						echo ' or ';
						$linkText = '<a class="buttonLinks" href=profile.php?go=friends&cancel='.$id.'>';
						$linkText .= 'Reject</a>';
						echo $linkText;
						break;
					}
					else break;
					
				case '1': // Already friends
					$linkText = '<a class="buttonLinks" href=profile.php?go=friends&cancel='.$id.'>';
					$linkText .= 'Unfriend</a>';
						echo $linkText;
					break;
			}
		}
	}
	else // there is no entry in friends table
	{
		$linkText = '<a class="buttonLinks" href=profile.php?go=friends&add='.$id.'>';
		$linkText .= 'Request friendship</a>';
		echo $linkText;
	}
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

function setUserFromId($usr){

	global $currentProfile;
	
	if($usr == $_SESSION['userid'])
		$samePerson = 'yes';
	else
		$samePerson = 'no';
		
	//	die($samePerson);
	
	if($samePerson == 'no')
	{
		$sql = "SELECT * FROM userinfo WHERE userid = '$usr'";
	
		$query = mysql_query($sql) or die('Error fetching user. '. mysql_error());
		
		if(mysql_num_rows($query))
		{
			$userinfo = mysql_fetch_assoc($query);
			
			$currentProfile['userid'] = $userinfo["userid"];
			$currentProfile['username'] = $userinfo["username"];
			$currentProfile['email'] =  $userinfo["email"];
			$currentProfile['name'] = $userinfo["firstname"].' '.$userinfo["lastname"];
			$currentProfile['classid'] = $userinfo["classid"];
			$currentProfile['classname'] = "Favourite classroom";
			
			$currentProfile['dob'] = $userinfo["birthday"];
			$currentProfile['gender'] = ($userinfo["gender"] == 1)? "Male" : "Female";
			$currentProfile['pronoun'] = ($userinfo["gender"] == 1)? "him" : "her";
			$currentProfile['adjective'] = ($userinfo["gender"] == 1)? "his" : "her";
			
			$currentProfile['password'] = $userinfo['pass'];
			$currentProfile['token'] = $userinfo['token'];
		}
		else{
			$samePerson = 'yes';
			echo '<div class="errbox" onclick="this.style.display=\'none\';">The profile you tried to view could not be displayed. The account might have been terminated or is currently unavailable, instead your profile has been displayed. <b> X </b></div>';
		}
	}
	if($samePerson == 'yes')
	{
		$currentProfile['userid'] = $_SESSION["userid"];
		$currentProfile['username'] = $_SESSION["username"];
		$currentProfile['email'] =  $_SESSION["email"];
		$currentProfile['name'] = $_SESSION["fname"].' '.$_SESSION["lname"];
		$currentProfile['classid'] = $_SESSION["classid"];
		$currentProfile['classname'] = "Favourite classroom";
		
		$currentProfile['dob'] = $_SESSION["bday"];
		$currentProfile['gender'] = ($_SESSION["gender"] == 1)? "Male" : "Female";
		$currentProfile['pronoun'] = ($_SESSION["gender"] == 1)? "him" : "her";
		$currentProfile['adjective'] = ($_SESSION["gender"] == 1)? "his" : "her";
		
		$currentProfile['token'] = $_SESSION['token'];
	}
	return;
}

/***************************** dropbox *******************/
function msgHasErrs($a)
{
	
}


function showDropbox()
{
	global $currentProfile;
	
	if($currentProfile['userid'] == $_SESSION['userid'])
	{
		$qry = mysql_query("SELECT * FROM dropbox WHERE target = '".$_SESSION['userid']."'") or die ("Couldnt fetch msgs cuz. ".mysql_error());
		
		if(mysql_num_rows($qry))
		{
			while($resset = mysql_fetch_assoc($qry))
			{
				
			}
		}
		else
			echo '<div class="infobox">There are no any messages in your dropbox.</div>';
		
	}
	else
	{
		?>
			<form action="postprocess.php" method="post" />
				<input type = "hidden" name="type" value="message" />
				<textarea class="textareaHolderMicro"><?php
					if(isset($_POST['message']) && msgHasErrs($_POST['message']))
						echo $_POST['message'];
				?></textarea>
			</form>
		<?php
	}
}


/******************************* activities **************/
function showRecentActivities($id)
{
	echo '<div class="ovMenuLeftUl">What\'s going on...</div>';
	
	$sql = "SELECT * FROM notifications WHERE userid = '".$_SESSION['userid']."'ORDER BY id DESC ";
	$sql = mysql_query($sql) or die("Couldn't fetch notifications because: ".mysql_error());
	
	if(!mysql_num_rows($sql))
	{
		echo '<div class="infobox">There are no any activities to display right now.</div>';
		return;	
	}
	
	while($resset = mysql_fetch_assoc($sql))
	{	
		echo '<div class="class_notice2">';
					
					echo '<div class="noticeHeading2">'.$resset['content'].'</div>';
					
					echo '<div class="noticeSuppinfo2">'.time_since($resset['regtime']).'</div>';
					
		echo '</div>';
	}
}


function getUserInfo($array)
{ ?>
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
				if(@$array['userid'] == @$_SESSION['userid'])
					echo '<span class="suppinfo"> (myself) </span>';
			
			?></strong>
		</div>
		
		<div class="storyMidText">
			<?php echo ($array['gender']=='1')? 'Male' : 'Female'; ?> born on 
			<?php echo $array['birthday']; ?><br /><br />
		</div>
		
		<div class="commentMiddle">
			<?php showFriendshipActions($array['userid']); ?>
		</div>
	</div>
</div>
<?php
}

function showFriends()
{
		$res = mysql_query("SELECT * FROM friends  WHERE (myself='".$_SESSION['userid']."' OR friend = '".$_SESSION['userid']."') AND type='1';");
		while($resset = mysql_fetch_assoc($res))
		{
			if($resset['friend'] == $_SESSION['userid'])
				$usr = getUserFromId($resset['myself']);
			else
				$usr = getUserFromId($resset['friend']);
			
			getUserInfo($usr);
		}
}

/********************************************** send friend request **************************/
function addFriend($target){
	
	$user = $_SESSION['userid'];
	
	if($target == $user)
	{
		echo "Same";
		return false;
	}
		
	if(!getUserFromId($target))
	{
		return false;
	}	
	if(mysql_num_rows(mysql_query("SELECT * FROM FRIENDS WHERE (myself = '$user' AND friend='$target') OR (myself = '$target' AND friend='$user');")))
		return false;
	
	$sql = "INSERT INTO friends VALUES('$user', '$target', '0')";
	$sent = mysql_query($sql) or die("Cannot process request: ".mysql_error());
	return true;
}

/********************************************** accept friend reqeust ************************/
function acceptFriend($user)
{
	if($user == $_SESSION['userid'])
		return false;
	if(!getUserFromId($user))
		return false;
	$sql = "UPDATE friends SET type='1' WHERE type='0' AND (friend='".$_SESSION['userid']."' AND myself='$user') OR (myself='".$_SESSION['userid']."' AND friend='$user') ;";
	$accepted = mysql_query($sql) or die ("Cannot process acceptance: ".mysql_error());
	return true;
}

/********************************************** delete from friend list ************************/
function cancelFriend($user)
{
	if($user == $_SESSION['userid'])
		return false;
	if(!getUserFromId($user))
		return false;
	$sql = "DELETE FROM friends WHERE (myself='".$_SESSION['userid']."' AND friend='$user')";
	$sql .= " OR (friend='".$_SESSION['userid']."' AND myself='$user') ";
	$accepted = mysql_query($sql) or die ("Cannot process deletion: ".mysql_error());
	return true;
}

/****************************************************************************************/
/************* SHOW THE CONTNETS OF PROFILE. THESE ARE FUNCITONS ************************/
/****************************************************************************************/

function showProfilePhoto(){
	global $currentProfile;
?>
	<img src="<?php echo OV_ROOT."/userdata/thumbs/pic_".$currentProfile['userid'].".jpg"; ?>" width="148px" />
<?php
}

function showFriendList()
{
	global $currentProfile;
	$fid = $currentProfile['userid'];
	
	$sql = "SELECT * FROM friends WHERE (myself = '$fid' OR friend = '$fid') AND type = '1' ;";
	$found = mysql_query($sql) or die("Sorry cannot find user's friends".mysql_error());
	
	echo '<div class="fieldHeading">Friend list</div>';
	
	if(!mysql_num_rows($found))
	{
		echo 'No friends.';
		return;
	}
	else
	while($friendIds = mysql_fetch_assoc($found))
	{
	if($friendIds['myself'] == $currentProfile['userid'])
		$friendArray = getUserFromId($friendIds['friend']);
	else
		$friendArray = getUserFromId($friendIds['myself']);
?>
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
	</div>
</div>
<?php
	}
}

function showJoinMenu($id)
{
	$sql = "SELECT * FROM membership WHERE userid='".$_SESSION['userid']."' AND classid='$id'; ";
	$listEm = mysql_query($sql) or die("Cannot show join menu because ".mysql_error());
	if(mysql_num_rows($listEm))
	{
		$foundRelation = mysql_fetch_assoc($listEm);
		
		switch($foundRelation['type'])
		{
			case '0': // pending request is there: request
					echo '<a ';
					echo 'onclick = "return confirm(\'Are you sure?\')" ';
					echo ' href=classroom.php?go=membership&membership=remove&id='.$id.'>';
					echo '<button class="ovButton2">Cancel join request</button></a>';
					
				break;
				
			case '1': // Already a member
				$linkText = 'Joined '.time_since($foundRelation['regtime']);
				echo $linkText;
				
				echo '<br />';
				echo '<a ';
					echo 'onclick = "return confirm(\'Are you sure?\')" ';
					echo ' href=classroom.php?go=membership&membership=remove&id='.$id.'>';
					echo '<button class="ovButton2">Leave the classroom</button></a>';
					
			break;
		}
	}
	else // there is no entry in friends table
	{
		$linkText = '<a href=classroom.php?go=membership&membership=request&id='.$id.'>';
		$linkText .= '<button class="ovButton2">Request to join this classroom</button></a>';
		echo $linkText;
	}
}



function getClassFromId($id, $display){
		
		$sql = "SELECT * FROM classrooms WHERE classid = '$id'; ";
		$result = mysql_query($sql);
		
		if(!mysql_num_rows($result)){
			if($display)
				echo "Sorry, no such classroom exists. ";
			return false;
		}

		else {
			$resset = mysql_fetch_assoc($result);
			
					return $resset;
		}
}

function showClassList()
{
	global $currentProfile;
	$fid = $currentProfile['userid'];
	
	$sql = "SELECT * FROM membership WHERE userid = '$fid' ;";
	$found = mysql_query($sql) or die("Sorry cannot find user's classrooms ".mysql_error());
	
	echo '<div class="fieldHeading">Classrooms list</div>';
	
	if(!mysql_num_rows($found))
	{
		echo 'No Classrooms.';
		return;
	}
	else
	while($classIds = mysql_fetch_assoc($found))
	{
		$classArray = getClassFromId($classIds['classid'], false);
?>
<div class="profileThumbs">
	<div class="profileThumbLeft">
		<div class="profileLeftPic">
			<img width="30px"  src="imgs/book.png" />
		</div>
	</div>

	<div class="profileThumbMiddle">
		<div class="profileThumbMidTitle">
			<strong><?php
				$name = '<a href="classroom.php?go=view&id='.$classArray['classid'].'">'.$classArray['title'].'</a>'; 
				echo $name;
			?></strong>
		</div>
		
		<div class="profileThumbMidText">
			<img src="imgs/class_level.gif" >
			<?php echo $classArray['level'] ; ?>
		</div>
	</div>
</div>
<?php
	}
}

function showProfileHeader(){
	global $currentProfile;
	?>
	<div class="classProfileTop">
		<div class="classProfileTopLeft">
			<?php $profileLink = '<a href="profile.php?go=view&id='.$currentProfile["userid"].'" />'.ucwords($currentProfile["name"]).'</a>'; ?>
			
			<div class="classProfileTitle">
				<?php echo $profileLink." (".$currentProfile['username'].")"; ?>
			
					
					<div class="cpl"><img src="imgs/class_location.gif" align="absmiddle" />
						<?php echo 'Dhulikhel, Nepal' ?></div>
							
					<div class="cpl">
					<img src="imgs/class_level.gif" align="absmiddle" />
						<?php $gend = ($currentProfile['gender'] != '1')? 'Male' : 'Female' ; echo $gend; ?>
						
					<div class="cpl"><img src="imgs/class_started.gif" align="absmiddle" /> Born on 
						<?php echo $currentProfile['dob'] ; ?>
					</div>
					<div class="cpl">	
						<?php showFriendshipActions($currentProfile['userid']); ?>
					</div>	
			</div>	
			</div><!-- left part of classprofile ends -->
		</div>
		<div class="classProfileTopRight">
			<div class="ovMenuLeftUl">
				Dropbox
			</div>
			<div class="noticeContent">
				<?php showDropBox(); ?>
			</div>
		</div>
	</div>
	<?php
}

function showChangePhotoForm()
{
	include_once "include/imageprocess.php";
}

function showChangePasswordForm()
{
	echo '<div class="ovMenuLeftUl">Change your password: <br /> <br /></div>';
	echo '<div class="infobox">';
		?>
			<form action="changepassword.php" method="post">
				<input type="password" class="loginInput" name="oldPass" /> &lt; Current Password <br /><br />
				<input type="password" class="loginInput" name="upPass1" /> &lt; New Password <br /><br />
				<input type="password" class="loginInput" name="upPass2" /> &lt; Repeat New Password <br /><br />
				<input type="submit" class="ovButton" value="Save new password" />
			</form>
		<?
	echo '<br /><br /></div>';
}

function showFriendRequests()
{
	global $currentProfile;
	$fid = $_SESSION['userid'];
	
	$sql = "SELECT * FROM friends WHERE (myself = '$fid' OR friend = '$fid') AND type = '0' ;";
	$found = mysql_query($sql) or die("Sorry cannot find user's friends".mysql_error());
	
	echo '<div class="ovMenuLeftUl">Pending Requests</div>';
	
	if(!mysql_num_rows($found))
	{
		echo 'You have no friend requests.';
		return;
	}
	else
	while($friendIds = mysql_fetch_assoc($found))
	{
	if($friendIds['myself'] == $currentProfile['userid'])
		$friendArray = getUserFromId($friendIds['friend']);
	else
		$friendArray = getUserFromId($friendIds['myself']);
?>
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
		
		<div class="profileActions">
			<?php showFriendshipActions($friendArray['userid']); ?>
		</div>
	</div>
</div>
<?php
	}
}


/********* GET PARAMETERS SENT ***********/

if(isset($_GET['go']) && $_GET['go'] == 'view')
{
	if(isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$userIsSet = setUserFromId($_GET['id']);	
	}
	else setUserFromId($_SESSION['userid']);
}
else
{
	setUserFromId($_SESSION['userid']);
}



/***********  ANALYSIS STARTS HERE *******************************************************/
			$nIndex = 0;
			$iIndex = 0;
			$notice = array();
			$noticeExists = false;
			$info   = array();
			$infoExists = false;
//die($_SESSION['token']);

if($_SESSION['token'] != '0') // user account is not verified
{
	$notice[$nIndex]  = '<img src="imgs/error.png" />&nbsp;&nbsp;';
	$notice[$nIndex] .= 'Your account is not verified yet. A verification code has been created in a file ';
	$notice[$nIndex] .= '<strong>tokens/'.$_SESSION['username'].'.txt</strong>.';
	$nIndex ++;
	
	$info[$iIndex]  = '<form action="verify.php" method="get">';
	$info[$iIndex] .= '<img src="imgs/info.gif" />&nbsp;&nbsp;';
	$info[$iIndex] .= 'Enter the code here: ';	
	$info[$iIndex] .= '<input class="signupinputLong" type="text" name="regToken" id="regToken" />';
	$info[$iIndex] .= '<input class="ovButton" type="submit" value="Verify" />';
	$info[$iIndex] .= 'Didn\'t receive? <a href="profile.php?go=verify"><strong>Send again</strong> </a> ';
	$info[$iIndex] .= 'or <a href="profile.php?go=edit&edit=email"><strong>Change Email</strong> </a>';
	$info[$iIndex] .= 'or <a onClick="return confirm(\'Your account will be permanently lost. Continue?\')" href="profile.php?go=drop&valid=';
	$info[$iIndex] .= md5($_SESSION['token'].'ovDropUser'.$_SESSION['userid']);
	$info[$iIndex] .= '&newuser"><strong>Delete account</strong></a>';
	$info[$iIndex] .= '</form>';
	$info[$iIndex]++;
	
	$noticeExists = true;
	$infoExists = true;
	//die($_SESSION['token']);
} 	
//else die("wtf");
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
	<link rel="stylesheet" type="text/css" href="css/profile.css" />
	<link rel="stylesheet" type="text/css" href="css/story.css" />
	<link rel="stylesheet" type="text/css" href="css/classroom.css" />
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

<!-----  THIS IS FOR TITLEBAR : INFO / NOTICE ------->

<?php if($noticeExists || $infoExists)
{
	echo '<div class="tpagetitle">';
		echo '<div class="tpagetitleflow">';
				if($noticeExists)
				{
					foreach($notice as $noticeText)
					{
						echo '<div class="errbox">';
							echo $noticeText;
						echo '</div>';
					}
				}
			
				if($infoExists)
				{
					foreach($info as $infoText)
					{ 
						echo '<div class="infobox">';
							echo $infoText;
						echo '</div>'; 
					} 
				}
		echo '</div>';
	echo '</div>';
} 
?>

<div class="tcontainer">
	<div class="tpagecontents">
		<div class="tleftbody">
			<?php
				showProfilePhoto();
				showFriendList();
				showClassList();
			?>
		</div>
		
		<div class="tcenterbody">
			<div class="tmidheader">
				<?php showProfileHeader();	?>
			</div>
				<?php
					if( @$_GET['go'] == 'edit')
					{
						if(!empty($_SESSION['editProfileErr']))
						{
							echo '<div class="errbox">'.$_SESSION['editProfileErr'].'</div>';
							unset($_SESSION['editProfileErr']);
						}
						echo '<div class="infobox">';
						echo 'For authentication reasons, you may not change anything except your password and picture';
						echo '</div>';	
						
						echo '<div class="tmidbody">';
						showChangePasswordForm();
						echo '</div>';
						
						echo '<div class="trightbody">';
						echo '<div class="ovMenuLeftUl">Change your profile picture: <br /><br /></div>';
						showChangePhotoForm();
						echo '</div>';
					}
					
					else if(@$_GET['go'] == 'friends')
					{
						if(isset($_GET['add']) && is_numeric($_GET['add']))
						{
							addFriend($_GET['add']);
						}	
						else if(isset($_GET['accept']) && is_numeric($_GET['accept']))
						{
							acceptFriend($_GET['accept']);
						}
						else if(isset($_GET['cancel']) && is_numeric($_GET['cancel']))
						{
							cancelFriend($_GET['cancel']);
						}
						
						echo '<div class="tmidbody">';
						echo '<div class="ovMenuLeftUl">Your Friends</div>';
							showFriends();
						echo '</div>';
						
						echo '<div class="trightbody">';
						showFriendRequests();
						echo '</div>';
						
						
					}
					
					else if(@$_GET['go'] == 'activities')
					{
						showRecentActivities($currentProfile['userid']);	
					}
				else
				{
					echo '<div class="tmidbody">';
					include_once "blog.php";
					
					getBlog($currentProfile['userid']);
					
					echo '</div>';
					echo '<div class="trightbody">';
						showRecentActivities($currentProfile['userid']);
					echo '</div>';
				}
				?>
		</div>
		
	</div> <!-- closes tpagecontents -->
	
	<div class="tbottombar">	</div>
</div> <!-- closes tcontainer -->

</body>
</html>