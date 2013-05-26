<?php
include_once "include/session.php";
include_once "include/conn.php";

/**** my globals :D ******/


$online = false;
$online = isOnline();
$time = time();
$userIsMember = false;
$shownFlag = true; /* KEEPS TRACK whether a classroom is shown or not... */

$foundRessetOfClass;

$isset_get_go = isset($_GET['go']);
$isset_get_id = isset($_GET['id']);
	$is_numeric_get_id = is_numeric(@$_GET['id']);

/*************************/

function showOverview()
{
	include_once "include/overview.php";
	echo '</div><div class="trightbody">';
	include_once "include/overviewImg.php";
	echo '</div>';
}

function isUserMember()
{
	global $userIsMember;
	
	$info = mysql_query("SELECT * FROM membership WHERE userid='".$_SESSION['userid']."' AND classid='".$_SESSION['currentClass']."' AND type = '1'; ");
	if(mysql_num_rows($info))
		$userIsMember = true;
	else
		$userIsMember = false;
	
	return $userIsMember;
}

function getClassInfo($resset)
{
	?>
	<div class="classProfileTop">
		<div class="classProfileTopLeft">
			<?php $classLink = '<a href="classroom.php?go=view&id='.$resset["classid"].'" />'.$resset["title"].'</a>'; ?>
			
			<div class="classProfilePic">
				<img src="imgs/classroom.jpg" alt="Your online virtual classroom" />
			</div>
			<div class="classProfileTitleDesc">
				<div class="classProfileTitle">
					<?php echo $classLink." (".$resset['nickname'].")"; ?>
				</div>		
					
				<div class="classProfileSuppInfo"><?php
						echo $resset['members']; ?> students | <img src="imgs/class_location.gif"><?php
						echo $resset['addr1']; ?><br />
						
					<div class="classProfileTopAbout">
						<?php echo $resset['description']; ?>
					</div>
				<?php	/* show the fav/non fav info */
					if($_SESSION['classid'] == $resset['classid'])
					{
						echo '<img src="imgs/class_ok.gif"> This is your Favorite Classroom</a>';
					}
					else
					{
						echo '<a onclick = "return confirm(\'Are you sure to make it you fav class?\')" ';
						echo ' href="classroom.php?makefav">';
						echo '<img src="imgs/class_lv.gif" alt="?" /> Choose as Favorite</a>';
					}
				?>
				</div>
			</div>
		</div><!-- left part of classprofile ends -->
		
		<div class="classProfileTopRight">
			<?php $adminInfo = '<a href="profile.php?go=view&id='.$resset['adminid'].'">'.$resset['adminname'].'</a>'; ?>
				
			<div class="cpl">
				<img src="imgs/class_level.gif" align="bottom" />
				<?php echo $resset['level']; ?>
				
				&nbsp;&nbsp;<img src="imgs/class_privacy.gif" align="bottom" />
				<?php echo $resset['type']; ?>
				
				&nbsp;&nbsp;<img src="imgs/class_started.gif" align="bottom" />
				<?php echo $resset['regtime']; ?>
			<br /><br />
				
				<img src="imgs/class_creator.gif" align="absmiddle" />
				Started by <?php echo $adminInfo; ?><br /><br />
			</div>
			<div class="cpl">	
				<?php showJoinMenu($resset['classid']); ?>
			</div>
		</div>
	</div>
	
<?php
	
	if(!isUserMember())
	{
		echo '<div class="infobox">';
		echo '<img src="imgs/lock.gif" /> ';
		echo 'You must join this classroom to discuss, share or interact.</div>';
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
					echo ' href="classroom.php?go=membership&membership=cancel&id='.$id.'">';
					echo 'Cancel join request</a>';
					
				break;
				
			case '1': // Already a member
				$linkText = 'Joined '.time_since($foundRelation['regtime']);
				echo $linkText;
				
				echo '&nbsp;&nbsp;&nbsp;<a ';
				echo 'onclick = "return confirm(\'Are you sure?\')" ';
				echo ' href="classroom.php?go=membership&membership=cancel&id='.$id.'">';
				echo '<img src="imgs/class_leave.gif"> Leave</a>';
				
			break;
		}
	}
	else // there is no entry in friends table
	{
		$linkText = '<a href="classroom.php?go=membership&membership=request&id='.$id.'">';
		$linkText .= 'Request to join this classroom</a>';
		echo $linkText;
	}
}

function listAllClassrooms()
{
	$userId = $_SESSION['userid'];
	$usrFind = mysql_query("SELECT classid FROM membership WHERE userid='".$_SESSION['userid']."';") or die("NO MEMBERSHIP ".mysql_error());
	
	if(!mysql_num_rows($usrFind))
	{
		echo '<div class="classAbout"><h2>&nbsp;Welcome, '.$_SESSION['fname'].'!</h2></div>';
		echo '<div class="errbox"><br /><center><b>Oops! You have not been enrolled to any classroom yet.</b></center><br /></div>';
		
		echo '<a class="iconbig" href="classroom.php?go=start">Create your own classroom</a>';
		echo '<a class="iconbig" href="classroom.php?go=enroll">Choose from available classrooms</a>';
		echo '<a class="iconbig" href="search.php?what=classrooms">Search for appropriate classroom</a>';
		
		return;
	}
	
	echo '<div class="createTitle">Classrooms you have enrolled</div>';
	while($allClasses = mysql_fetch_assoc($usrFind))
	{
		$searchThisClassId = $allClasses['classid'];
		
		$sql = "SELECT * FROM classrooms WHERE classid ='$searchThisClassId';";
		$result = mysql_query($sql) or die("Couldn't search for classrooms. ".mysql_error());
			
			if(mysql_num_rows($result))
			{
				$resset = mysql_fetch_assoc($result);
					echo '<div style="border:1px solid #E5E5E5; overflow:hidden; margin-left: 5px; margin-bottom:10px; margin-top:10px; float:left; clear:both;  -moz-border-radius: 5px; border-radius: 5px;" >';
/* --> */			getClassInfo($resset);
					echo '</div>';
			}
	
	}
	
	return;
}

function listMyClassrooms()
{
	$userid = $_SESSION['userid'];
		$sql = "SELECT * FROM classrooms WHERE adminid = '$userid' ;";
		$result = mysql_query($sql) or die("Couldn't search for classrooms. ".mysql_error());
			
			if(!mysql_num_rows($result))
			{
				echo '<div class="classAbout"><h2>&nbsp;Welcome, '.$_SESSION['fname'].'!</h2></div>';
				echo '<div class="infobox"><b>So, you want to start a classroom for your students, huh?</b></div>';
				
				echo '<a class="iconbig" href="classroom.php?go=start">Create your own classroom</a>';
				echo '<a class="iconbig" href="classroom.php?go=enroll">Choose from available classrooms</a>';
				echo '<a class="iconbig" href="search.php?what=classrooms">Search for appropriate classroom</a>';
				
				return;
			}
			else 
			{
				echo '<div class="createTitle">Classrooms You have created</div>';
				while($resset = mysql_fetch_assoc($result))
				{
/* --> */			echo '<div style="border:1px solid #E5E5E5; overflow:hidden; margin-left: 5px; margin-bottom:10px; margin-top:10px; float:left; clear:both;  -moz-border-radius: 5px; border-radius: 5px;" >';
/* --> */			getClassInfo($resset);
					echo '</div>';
				}
			}
	
	return;
}

function showClassroomPosts($id, $what)
{ 
	global $time;
	
	if($what == false) return;

	if($canSeePosts = isUserMember())
	{
?>
		<div class="newPost">
		<form name="postForm" action="postprocess.php" method="post" onsubmit="return addNewPost();" enctype="multipart/form">
			
			<div class="newPostTitle">
					<img src="imgs/class_plus.gif" align="absmiddle"> Share something
					&nbsp;&nbsp;&nbsp;&nbsp;
					
					<a href="javascript:" title="share a photo"
					onClick="document.getElementById('newpic').style.display='block';">
					Upload file</a>
			</div>
			<div class="newPicTitle" id="newpic">
					<input type="file" name="newPic" class="newPicInput" />
					<button type="reset" class="ovButton2" onClick="document.getElementById('newpic').style.display='none';">Cancel</button>
			</div>
					<script type="text/javascript">
						document.getElementById('newpic').style.display='none';
					</script>		
			<input type="hidden" name="type" value="post"/>
			<?php echo "<input type=\"hidden\" name=\"targetClass\" value=\"$id\">"; ?>
			
			<div class="newPostBody">
				<textarea id="newPostText" name="newPost" wrap="physical" rows="2" cols="55" onfocus="sizeBox(this);document.getElementById('npFooter').style.display='block';" ></textarea>
			</div>	
			<div class="newPostFooter" id="npFooter">
				<span class="darktext">&nbsp;&nbsp;Who can see this?</span>
				<select name="postPrivacy" class="bDay" >
					<option selected="selected" value="1"> Members of this class </option>
					<option value="2"> Anyone on ovclass </option>
				</select>						
				<button type="submit" class="ovButton">Share</button>
				<button class="ovButton2" onclick="if(!document.getElementById('newPostText').value){document.getElementById('npFooter').style.display='none';
				document.getElementById('newPostText').rows ='2';}">Cancel</button>
			</div>
				<script type="text/javascript">	document.getElementById('npFooter').style.display='none';</script>	
		</form>
		
		<div class="recentPostsInfo"> Recent posts  </div>
		</div>
<?php } 
	  else
	  {
		echo '<div class="fieldHeading">Publicly Shared Posts</div>';
	  }
?>
	
	<div class="wallPosts">
	<?php

	// query for all posts which are for this classroom
	$sql = "SELECT * FROM posts WHERE classid='$id' ";
	if(!$canSeePosts)
		$sql .= " AND category = '1' "; // 1 is private post.. 0 is not
	$sql .= "ORDER BY postid DESC LIMIT 0, 30;";
	
	$sqlquery = mysql_query($sql) or die("Couldn't load posts. ".mysql_error());
	
	// proceed if only there exist any posts
	if(!mysql_num_rows($sqlquery))
	{
		echo '<div class="infobox">There are no posts to display right now!</div>';		
	}
	else
	{
		while($resset = mysql_fetch_assoc($sqlquery))
		{
			$postid = $resset['postid'];
			$classid = $resset['classid'];
			$category = ($resset['category'] == '1')? ' | Everyone ' : 'Only members ';
			$author = '<a href="profile.php?go=view&id='.$resset['authorid'].'">'.$resset['authorname'].'</a>';
			$content = nl2br(@htmlentities($resset['content']));
			//	$content = "<span>".@wordwrap($content, 70, "</span><wbr /><span>\n", true)."</span>";
			$content = "<span>".$content."</span>";
			$time = time_since($resset['regtime']);
	?>	
			<div class="stories">
				<div class="storyLeft">
					<div class="storyLeftPic">
						<img class="img_author" src="<?php echo OV_ROOT.'/userdata/thumbs/thumb_'.$resset['authorid']; ?>.jpg" />
					</div>
					<div class="storyLeftInfo">
					</div>
				</div>
			
				<div class="storyMiddle">
				
					<div class="storyMidTitle">
						<strong><?php echo $author; ?></strong>
					</div>
					
					<div class="storyMidText">
						<?php echo $content; ?>
					</div>
					
					<div class="storyAllComments">
						<div class="suppInfo"><!-- for likes..etc info before comments -->
							<?php echo $time; ?>
							<?php echo ' <b>'.$category. '</b> can see this post.'; ?>
							
						<?php if($resset['authorid'] == $_SESSION['userid']) { ?>
							<span style="float:right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a onclick="return confirm('Are sure you want to delete this?')" href="postprocess.php?type=deletepost&id=<?php echo $postid ; ?>">Delete</a>
							</span>
						<?php } ?>
						</div>
					
					<?php
							$commentQuery = mysql_query("SELECT * FROM replies WHERE postid='$postid' ORDER BY postid DESC LIMIT 0, 30;") or die("Couldn't load posts. ".mysql_error());
						
							if(mysql_num_rows($commentQuery)){
			
								while($rresset = mysql_fetch_assoc($commentQuery)) {
										$replyid = $rresset['replyid'];
										$rpostid = $rresset['postid'];
										$rauthor = '<a href="profile.php?go=view&id='.$rresset['authorid'].'">'.$rresset['authorname'].'</a>';
										$rcontent = nl2br(htmlentities($rresset['content']));
									//	$rcontent = "<span>".@wordwrap($rcontent, 60, "</span><span>\n", true)."</span>";
										$rcontent = "<span>".$rcontent."</span>";
										$rtime = time_since($rresset['regtime']);
					?>
						<div class="comments">
								<div class="commentLeft">
									<img src="userdata/thumbs/thumb_<?php echo $rresset['authorid']; ?>.jpg" width="40px" />
								</div>
								<div class="commentMiddle">
									<div class="commentBody">
										<?php echo "$rauthor $rcontent"; ?>
									</div>
									<div class="suppInfo">
										<?php echo $rtime; ?>
										<?php if($rresset['authorid'] == $_SESSION['userid']) { ?>
										<span style="float:right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<a class="lighttext" onclick="return confirm('Are sure you want to delete this?')"  href="postprocess.php?type=deletereply&id=<?php echo $replyid ; ?>">Delete</a>
										</span>
						<?php } ?>
									</div>
								</div>
						</div>
					
					<?php 
								} /* while condition for comment ends here */
							} /* if condition ends here */
				/* this block creates a comment field whose visibility can be toggled  */
					// this is a link to open the comment field
				if($canSeePosts)
				{
				?>
				<div class="comments">
				<form name="commentForm" action="postprocess.php" method="post" onSubmit="return addReply();">
					<input type="hidden" name="type" value="comment"/>
					<input type="hidden" name="targetPost" value="<?php echo $postid; ?>" />
								
						<a class="lighttext" name="anc_<?php echo $postid ;?>"  href="posts.php?id=<?php echo $postid ;?>" 
						onClick="document.getElementById('commentArea_<?php echo $postid; ?>').style.display = 'block';
						document.getElementById('commentField_<?php echo $postid; ?>').focus();return false;">
						<img src="imgs/class_comment.gif" align="top" /> Add a Reply</a>
						
						
					<?php /*
					<!--
						<a class="lighttext" href="reactions.php" onclick="return addLike(<?php echo $postid ;?>);" >
						<img src="imgs/class_thumbup.gif" /></a>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<a class="lighttext" href="reactions.php" onclick="return delLike(<?php echo $postid ;?>);" >
						<img src="imgs/class_thumbdn.gif" /></a>
						-->
					*/ ?>
						
						<div style='display:none' id='commentArea_<?php echo $postid ;?>'><div id='textFcCont'>
						<textarea wrap="physical" onkeyup="sizeBox(this)" class="textareaHolderMini" name="newComment" rows="1" id='commentField_<?php echo $postid ;?>'></textarea></div>
						<button type="submit" class="ovButton" >Reply
						</button></span>&nbsp;&nbsp;&nbsp;&nbsp;
						
						
						<button class="ovButton2" 
						onClick="document.getElementById('commentArea_<?php echo $postid ;?>').style.display='none'; document.getElementById('commentArea_<?php echo $postid ;?>').rows = 2
						return false;">Cancel</button></div>
				</form>
				</div>
<?php			}
				echo '</div><!-- storyAllComments ends here -->';
				echo '</div><!-- story middle ends -->';
			echo '</div><!-- stories end here -->';
		} /* while condition for array-fetch of posts end here */
	}/*end-if mysql-num-rows check ELSE condition ends here */
	
	echo '</div>'; // wallpost div ends here
} /* function ends here */


function showClassroomActs($cid, $what)
{ 
	if($what == false) return false;
	
	include_once "include/class_acts.php";
			show_notifications();
	return true;
}


function showClassroom($id, $display){
	global $shownFlag;
		$sql = "SELECT * FROM classrooms WHERE classid = '$id' ";
		$result = mysql_query($sql) or die("Couldn not show classroom. ".mysql_error());
		
		if(!mysql_num_rows($result))
		{
			if($display)
			{
				echo '<div class="errbox" style="margin-bottom:10px;" onmouseover="this.style.display=\'none\';">';
				echo 'The Classroom You requested could not be displayed. The classroom doesn\'t exist or there was an error in URL.';
				echo '</div>';
				
				if(!$shownFlag)
					showClassroom($_SESSION['currentClass'], true);
				else
					return;
				$shownFlag = true;
			}
			return true;
		}
		else
		{
			/* save the currently viewing class in session... */
			$_SESSION['currentClass'] = $id;
					
			$resset = mysql_fetch_assoc($result);
			if($display){
/* --> */		getClassInfo($resset);
				return $resset;
			}
			else{
				return $resset;
			}
		}
}

function showAppropriateContents(){

		global $isset_get_go;
		global $isset_get_id;
		global $is_numeric_get_id;
		global $foundRessetOfClass;
		
	if($isset_get_go)
	{
		switch($_GET['go'])
		{
			case 'start':
				include_once "include/start.php";
				break;
				
			case 'view':
			
				if($isset_get_id)
				{					
					if($_GET['id'] && $is_numeric_get_id){
						$toGet = $_GET['id'];
					}
					else{
						$toGet = $_SESSION['classid'];
					}
				}else
				{
						$toGet = $_SESSION['classid'];
				}
				
					//die($toGet);
					
				echo '<div class="tmidheader">';
	/* --> */		$csShown = showClassroom($toGet, true);
				echo '</div>';
				
				echo '<div class="tmidbody">';
	/* --> */		showClassroomPosts($toGet, $csShown);
				echo '</div>';
				
				echo '<div class="trightbody">';
	/* --> */		showClassroomActs($toGet, $csShown);
				echo '</div>';
				
				break;
			
			case 'admin':
	/* --> */		listMyClassrooms();
				break;
				
			case 'save':
					include_once "include/newclass.php";
				break;
				
			case 'enroll':
	/* --> */		listAllClassrooms();
				break;
			
			case 'acts':
				include_once "include/class_acts.php";
			
				echo '<div class="tmidheader">';
/* --> */			$csShown = showClassroom($_SESSION['currentClass'], true);
				echo '</div>';
					$rightBarFilled = display_acts($foundRessetOfClass);					
				break;
			
			case 'about':
					echo '<div class="tmidbody">';
					showOverview();
					echo '</div>';
				break;
			
			case 'membership':
				include_once "include/membership.php";
				include_once "include/class_acts.php";
				if(isset($_GET['membership']) && isset($_GET['id']) && is_numeric($_GET['id']))
				{
					$type = $_GET['membership'];
					
					switch($type)
					{
						case 'request':
							requestJoin($_SESSION['userid'], $_SESSION['currentClass']);
							break;
						
						case 'cancel':
							cancelJoin($_SESSION['userid'],  $_SESSION['currentClass']);
							break;
					}
				}
				showClassroom($_SESSION['currentClass'], true);
				
				echo '<div class="infobox">';
				showMembers(showClassroom($_SESSION['currentClass'], false));
				echo '</div>';
				break;
		
			default:		
			/* no 'go' parameters found */
				$toGet = $_SESSION['classid'];
				
				echo '<div class="tmidheader">';
	/* --> */			$csShown = showClassroom($toGet, true);
				echo '</div>';
				
				echo '<div class="tmidbody">';
	/* --> */			showClassroomPosts($toGet, $csShown);
				echo '</div>';
				
				echo '<div class="trightbody">';
	/* --> */			//showClassroomActs($toGet, $csShown);
				break;
		}
	}
	else{
		if(isset($_GET['makefav']))
		{
			mysql_query("UPDATE userinfo SET classid ='".$_SESSION['currentClass']."' WHERE userid='".$_SESSION['userid']."';") or die("Cannot set fav. ".mysql_error());
			$_SESSION['classid'] = $_SESSION['currentClass'];
		}
		/* no 'go' parameters found */
			$toGet = $_SESSION['classid'];
			
			echo '<div class="tmidheader">';
/* --> */			$csShown = showClassroom($toGet, true);
			echo '</div>';
			
			echo '<div class="tmidbody">';
/* --> */			showClassroomPosts($toGet, $csShown);
			echo '</div>';
			
			echo '<div class="trightbody">';
/* --> */			showClassroomActs($toGet, $csShown);
			echo '</div>';
	}
}


if(!isset($_GET['go']) && @$_SESSION['classid'] == 1)
	header("Location: classroom.php?go=enroll");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
  <head>
	<title>Online Virtual Classroom</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/template.css" />
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<link rel="stylesheet" type="text/css" href="css/classes.css" />
	<link rel="stylesheet" type="text/css" href="css/search.css" />
	<link rel="stylesheet" type="text/css" href="css/classroom.css" />
	<link rel="stylesheet" type="text/css" href="css/story.css" />
	<link rel="stylesheet" type="text/css" href="css/chat.css" />
		
	<script type="text/javascript" src="js/classroom.js" > </script>
	<script type="text/javascript" src="js/like.js" > </script>
	<script type="text/javascript" src="js/comment.js" > </script>
	<script type="text/javascript" src="js/create.js" > </script>
	
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

<div class="tpagetitle">
	<div class="tpagetitleflow">
		<?php include_once "include/barmenu.php";
		if($online){
					global $isset_get_go;
					global $foundRessetOfClass;
					
					if($isset_get_go && $isset_get_id && $is_numeric_get_id)
					{
						$foundRessetOfClass = showClassroom($_GET['id'] , false);
					}
					else
					{
						if(isset($_SESSION['currentClass']))
							$foundRessetOfClass = showClassroom($_SESSION['currentClass'], false);
						else
							$foundRessetOfClass = showClassroom($_SESSION['classid'], false);
					}
					
					showBarMenu($foundRessetOfClass, 'all');
				}
			else
				echo '<div style="margin:5px;">You have not logged in. Please login or <a href="index.php">click here</a> to register a new account.</div>';
		?>
	</div>
</div>

<div class="tcontainer">
	<div class="tpagecontents">
		<div class="tleftbody">
			<?php include_once "include/leftmenu.php";
				if($online){
					global $isset_get_go;
					global $foundRessetOfClass;
					
					if($isset_get_go && $isset_get_id && $is_numeric_get_id)
					{
						$foundRessetOfClass = showClassroom($_GET['id'] , false);
					}
					else
					{
						if(isset($_SESSION['currentClass']))
							$foundRessetOfClass = showClassroom($_SESSION['currentClass'], false);
						else
							$foundRessetOfClass = showClassroom($_SESSION['classid'], false);
					}
					showLeftMenu($foundRessetOfClass, 'all');
				}
				else
					echo '<div class="infobox">Hello, guest!</div>';
			?>
		</div>
		
		<div class="tcenterbody">
		<?php  
			if($online)
			{
				showAppropriateContents();				
			}
			/* if not online */
			else
			{
				echo '<div class="tmidbody">';
				showOverview();
				echo '</div>';
			}
		?>
		</div>
		
	</div> <!-- closes tpagecontents -->
	
	<!-- COMMON FOOTER FOR INTRO PAGES THROUGHOUT THE SITE --> 
	<div class="bottombar">
		 <a href="login.php"> </a>
		 
	</div>
</div> <!-- closes tcontainer -->

</body>
</html>
