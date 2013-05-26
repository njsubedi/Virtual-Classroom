<?php
/* file conn.php to establish database connection */

    include "definitions.php";
    $resource = mysql_connect(OV_SERVER, OV_DBUSER, OV_DBPASS) or die("Could not connect to database server ".mysql_error());
    $dblink = mysql_select_db(OV_DBASE)	or die ("Could not find database in server ".mysql_error());
