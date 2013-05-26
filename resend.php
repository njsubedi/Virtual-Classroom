<?php
	include_once "include/session.php";
	include_once "include/conn.php";
	
	
	$tmp = md5(microtime()."resend");
	$user = @$_REQUEST['username'];
	
	if(mysql_fetch_assoc($res = mysql_query("SELECT * FROM userinfo WHERE username='$user';")))
	{
		mysql_query("UPDATE userinfo SET verification = '$tmp' WHERE username = '$user';") or die("Cannot update altpass: ".mysql_error());
		
		$file = fopen("tokens/".$user.".txt", "w");
		fwrite($file, "Your Temporary Password is: \n\n".$tmp);
		fclose($file);
		
		$toShow ='<div style="padding: 20px; color: #006666; background-color: #F5F5F5; border: 1px solid #CCCCCC;">';
		$toShow .= '<img src="imgs/verified.png" />';
		$toShow .='Success! Your new password is at ';
		$toShow .= '<a href="tokens/'.$user.'.txt" target="_blank">';
		$toShow .= '<b>tokens/'.$user.'.txt';
		$toShow .= '</b></a>';
		$toShow .='<hr color="#CCCCCC" size="1" /><br /><br />';
		$toShow .= '<div class="infobox">';
		$toShow .= 'You can now use your temporary password.</div>';
		$toShow .= '</div>';
	}
	else
	{
		$toShow ='<div style="padding: 20px; color: #006666; background-color: #F5F5F5; border: 1px solid #CCCCCC;">';
		$toShow .='<div class="errbox">Sorry, the username is not valid.</div>';
		$toShow .= '<div class="loginForm">
				<form action="resend.php" method="post" >
				Enter Your Username <br /><input name="username" type="text" class="loginInput" >
				<input type="submit" value="Reset Password" class="ovButton"/>
				</form>
			</div>';
		
		$toShow .= '</div>';
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
  <head>
	<title>Template Page for ovclass</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css" />
	<link rel="stylesheet" type="text/css" href="css/template.css" />
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<link rel="stylesheet" type="text/css" href="css/classes.css" />
	<link rel="stylesheet" type="text/css" href="css/search.css" />
  </head>
<body>

<div class="ttopbar">
	<div class="ttitlebar">
			<div class="ttopleft">
				<a href="index.php" alt="OVCLASS" title="OVCLASS"><img src="imgs/ovclasslogodark.png" width="150px" height="55px"/></a>
			</div>
			
		</div><!-- ttitle bar: (common for all) ends here -->
</div><!-- ttopbar ends here, fortunately ! :) -->

<div class="tpagetitle">
	<div class="tpagetitleflow" style="padding:5px">
		Reset Your OVclass Password
	</div>
</div>

<div class="tcontainer">
	<div class="tpagecontents">
		<div class="loginBoxBig">
			<div style="font-size: 12pt; font-weight: bold; padding: 5px; color: #006666; border: 1px solid #CCCCCC">
				<img src="imgs/user.gif" /> Reset your password
			</div>
			<div class="loginForm">
				<?php echo $toShow; ?>
			</div>
			
		</div>
		
	</div>	
	
	<div class="bottombar">
	Virtual Classroom is an open source project. Source code available at: http://ovclass.sourceforge.net
	</div>
	
</div><!-- div closes here : </container > -->
</body>
</html>