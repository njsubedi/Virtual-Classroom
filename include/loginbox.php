<?php 

if($_SERVER['PHP_SELF'] == OV_ROOT.'/index.php') $col = '#006633';
else $col = '#FFFFFF';
	
?>
<div class="loginBoxSmall">
	<form name="indexLoginform" action="login.php" method="post" onsubmit="return validate(this)">
	
	<span class="indexLoginForm">
		<img src="imgs/user.gif" alt="login" />
	</span>
	<span class="indexLoginForm">
			<span style="font-weight:bold; color:<?php echo $col; ?>">Username</span>&nbsp;&nbsp;&nbsp;
			<br />
			<input id="loginname" name="username" class="indexLoginInput" type="text" size="12" />
	</span>
	
	<span class="indexLoginForm">
			<span  style="font-weight:bold; color:<?php echo $col; ?>">Password</span>&nbsp;&nbsp;&nbsp;<a href="forgot.html" title="Click here for assistance"  style=" color:<?php echo $col; ?>">forgot it</a>?
			<br />
			<input id="loginpass" name="password" class="indexLoginInput" type="password" size="12" />
	</span>
	<span class="indexLoginForm">
			<label><span style="cursor:pointer; color:<?php echo $col; ?>">remember me</span><br />
			<input name="remember" value="1" type="checkbox"/>
			</label>
			<input class="ovButton" type="submit" value="Sign in"/>
	</span>
	</form>
</div> <!-- login box ends here  -->
