<?php
    if( file_exists("include/definitions.php") ){
       // do nothing
    }
    else if ( isset( $_POST['installing'] )){
        $dbHost = trim($_POST['dbhost']);
        $dbUser = trim($_POST['dbuser']);
        $dbPass = trim($_POST['dbpass']);
        $dbName = trim($_POST['dbname']);
        $ovRoot = trim($_POST['ovroot']);
        
        if( !$dbHost || !$dbUser || !$dbName || !$ovRoot ){
            echo "<h1>FILL UP ALL THE FIELDS.</h1>";
            exit;
        }

        $conn = mysql_connect($dbHost, $dbUser, $dbPass) or die("<h1>ERROR</h1>Make sure the host, username and password are correct.");
        
        mysql_query("CREATE DATABASE IF NOT EXISTS $dbName;") or die("Cannot create database");
        
        $db = mysql_select_db($dbName) or die("<h1>ERROR</h1>Make sure the database $dbName exists and available to user $dbUser.");

        $sql = "
        
        CREATE TABLE IF NOT EXISTS `assignmentinfo` (
          `assignment` bigint(12) DEFAULT NULL,
          `user` bigint(14) DEFAULT NULL,
          `type` tinyint(1) DEFAULT NULL,
          `contents` text,
          `regtime` int(10) DEFAULT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

        CREATE TABLE IF NOT EXISTS `assignments` (
          `id` bigint(12) NOT NULL AUTO_INCREMENT,
          `classid` bigint(12) DEFAULT NULL,
          `topic` varchar(200) DEFAULT NULL,
          `description` text,
          `regtime` int(10) DEFAULT NULL,
          `duedate` int(10) DEFAULT NULL,
          `type` tinyint(1) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;


        CREATE TABLE IF NOT EXISTS `attendance` (
          `at_year` int(4) DEFAULT NULL,
          `at_month` tinyint(2) DEFAULT NULL,
          `at_day` tinyint(2) DEFAULT NULL,
          `classid` bigint(12) DEFAULT NULL,
          `userid` bigint(14) DEFAULT NULL,
          `presence` tinyint(1) DEFAULT '0'
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


        CREATE TABLE IF NOT EXISTS `autograph` (
          `userid` bigint(14) DEFAULT NULL,
          `uialtname` varchar(50) DEFAULT NULL,
          `uladdr1` varchar(100) DEFAULT NULL,
          `uladdr2` varchar(100) DEFAULT NULL,
          `uladdr3` varchar(100) DEFAULT NULL,
          `ulcountry` varchar(50) DEFAULT NULL,
          `ufhobby` tinytext,
          `ufmovie` tinytext,
          `ufmusic` tinytext,
          `ufbook` tinytext,
          `ufsport` tinytext,
          `ufactor` tinytext,
          `ufplace` tinytext,
          `ufcolor` varchar(50) DEFAULT NULL,
          `uffood` tinytext,
          `ufdress` tinytext,
          `umhappy` tinytext,
          `umsad` tinytext,
          `umbest` tinytext,
          `umdream` tinytext,
          `uvquote` tinytext,
          `uvabout` tinytext,
          `uvreligion` tinytext,
          `uvbio` tinytext,
          `ueschool` tinytext,
          `uescourse` tinytext,
          `uehighschool` tinytext,
          `uehcourse` tinytext,
          `ueuniversity` tinytext,
          `ueucourse` tinytext,
          `ustlastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          `ustlastip` varchar(15) DEFAULT NULL,
          `ustlastact` tinytext,
          `ustverified` varchar(32) DEFAULT 'unverified',
          `ustonline` tinyint(1) DEFAULT NULL,
          `ustprivacy` tinyint(2) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


        CREATE TABLE IF NOT EXISTS `blog` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `userid` bigint(14) DEFAULT NULL,
          `topic` varchar(200) DEFAULT NULL,
          `content` text,
          `regtime` int(10) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1333 ;


        CREATE TABLE IF NOT EXISTS `chat` (
          `msgid` bigint(14) NOT NULL AUTO_INCREMENT,
          `msgto` bigint(14) DEFAULT NULL,
          `msgfrom` bigint(14) DEFAULT NULL,
          `msgtime` int(10) DEFAULT NULL,
          `msgseen` tinyint(1) DEFAULT NULL,
          PRIMARY KEY (`msgid`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;


        CREATE TABLE IF NOT EXISTS `classrooms` (
          `classid` bigint(12) NOT NULL AUTO_INCREMENT,
          `coursecode` bigint(12) NOT NULL,
          `nickname` varchar(50) DEFAULT NULL,
          `title` varchar(400) DEFAULT NULL,
          `description` tinytext,
          `adminid` bigint(14) DEFAULT NULL,
          `adminname` varchar(100) DEFAULT NULL,
          `regtime` date NOT NULL,
          `addr1` varchar(100) DEFAULT NULL,
          `addr2` varchar(100) DEFAULT NULL,
          `country` varchar(4) DEFAULT NULL,
          `affiliation` bigint(12) DEFAULT NULL,
          `level` varchar(50) DEFAULT NULL,
          `type` varchar(30) DEFAULT NULL,
          `members` int(5) DEFAULT NULL,
          PRIMARY KEY (`classid`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1000044 ;


        CREATE TABLE IF NOT EXISTS `dropbox` (
          `id` bigint(12) NOT NULL AUTO_INCREMENT,
          `source` bigint(14) DEFAULT NULL,
          `target` bigint(14) DEFAULT NULL,
          `regtime` int(10) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

       

        CREATE TABLE IF NOT EXISTS `events` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `classid` bigint(12) DEFAULT NULL,
          `topic` varchar(200) DEFAULT NULL,
          `description` text,
          `regtime` varchar(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


        CREATE TABLE IF NOT EXISTS `feedback` (
          `id` int(10) NOT NULL AUTO_INCREMENT,
          `classid` bigint(12) DEFAULT NULL,
          `userid` bigint(14) DEFAULT NULL,
          `subject` varchar(200) DEFAULT NULL,
          `content` text,
          `regtime` int(10) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


        CREATE TABLE IF NOT EXISTS `friends` (
          `myself` bigint(14) DEFAULT NULL,
          `friend` bigint(14) DEFAULT NULL,
          `type` varchar(1) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

       
        CREATE TABLE IF NOT EXISTS `interaction` (
          `postid` varchar(14) DEFAULT NULL,
          `authorid` bigint(14) DEFAULT NULL,
          `type` varchar(5) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        

        CREATE TABLE IF NOT EXISTS `membership` (
          `userid` bigint(14) DEFAULT NULL,
          `classid` bigint(12) DEFAULT NULL,
          `adminid` bigint(14) DEFAULT NULL,
          `regtime` int(10) DEFAULT NULL,
          `type` varchar(1) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

       

        CREATE TABLE IF NOT EXISTS `notices` (
          `id` bigint(12) NOT NULL AUTO_INCREMENT,
          `classid` bigint(12) DEFAULT NULL,
          `topic` varchar(200) DEFAULT NULL,
          `description` text,
          `regtime` int(10) NOT NULL,
          `validity` int(10) DEFAULT NULL,
          `urgency` tinyint(1) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

       

        CREATE TABLE IF NOT EXISTS `notifications` (
          `id` bigint(12) NOT NULL AUTO_INCREMENT,
          `userid` bigint(14) DEFAULT NULL,
          `classid` bigint(12) DEFAULT NULL,
          `content` tinytext,
          `regtime` int(10) DEFAULT NULL,
          `byuser` bigint(14) DEFAULT NULL,
          `byclass` bigint(12) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


        CREATE TABLE IF NOT EXISTS `ovpolls` (
          `pollid` bigint(12) NOT NULL AUTO_INCREMENT,
          `classid` bigint(12) DEFAULT NULL,
          `question` varchar(200) DEFAULT NULL,
          PRIMARY KEY (`pollid`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;


        CREATE TABLE IF NOT EXISTS `ovvotes` (
          `pollid` bigint(12) DEFAULT NULL,
          `userid` bigint(12) DEFAULT NULL,
          `optnum` tinyint(1) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

        

        CREATE TABLE IF NOT EXISTS `polloptions` (
          `value` varchar(200) DEFAULT NULL,
          `pollid` bigint(12) DEFAULT NULL,
          `optnum` tinyint(1) NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

        

        CREATE TABLE IF NOT EXISTS `posts` (
          `postid` bigint(14) NOT NULL AUTO_INCREMENT,
          `classid` varchar(14) DEFAULT NULL,
          `category` varchar(10) DEFAULT NULL,
          `authorid` bigint(14) DEFAULT NULL,
          `authorname` varchar(100) DEFAULT NULL,
          `content` tinytext,
          `regtime` int(10) NOT NULL,
          PRIMARY KEY (`postid`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=72 ;

        

        CREATE TABLE IF NOT EXISTS `reactions` (
          `postid` varchar(14) DEFAULT NULL,
          `authorid` bigint(14) DEFAULT NULL,
          `type` varchar(5) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


        CREATE TABLE IF NOT EXISTS `replies` (
          `replyid` bigint(14) NOT NULL AUTO_INCREMENT,
          `postid` varchar(14) DEFAULT NULL,
          `authorid` bigint(14) DEFAULT NULL,
          `authorname` varchar(100) DEFAULT NULL,
          `content` tinytext,
          `regtime` int(10) NOT NULL,
          PRIMARY KEY (`replyid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

        

        CREATE TABLE IF NOT EXISTS `sessions` (
          `token` varchar(32) DEFAULT NULL,
          `userid` bigint(14) DEFAULT NULL,
          `regtime` int(10) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;


        CREATE TABLE IF NOT EXISTS `stats` (
          `classid` bigint(12) DEFAULT NULL,
          `users` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        
        CREATE TABLE IF NOT EXISTS `userinfo` (
          `userid` bigint(14) NOT NULL AUTO_INCREMENT,
          `classid` bigint(12) DEFAULT '1',
          `username` varchar(40) DEFAULT NULL,
          `email` varchar(80) DEFAULT NULL,
          `pass` varchar(32) DEFAULT NULL,
          `regtime` int(10) NOT NULL,
          `regip` varchar(14) DEFAULT NULL,
          `firstname` varchar(40) DEFAULT NULL,
          `lastname` varchar(40) DEFAULT NULL,
          `gender` varchar(6) DEFAULT NULL,
          `birthday` date DEFAULT NULL,
          `token` varchar(32) DEFAULT NULL,
          `picture` varchar(100) DEFAULT NULL,
          `verification` varchar(32) DEFAULT NULL,
          PRIMARY KEY (`userid`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10000000000058
        
        ";
        $qrys = explode(";", $sql);
        foreach( $qrys as $sql ){
            mysql_query($sql) or die("Error creating tables because: ". mysql_error());
        }

        $file = fOpen("include/definitions.php", "w+");

        fputs( $file, "
        <?php\n
        define(\"OV_SERVER\", \"{$dbHost}\");\n
        define(\"OV_DBUSER\", \"{$dbUser}\");\n
        define(\"OV_DBPASS\", \"{$dbPass}\");\n
        define(\"OV_DBASE\", \"{$dbName}\");\n
        define(\"OV_ROOT\", \"/{$ovRoot}\");\n
        ?>\n");

        fclose($file);
        echo "Ovclass is successfully installed!";
        echo "Click <a href='index.php'>HERE</a> to create a new account.";
    }
    else {
    ?>
        <h1>Installing OVCLASS</h1>
        <form action="install.php" method="post">
        
        <input type="hidden" name="installing" />
        <table border="0"><tr><td>
            Database Host: </td><td><input type="text" name="dbhost" /></tr>
            <tr><td>Database User: </td><td><input type="text" name="dbuser" /></tr>
            <tr><td>Database Pass: </td><td><input type="text" name="dbpass" /></tr>
            <tr><td>Database Name: </td><td><input type="text" name="dbname" /></tr>
            <tr><td>Root Folder: </td><td><input type="text" name="ovroot" /> eg: ( ovclassnew, ov, ovclass [no slashes] )</td></tr>
            <tr><td>Everything is ok.</td><td><input type="submit" value="INSTALL" /></td></tr>
        </table>
        </form>
    <?php
    }

?>
