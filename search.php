<?php
/* file : search.php
	for: searching users/classes/online users etc
*/
include_once "include/session.php";
$online = isOnline();

$toShow = '';

/************** get input vars ***/
function isvalid_email($a)
{
	return @eregi("^([a-zA-Z0-9_]+)([\.a-zA-Z0-9\_-]+)@([\.a-zA-Z0-9-]+)(\.[a-zA-Z0-9-]+)+$", $a);
}

function showJoinMenu($id)
{
global $online; 

if(!$online)
	return;
	
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

function s_getClassInfo($resset)
{
	?>
	<div class="classProfileTop">
		<div class="classProfileTopLeft">
			<?php $classLink = '<a href="classroom.php?go=view&id='.$resset["classid"].'" />'.$resset["title"].'</a>'; ?>
			
			<div class="classProfilePic">
				<img src="imgs/classroom.jpg" alt="Your online virtual classroom" />
			</div>
			
			<div class="classProfileTitle">
				<?php echo $classLink." (".$resset['nickname'].")"; ?>
			</div>		
				
			<div class="classProfileSuppInfo">
					<?php echo $resset['members']; ?> students |
					<img src="imgs/class_location.gif">
					<?php echo $resset['addr1']; ?><br />
					
				<div class="classProfileTopAbout">
					<?php echo $resset['description']; ?>
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
}

function s_getUserInfo($array)
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
	</div>
</div>
<?php
}

function ovSearch(){

	global $toShow;
	
		if(!isset($_REQUEST['what']))
		{
			$toShow = "Use the form on the left side to search...";
			return false;
		}

		else
		{
			switch($_REQUEST['what']) {
				case 'classrooms':
				
						
					if(@$_REQUEST['title'] && ctype_alnum(@$_REQUEST['title']))
					{
						$sql = "SELECT * FROM classrooms WHERE ";
						$sql .= "title LIKE '%".$_REQUEST['title']."%' ";
						$flag = 1;		// title chosen
					}
						else $flag = 0; // no title chosen
						
					if(ctype_alpha(@$_REQUEST['privacy']))
					{
						if(!$flag){							
							$sql = "SELECT * FROM classrooms WHERE type = '".$_REQUEST['privacy']."' ";
						}
					}else
					{
						if(!$flag){							
							$sql = "SELECT * FROM classrooms WHERE type like '%P%' ";
						}
					}	
					
					if(ctype_alpha(@$_REQUEST['level']))
					{
						$sql .= " AND";
						$sql.= " level LIKE '%".$_REQUEST['level']."%' ";
					}
						
				//	$sql .= " ORDER BY classid ASC LIMIT 0, 20";
					
					$foo = true;
					break;

				 
				case 'friends':
					if(@$_REQUEST['name'])
					{
						$sql = "SELECT * FROM userinfo WHERE (";
						$chunks = explode(" ", $_REQUEST['name']);
						 
						foreach($chunks as $searchItem){
						
							if(ctype_alpha($searchItem)){
								
								$sql .= " firstname LIKE '%$searchItem%' OR lastname LIKE '%$searchItem%' OR ";
							}
						}
						$sql .= " firstname = 'ovclass') ";
					}
					else if(@$_REQUEST['email'] and isvalid_email(@$_REQUEST['email']))
					{
						$sql = "SELECT * FROM userinfo WHERE email = '".$_REQUEST['email']."' ";
					}
					else
					{
						$toShow = "You must type a name or valid email to search for people.";
						return false;
					}
				
					if(@$_REQUEST['age'] && ctype_digit(@$_REQUEST['age']))
					{
						$sql .= " AND birthday LIKE '%".$_REQUEST['age']."%'";
					}
					
					$sql .= "ORDER BY userid DESC LIMIT 0, 20";
					
					$foo = true;
					break;
				
				default:
					$foo = false;
				
			} // switch ends here
			
			if(!$foo)
			{
				$toShow = "Please fill up the form properly to search.";
				return false;
			}
			
			else
			{
				if($sql)
				{
				
				/*	ADD A line comment IN THE BEGINNING OF THIS LINE TO DEBUG
				// debug:
				echo "Generated query: <br /><br />";
				echo $sql;
				echo '<br /><br />';
				
			// */
			
					$findThem = mysql_query($sql) OR die("Couldn't complete search. Mysql error: ".mysql_error());
					
					if(!mysql_num_rows($findThem))
					{
						$toShow = "Sorry, your search returned no results. ";
						return false;
					}
					
					$toShow = "&nbsp;Following matches were found!";
					echo '<div class="classAbout"><h2>';
					echo $toShow;
					echo '</h2></div>';
					
					if(@$_REQUEST['what'] == 'classrooms')
					{
						while($resset  = mysql_fetch_array($findThem))
						{
							s_getClassInfo($resset);
						}
					}
					else if(@$_REQUEST['what'] == 'friends')
					{
						while($resset  = mysql_fetch_array($findThem))
						{
							s_getUserInfo($resset);
						}		
					}
					
					return true;
				}
			}
		}
		if($foo)
		{
			$result = mysql_query($sql) or die("Couldn't search. ".mysql_error());

			if(mysql_num_rows($result)){

				while($resset = mysql_fetch_array($result))
				{
					echo '<div class="classProfileTop">';
					echo '<div class="classProfilePic"><img src="imgs/classNew.jpg" alt="LOGO" /></div>';

					echo '<div class="classProfileText">';
						$titleLink = '<a href="classroom.php?go=view&id='.$resset['classid'].'">'.$resset['title'].'</a>';
					echo '<div class="classProfileTitle">'.$titleLink.'</div>';
					
					echo '<div class="classProfileInfo">'.$resset['nickname'].' , a '.$resset['type'].' classroom for '.	$resset['level'].' students';
					echo '</div>';							
					echo '<div class="classProfileInfo">';
					echo ' running since '.$resset['regtime'].' '.$resset['members'].' students</div>';
					
					echo '<div class="classProfileDesc">'.$resset['description'].'</div>';
					echo '</div></div>';
				}
			}
		}
}


			 /*<!--form action="search.php" method="GET">
			 		Keywords : <input type="text" class="createInputLong" name="keyword" /> <br /> <br />
			 		Search for: <input type="radio" name="what" value="people" /> People
			 						<input type="radio" name="what" value="classrooms" /> Classrooms<br />
			 		If classrooms, <select name="category">
                  	<option selected="selected" disabled="disabled">Choose a category</div>
	<button type="submit" class="ovButton">Search</button>
</form-->
*/
function showPeopleSearchMethods(){
?>
<form action="search.php" method="get">
<input type="hidden" name="what" value="friends" />
	
	<div class="searchMenuLeftList">
		Name 
		<input type="text" name="name" class="createInput" />
	</div>
	
	<div class="searchMenuLeftList">
		Email
		<input type="text" name="email"  class="createInput" />
	</div>
	<div class="searchMenuLeftList"><label>
		Age 
		<select name="age" class="bDay">
			<option value="200">upto 12</option>
			<option value="199">12 - 21</option>
			<option value="198">22 - 31</option>
			<option value="197">32 - 41</option>
			<option value="196">42 - 52</option>
			<option value="195">52 - 61</option>
			<option value="194">62 - 71</option>
		</select> years old </label>
	</div>
	<div class="ovMenuLeftList">
		<button type="submit" class="ovButton">Search</button>
	</div>
</form>
<?php
}

function showClassSearchMethods(){
		?>
	<form action="search.php" method="GET">
	<input type="hidden" name="what" value="classrooms" />
		<div class="searchMenuLeftList">
			Privacy
			<select name="privacy" class="bDay">
				<option value="Public">Public only</option>
				<option value="Protected">Protected only</option>
				<option value="B0TH">Both types</option>
			</select>
		</div><div class="searchMenuLeftList">
			Level
			<select name="level">
				<option value="Pri">
				  Primary Level
				</option>
				<option value="Lo">
				  Lower Secondary
				</option>
				<option value="Se">
				  Secondary Level
				</option>
				<option value="Hi">
				  Higher Secondary
				</option>
				<option value="Un">
				  Undergraduate
				</option>
				<option value="Gr">
				  Graduate
				</option>
				<option value="Po">
				  Post Graduate
				</option>
				<option value="F0R">
				  All Formal
				</option>
				<option value="Tr">
				  Training class
				</option>
				<option value="Ar">
				  Arts or music
				</option>
				<option value="Tu">
				  Tution class
				</option>
				<option value="Sp">
				  Sports or dance
				</option>
				<option value="Al">
				  All informal
				</option>
			</select>
		</div>
		
		<div class="searchMenuLeftList">
			Name
			<input type="text" name="title" class="createInput" />
		</div>
		
		<div class="searchMenuLeftList"><label>
			<input type="checkbox" checked="checked" name="exact" value="1"/>
			Exact match only</label>
		</div>
		<div class="ovMenuLeftList">
			<button type="submit" class="ovButton">Search</button>
		</div>
	 </form>
		<?php	
	}
/*
if(!isset($_REQUEST['keyword'])){
		showSearchForm();
	}
	else ovSearch($_REQUEST['keyword'], 'classrooms');
*/

?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
  <head>
	<title>Online Virtual Classroom</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/template.css" />
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<link rel="stylesheet" type="text/css" href="css/profile.css" />
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

<div class="tcontainer">
	<div class="tpagecontents">
		<div class="trightbody">
			<div class="fieldHeading">
				Friends Search
			</div>
			<?php showPeopleSearchMethods(); ?>
			<div class="fieldHeading">
				Classroom Search
			</div>
			<?php showClassSearchMethods(); ?>
		</div>
		<div class="tmidbody">
			<?php if(ovSearch() == false){
				echo '<div class="classAbout"><h2>';
					echo $toShow;
				echo '</h2></div>';
			}
			?>
		</div>
	</div>
</div>