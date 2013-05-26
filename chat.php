<?php 

?>
<script type="text/javascript">

{
var prevMinimized = false;
var i, j;
	function changePrev(){
			var prevBox = document.getElementById('chatbar').style;
			
			if(prevMinimized == false){ //maximize
				prevBox.height = "360px";
				prevBox.width = "600px";
				document.getElementById('minBut').innerHTML = " HIDE CHATBOX ";
			}
			else{// minimize
				prevBox.height = "30px";
				prevBox.width = "100px";
				document.getElementById('minBut').innerHTML = " SHOW CHATBOX ";
			}
			
			prevMinimized = !prevMinimized;
	}
}
</script>
	  
<div class="chatbar" id="chatbar">
	<div class="chattitle">
		<span class="minBut">
			NO
		</span>
		<span id="minBut" class="minBut" id="minBut" onclick='changePrev()'> SHOW CHATBOX </span>
		<span class="minBut">
		<select>
			<option value="1">Online
			<option value="2">Offline
			</select>
		</span>
	</div>
	<div class="chatbody">
		<div class="chatarea">
			
		</div>
		
		<div class="onlinelist">
			<ul>Online classmates
			
			</ul>
		</div>
	</div>
</div>