<?php
if(isset($_GET['u']))
	$topic = $_GET['u'];
	
	switch($topic) {
			
			case 'name':
					include "name.html";
				break;
				
			case 'email':
					include "email.html";
				break;
			
			case 'pass':
					include "pass.html";
				break;
				
			case 'user':
					include "user.html";
				break;
				
			case 'tos':
					include "tos.html";
				break;
			
		}
?>