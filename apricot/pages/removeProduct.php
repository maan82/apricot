<?php require("Connections/adestate.php"); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$sessionID = $_COOKIE['PHPSESSID'];
if(isset($_POST['productIdToRemove']))
{
$productID = $_POST['productIdToRemove'];
$query = "DELETE FROM shortlist WHERE PROPERTY_ID = " . $productID . " AND SESSION_ID = '" . $sessionID . "'";
	mysql_query($query) or die('Error, delete query failed');
	echo 'OK';
}
?>