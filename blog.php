<?php
// blog.php


function addBlog($topic, $text)
{
	global $time;
	$usr = $_SESSION['userid'];
	
	$sql = "INSERT INTO blog(userid, topic, content, regtime) VALUES('$usr', '$topic', '$text', $time)";
	mysql_query($sql) or die("cannont add blog cuz. ".mysql_error());
	return;
}

function displayBlogForm()
{
$values = false;

	if(isset($_SESSION['blogerr']))
	{
		echo $_SESSION['blogerr'];
		unset($_SESSION['blogerr']);
	}
	if(isset($_POST['addblog']))
	{
		$_SESSION['start']['blogTitle'] = @$_POST['assignmentTabe'];
		$_SESSION['start']['blogContent'] = @$_POST['assignmentContent'];
		$values = true;
		
		$title = mysql_real_escape_string(@$_POST['blogTitle']);
		$content = mysql_real_escape_string(@$_POST['blogContent']);
		
		$blogErr = '';
		if(!$title){
			$blogErr .= "You must enter topic for your blog.<br />";
		}else if(strlen($title) > 200)
		{
			$blogErr .= "The Title is too long (longer than 200 letters)<br />";
		}
		
		if(!$content){
			$blogErr .= "You must provide content of your blog.<br />";
		}else if(strlen($title) > 5000)
		{
			$blogErr = "The Assignment is too long (longer than 5000 letters)<br />";
		}
		
		if(!$blogErr)
		{
			addBlog($title, $content);
			return true;
		}
		else{
			echo '<div class="errbox">';
			echo $blogErr;
			echo '</div>';
			
		showBlogForm(false);	/* false is for recursive calls,, ie request not made by user.. */
		return false;
		}
	
	return true;
	}
	else $values = false;
?>
<div>
	<div class="addHeading" onClick="showNewBlogForm()"><img src="imgs/class_plus.gif" /> Add a new blog post</div>
</div>
<div id="newBlogBox" style="display:block;">
		<script type="text/javascript">
			function hideNewBlogForm(){document.getElementById('newBlogBox').style.display ='none';}
			function showNewBlogForm(){document.getElementById('newBlogBox').style.display='block';}
			hideNewBlogForm();
		</script>

<form method="post" action="profile.php">
	<input type="hidden" name="addblog" value="yes" />
	
	<table cellspacing="0" cellpadding="0" border="0">
		 <tr>
			<td class="createRows">
				<b>Blog Title</b><br />
					<input class="createInputLong" onBlur="checkClassId(this.value)" type="text" name="blogTitle" value="<?php if($values) echo $_SESSION['start']['blogTitle']; ?>" />
			</td>
		</tr>
		<tr>
			<td class="createRows">
			<b>Content</b><br />
					<textarea wrap="physical" class="textareaHolder" name="blogContent" id="ovTr" rows="3" cols="55" onkeyup="sizeBox(this)"><?php if($values) echo @$_SESSION['start']['blogContent']; 
					?></textarea>
			</td>
		</tr>
		<tr>
			<td class="createRows">
				<input type="submit" class="ovButton" value="Save" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="ovButton2" value="Cancel" onclick="hideNewBlogForm()" />
			</td>
		</tr><tr><td><br /></td></tr>
	</table>
</form>
</div>
<?php
}

function getBlog($userid)
{
	global $currentProfile;
	if($userid == $_SESSION['userid'])
	{
		displayBlogForm();
	}
	
	if(isset($currentProfile)){
		echo '<div class="ovMenuLeftUl">'.ucwords($currentProfile['name']).'\'s blog</div>';
	}
	
	$sql = "SELECT * FROM blog WHERE userid = $userid ;";
	$found = mysql_query($sql) or die ("cannot get blog because ".mysql_error());
	if(!mysql_num_rows($found))
	{
		echo '<div class="infobox">There are no blogs to display</div>';
	}
	
	while($resset = mysql_fetch_assoc($found))
	{
			$blog = $resset['id'];
			$topic = nl2br(@htmlentities($resset['topic']));
			$content = nl2br(@htmlentities($resset['content']));
			$content = "<span>".$content."</span>";
			$time = time_since($resset['regtime']);
	?>	
			<div class="stories">
				<div class="storyLeft">
					<div class="storyLeftPic">
						<img class="img_author" src="<?php echo OV_ROOT.'/userdata/thumbs/thumb_'.$resset['userid']; ?>.jpg" />
					</div>
				</div>
			
				<div class="storyMiddle">
				
					<div class="storyMidTitle">
						<strong><?php echo $topic; ?></strong>
					</div>
					
					<div class="storyMidText">
						<?php echo $content; ?>
						<br /><br />
						Updated <?php echo $time; ?>
					</div>
				</div>
			</div>
	<?php
	}
}

?>