<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
ini_set("memory_limit","128M");
$hostname_adestate = "localhost";
$database_adestate = "aeon";
$username_adestate = "root";
$password_adestate = "data1234";
$adestate = mysql_connect($hostname_adestate, $username_adestate, $password_adestate) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_adestate, $adestate);
?>
