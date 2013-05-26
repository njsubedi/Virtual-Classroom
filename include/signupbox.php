<?php

	if($_SERVER['PHP_SELF'] == OV_ROOT."/signup.php")
		$signupPage = 'yes';
	else
		$signupPage = false;
		
	
	$errInName	= !empty($errmsg["name"]);
	$errInEmail = !empty($errmsg["email"]);
	$errInUser	= !empty($errmsg["user"]);
	$errInPass	= !empty($errmsg["pass"]);
	$errInDob	= !empty($errmsg["dob"]);
	$errInGender= !empty($errmsg["gender"]);
	$errInTos	= !empty($errmsg["tos"]);
					
?>
<div class="signupForm"><!-- Registration form starts here -->
	<form name="signupform" action="signup.php" method="post" onsubmit="return validate(this)">
	<input type="hidden" name="registering" value="1" />
		<table cellpadding="0" cellspacing="2">
			<tr>
				<td>
				<?php  
						if($signupPage && $errInName)
						{
							// echo '<img src="imgs/what.png" onmouseover="ovhelp(\'name\')" />';
							echo '<span class="errfield">'.$errmsg["name"].'</span>';
						}
						else
						{
							echo "Firstname"; 
						}
					?>
				</td>
				<td>
				<?php 
						if($signupPage && $errInName)
						{
							echo '<span class="errfield">Last name</span>';
						}
						else
						{
							echo "Lastname"; 
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
				<input id="signupname" class="signupinput" type="text" name="upNamef" maxlength="50" value="<?php if($signupPage) echo $_SESSION['step1fn']; ?>"/>&nbsp;&nbsp;&nbsp;</td>
				<td><input id="signupname" class="signupinput" type="text" name="upNamel" maxlength="50" value="<?php if($signupPage) echo $_SESSION['step1ln']; ?>"/></td>
			</tr>
			<tr>
				<td class="errfield"><br /></td>
			</tr>	
			<tr>
				<td>
				<?php 
						if($signupPage && $errInEmail)
						{
							echo '<span class="errfield">'.$errmsg["email"].'</span>';
						}
						else
						{
							echo "Valid email";
						}
				?>
				
				</td>
				<td>
				<?php 
						if($signupPage && $errInUser)
						{
							echo '<span class="errfield">'.$errmsg["user"].'</span>';
						}
						else
						{
							echo "Choose username"; 
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
				<input id="signupemail" class="signupinput" type="text" name="upEmail" maxlength="80" value="<?php if($signupPage) echo $_SESSION['step1em']; ?>"/></td>
				<td><input id="signupemail" class="signupinput" type="text" name="upUser" maxlength="80" value="<?php if($signupPage) echo $_SESSION['step1ur']; ?>"/></td>
			</tr>
			<tr>
				<td><br /></td>
			</tr>
				<td>
					<?php
						if($signupPage && $errInPass)
						{
							echo '<span class="errfield">'.$errmsg["pass"].'</span>';
						}
						else
						{
							echo "New password"; 
						}
					?>
				</td>
				<td>
					<?php 
						if($signupPage && $errInPass)
						{
							echo '<span class="errfield">Repeat the new password.</span>';
						}
						else
						{
							echo "Confirm new password."; 
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
				<input id="signuppass" class="signupinput" type="password" name="upPass1" maxlength="40"/></td>
				<td><input id="signuprepeat" class="signupinput" type="password" name="upPass2" maxlength="40"/></td>
			</tr>
			<tr>
				<td> <br /></td>
			</tr>
			<tr>
				<td>
				<?php  
						if($signupPage && $errInDob)
						{
							echo '<span class="errfield">'.$errmsg["dob"].'</span>';
						}
						else
						{
							echo "Date of birth"; 
						}
					?>
				</td>
				<td>
				<?php 
						if($signupPage && $errInGender)
						{
							echo '<span class="errfield">'.$errmsg["gender"].'</span>';
						}
						else
						{
							echo "Gender"; 
						}
					?>
				</td>
			</tr>
			<tr>
			<td>
				<select id="signupmonth" class="bDay" name="upBmonth">
					<option selected="selected">Month </option>
					<option value="1">Jan </option>
					<option value="2">Feb </option>
					<option value="3">Mar </option>
					<option value="4">Apr </option>
					<option value="5">May</option>
					<option value="6">Jun </option>
					<option value="7">Jul </option>
					<option value="8">Aug </option>
					<option value="9">Sep </option>
					<option value="10">Oct </option>
					<option value="11">Nov </option>
					<option value="12">Dec </option>
				</select>
				
				<select id="signupday" class="bDay" name="upBday">
					<option selected="selected">Day </option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="13">13</option>
					<option value="14">14</option>
					<option value="15">15</option>
					<option value="16">16</option>
					<option value="17">17</option>
					<option value="18">18</option>
					<option value="19">19</option>
					<option value="20">20</option>
					<option value="21">21</option>
					<option value="22">22</option>
					<option value="23">23</option>
					<option value="24">24</option>
					<option value="25">25</option>
					<option value="26">26</option>
					<option value="27">27</option>
					<option value="28">28</option>
					<option value="29">29</option>
					<option value="30">30</option>
					<option value="31">31</option>
				</select>
				
				<select id="signupyear" class="bDay" name="upByear">
					<option selected="selected">Year </option>
					<option value="2009">2009</option>
					<option value="2008">2008</option>
					<option value="2007">2007</option>
					<option value="2006">2006</option>
					<option value="2005">2005</option>
					<option value="2004">2004</option>
					<option value="2003">2003</option>
					<option value="2002">2002</option>
					<option value="2001">2001</option>
					<option value="2000">2000</option>
					<option value="1999">1999</option>
					<option value="1998">1998</option>
					<option value="1997">1997</option>
					<option value="1996">1996</option>
					<option value="1995">1995</option>
					<option value="1994">1994</option>
					<option value="1993">1993</option>
					<option value="1992">1992</option>
					<option value="1991">1991</option>
					<option value="1990">1990</option>
					<option value="1989">1989</option>
					<option value="1988">1988</option>
					<option value="1987">1987</option>
					<option value="1986">1986</option>
					<option value="1985">1985</option>
					<option value="1984">1984</option>
					<option value="1983">1983</option>
					<option value="1982">1982</option>
					<option value="1981">1981</option>
					<option value="1980">1980</option>
					<option value="1979">1979</option>
					<option value="1978">1978</option>
					<option value="1977">1977</option>
					<option value="1976">1976</option>
					<option value="1975">1975</option>
					<option value="1974">1974</option>
					<option value="1973">1973</option>
					<option value="1972">1972</option>
					<option value="1971">1971</option>
					<option value="1970">1970</option>
					<option value="1969">1969</option>
					<option value="1968">1968</option>
					<option value="1967">1967</option>
					<option value="1966">1966</option>
					<option value="1965">1965</option>
					<option value="1964">1964</option>
					<option value="1963">1963</option>
					<option value="1962">1962</option>
					<option value="1961">1961</option>
					<option value="1960">1960</option>
					<option value="1959">1959</option>
					<option value="1958">1958</option>
					<option value="1957">1957</option>
					<option value="1955">1955</option>
					<option value="1954">1954</option>
					<option value="1953">1953</option>
					<option value="1952">1952</option>
					<option value="1951">1951</option>
					<option value="1950">1950</option>
					<option value="1949">1949</option>
					<option value="1948">1948</option>
					<option value="1947">1947</option>
					<option value="1946">1946</option>
					<option value="1945">1945</option>
					<option value="1944">1944</option>
					<option value="1943">1943</option>
					<option value="1942">1942</option>
					<option value="1941">1941</option>
					<option value="1940">1940</option>
					<option value="1939">1939</option>
					<option value="1938">1938</option>
					<option value="1937">1937</option>
					<option value="1936">1936</option>
					<option value="1935">1935</option>
					<option value="1934">1934</option>
					<option value="1933">1933</option>
					<option value="1932">1932</option>
					<option value="1931">1931</option>
					<option value="1930">1930</option>
				</select>
			</td>
			<td>
				<select  name="upGender" class="bDay">
						<option id="signupgenderm" value="1"/> Male </option>
						<option id="signupgenderf" value="1"/> Female </option>
						<option id="signupgendero" selected="selected" /> Select one </option>
				</select>
			</td>
		</tr>
		<tr>
			<td><br /></td>
		</tr>
		<?php
			if($signupPage && $errInTos)
			{
				echo '<tr><td colspan="2" align="left" class="errfield">';
				echo '<img src="imgs/what.png" onmouseover="ovhelp(\'name\')" />';
				echo $errmsg["tos"];
				echo '</td></tr>';
			}
		?>
		<tr>
			<td colspan="2" align="right" class="darkTable">
				<label>
					<input id="signuptos" name="upTOS" type="checkbox" value="1" />
					I accept the 
					<a href="tos.php" target="_blank">Terms of Services</a> of Ovclass.
				</label>
				<button class="ovButton" type="submit">Register</button>
			</td>
		</tr>
	</table>
	</form>
</div><!-- registration formfield ends here -->