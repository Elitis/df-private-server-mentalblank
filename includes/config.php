<?php
session_start();
$mysql_host = "localhost";	// MySQL Server IP/Hostname
$mysql_user = "root";		// MySQL Username.
$mysql_pass = "";		// MySQL Password.
$mysql_name = "dragonfable"; 		// MySQL Database name.

mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die(mysql_error());
mysql_select_db($mysql_name) or die(mysql_error());
?>