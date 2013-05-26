<?php
	/* file: postprocess.php
		for : processing posts and comments
	*/
include_once "include/session.php";
include_once "include/conn.php";

if( ! isOnline() )
{
	header( "Location: classroom.php" );
	exit(0);
}

$time = time();

if( isset( $_REQUEST['type'] ) ){
		switch( $_REQUEST['type'] ) {
			
			case 'post':
						$userid = $_SESSION['userid'];
						$urname = $_SESSION['fname']." ".$_SESSION['lname'];
						$target = @$_REQUEST['targetClass'];
						$privacy = ( @$_REQUEST['postPrivacy'] == '2' ) ? 1 : 0;
						
						$content = @mysql_real_escape_string( $_REQUEST['newPost'] );
						if( ! trim( $content))
							break;
							
						if( is_numeric( $target)){
							$sql = "(SELECT regtime FROM membership WHERE userid='$userid' AND classid='$target' AND type = '1')";
							
							$info = mysql_query( $sql);
							
							if( ! mysql_num_rows( $info))
							{
								die("Sorry, you are not authorized.");
								//in case.. ;)
								header("Location: classroom.php");
								exit(0);
							}

							mysql_query("INSERT INTO posts(classid, category, authorid, authorname, content ,regtime) VALUES('$target', '$privacy', '$userid', '$urname', '$content', '$time')") or die("Couldn't add post. ".mysql_error());
						}
				break;
			
			case 'comment':
						$userid = $_SESSION['userid'];
						$urname = $_SESSION['fname']." ".$_SESSION['lname'];
						$target = @$_REQUEST['targetPost'];
						
						$content = @mysql_real_escape_string($_REQUEST['newComment']);
						if(!trim($content))
							break;						
						if(is_numeric($target)){
									mysql_query("INSERT INTO replies(postid,  authorid, authorname, content ,regtime) VALUES('$target', '$userid', '$urname', '$content', '$time')") or die("Couldn't add reply. ".mysql_error());					
							}
				break;
			
			case 'deletepost':
					// unnecessary use of memory ;)
					$userid = $_SESSION['userid'];
						if(!$userid)
							header("Location: login.php?redir=classroom.php");
					$postid = $_REQUEST['id']; // get requested target id
					
					if(!is_numeric($_REQUEST['id']))
					{ // don't try to play with my links ;)
						header("Location: classroom.php?idNotNumber&go=view&id=".$_SESSION['currentClass']);
						exit;
					}
					
					$authorid = mysql_query("SELECT authorid FROM posts WHERE postid = '$postid'");
					if(!mysql_num_rows($authorid))
					{ // uh ho hello.. how did you reach here ?
						header("Location: classroom.php?authorNotFound&go=view&id=".$_SESSION['currentClass']);
						exit;
					}
					$authorid = mysql_fetch_array($authorid);
					$authorid = $authorid['authorid'];
					
					if($authorid !== $_SESSION['userid'])
					{ // oops.. sorry dude, you can't delete someone else's posts
						header("Location: classroom.php?authorMismatch&go=view&id=".$_SESSION['currentClass']);
						exit;
					}else
					{
						mysql_query("DELETE FROM posts WHERE postid = '$postid';");
						header("Location: classroom.php?go=view&id=".$_SESSION['currentClass']);
						exit; // great!
					}
					break;
			
			case 'deletereply':
					$userid = $_SESSION['userid'];
					$replyid = $_REQUEST['id'];
					
					if(!is_numeric($_REQUEST['id'])){
						header("Location: classroom.php?errinid&go=view&id=".$_SESSION['currentClass']);
						exit;
					}
					
					$authorid = mysql_query("SELECT authorid FROM replies WHERE replyid = '$replyid';");
					if(!mysql_num_rows($authorid)){
						header("Location: classroom.php?noauthorfound&go=view&id=".$_SESSION['currentClass']);
						exit;
					}
					$authorid = mysql_fetch_array($authorid);
					$authorid = $authorid['authorid'];
					if($authorid != $_SESSION['userid']){
						header("Location: classroom.php?isnotauthor&go=view&id=".$_SESSION['currentClass']);
						exit;
					}
					else{
						mysql_query("DELETE FROM replies WHERE replyid = '$replyid';");
						header("Location: classroom.php?go=view&id=".$_SESSION['currentClass']);
						exit;
					}
					break;
			
			case 'message':
				
				break;
	}
}
	else /* request type is not set */ exit(0);
	;
	
	$lastPos = (@is_numeric($target))? $target : '';
	
	header("Location: classroom.php?go=view&id=".$_SESSION['currentClass']."#anc_".$lastPos);
	exit(0);

?>