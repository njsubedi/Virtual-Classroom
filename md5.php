<?php 
	for($i = 0; $i < 99999 ; $i++){
		echo $i." : ";
		echo md5($i);
		echo "<br/>";
		
	}
	exit();
?>