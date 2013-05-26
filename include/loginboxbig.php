<div class="infobox"><img src="imgs/user.gif"align="absmiddle" />&nbsp;&nbsp;
	<b>OVclass Login</b>
</div>
<?php
	if(isset($_SESSION['badlogin']))
	{
		echo '<div class="errbox"><img src="imgs/error.png">&nbsp;&nbsp;&nbsp;&nbsp;';
		echo $_SESSION['badlogin'];
		echo '</div>';
		unset($_SESSION['badlogin']);
	}
?>	
<div class="loginForm">
	<form name="loginform" action="login.php" method="post" onsubmit="return validate(this)">
	
	<?php 
		if(isset($_REQUEST['redir'])){
			echo "<input type=\"hidden\" name=\"redir\" value=\"".$_REQUEST['redir']."\" />";
		}
	
	?>	
		<br />
		<b>Username</b> or <b>Email</b><br />
			<input id="loginname" name="username" class="loginInput" type="text"
			value = "<?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?>" size="12" />
			<label>
			<input name="remember" value="1" type="checkbox" />&nbsp;<span class="darktext" style="cursor:pointer;" >Remember me</span>
			</label>
			
		<br /><br /><b>Password</b><br />
		<input id="loginpass" name="password" class="loginInput" type="password" size="12" />
		<a class="darktext" href="forgot.html" title="Click here for assistance"><span class="darktext">Forgot Password?</span></a>
		
		<br /><br />
		<input class="ovButton" type="submit" value="Sign in"/>&nbsp;&nbsp;&nbsp;
		or <a href="signup.php?noerr">Click Here</a> to reigster.
		<br />
	</form>
</div>