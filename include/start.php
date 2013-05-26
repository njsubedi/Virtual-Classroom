<?php
include_once "session.php";

/* file : start.php
	for : including in classroom to start a new classroom
*/
$values = true;
if(empty($_SESSION['start']['newClassCreating']))
	$values = false;
		
?>
<div class="tmidbody">
	<div class="createTitle">Start a new classroom</div>
	
	<div class="createBox">
		<form name="startForm" action="classroom.php?go=save" method="post">
			<input type="hidden" value="save" name="go" />
          
                <div class="createRows">
                  Name<br />
						<input class="createInputLong" type="text" name="classTitle" value="<?php if($values) echo $_SESSION['start']['startTitle']; ?>"/>
                </div>
              
                <div class="createRows">
                 Unique nickname<br />
						<input class="createInput" onBlur="checkClassId(this.value)" type="text" name="classId" value="<?php if($values) echo $_SESSION['start']['startId']; ?>" />
						<span id="classIdCheck" style="font-weight:normal; color: #CC3300;">Check availability</span>
				</div>
              
                <div class="createRows">
				Description<br />
						<textarea class="textareaHolder" name="classDescription" id="ovTr" rows="3" cols="55" onkeyup="sizeBox(this)"><?php
							 if($values) echo $_SESSION['start']['startDesc']; 
						?></textarea>
                </div>
             
                <div class="createRows">
                 Address<br />
						<input class="createInputLong" onBlur="checkClassId(this.value)" type="text" name="classLoc" value="<?php if($values) echo $_SESSION['start']['startLoc']; ?>" />
				</div>
              
				<div class="createRows" colspan="2">
                  <select name="classLevel">
                  	<option selected="selected" disabled="disabled"><b>Choose level</b></option>
                    <option value="1">
                      Primary Level
                    </option>
                    <option value="2">
                      Lower Secondary
                    </option>
                    <option value="3">
                      Secondary
                    </option>
                    <option value="4">
                      Higher Secondary
                    </option>
                    <option value="5">
                      Undergraduate
                    </option>
                    <option value="6">
                      Graduate
                    </option>
                    <option value="8">
                      Post Graduate
                    </option>
                    <option value="9">
                      Others (Formal)
                    </option>
                    <option disabled="disabled">
                      ================
                    </option>
                    <option value="11">
                      Training class
                    </option>
                    <option value="12">
                      Arts or music class
                    </option>
                    <option value="13">
                      Tution class
                    </option>
                    <option value="14">
                      Sports/Dance class
                    </option>
                    <option value="19">
                      Others (Informal)
                    </option>
                  </select>
                &nbsp;&nbsp;
                  <select  name="classPrivacy">
                  	<option selected="selected" disabled="disabled">Choose privacy</option>
                    <option value="1" title="Private classes do not appear in search results.">
                      Private Classroom
                    </option>
                    <option value="2" title="Only members can enter protected classrooms">
                      Protected Classroom
                    </option>
                    <option value="3" title="Public rooms are accessible to everyone">
                      Public Classroom
                    </option>
                  </select>
                </div>

                <div class="createRows">
                  <button type="submit" class="ovButton">Proceed..</button><br />
                </div>
        </form>
	</div>
</div> <!-- closing tag for tmidbody -->


<div class="trightbody">
<?php
if(isset($_SESSION['start']['newClassError'])){ ?>
		<p style="background-color: #CC0000;font-size:16pt; padding:5px; color: #FFFFFF;">
			Oops! We could not proceed..
		</p>
		<p style="padding:5px; background-color: #F5F5F5; line-height: 19px; color: #666666;">
			<?php echo $_SESSION['start']['newClassError']; unset($_SESSION['start']['newClassError']); ?>
		</p>
		
<?php	} else{	?>
	<p style="background-color: #009999;font-size:16pt; padding:5px; color: #FFFFFF;">
		Guidelines to fill up the form
	</p>
	<p style="padding:5px; background-color: #F5F5F5; line-height: 19px; color: #666666;">
		<b style="color:#009999">Title</b>
		<br />
			- Title must be at least 5 to maximum 200 characters.<br />
			- You can use letters and numbers only.<br />
			- Example: <span style="color:red">Kathmandu University CSE 2010 Comp206</span><br/>
		<br />
		<b style="color:#009999">Unique name (nickname) </b>
		<br />
			- This must be 5 to 30 characters long.<br />
			- You can use letters, numbers and symbols . - and _  only. </b><br />
			- Example: <span style="color:red">KU.CSE-2010_comp206</span><br/><br/>
		<b style="color:#009999">Description </b>
		<br/>
			- This must be 5 to 5000 characters long.<br />
			- Describe your classroom in brief so that people can know you.<br />
			- Include an overview of your classroom, motto, etc.<br/>
			
		<br/>
		<b style="color:#009999">Address (location) </b>
		<br/>
			- This will help people invite you to events or contact you.<br />
			- Include your Postal address in one line.
	</p>
<?php }  ?>
</div> <!-- closes tright body -->