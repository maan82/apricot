<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
ini_set("memory_limit","128M");
$hostname_adestate = "db525602008.db.1and1.com";
$database_adestate = "db525602008";
$username_adestate = "dbo525602008";
$password_adestate = "password";
$adestate = mysql_connect($hostname_adestate, $username_adestate, $password_adestate) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_adestate, $adestate);
?>