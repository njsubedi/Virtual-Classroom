<?php
	/* file: browseclassrooms.php
		for: displaying calssrooms by category
	*/
	include_once "session.php";
	include_once "conn.php";
	
	$token = $_GET['q'];
	if(!@eregi('[0-9 a-z A-Z]', $token))
		echo "No results found";
		exit();	
	
	$sql = "SELECT classid, nickname, title, description, adminid, adminname, affiliation, level, members FROM classrooms";
	$sql .= "WHERE title LIKE '$token'% ORDER BY classid DESC LIMIT 0, 50";
	
	$found = mysql_query($sql) or die("Couldn't search classrooms.". mysql_error());
	while($resset = mysql_fetch_array($found)) {
			
			$clId = $resset[0];
			$clNm = $resset[1];
			$clTt = $resset[2];
			$clDc = $resset[3];
			$clAi = $resset[4];
			$clAn = $resset[5];
			$clAf = $resset[6];
			$clLv = $resset[7];
			$clMb = $resset[8];
			
			;
		
		}
?>