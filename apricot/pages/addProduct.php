<?php require("Connections/adestate.php"); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$sessionID = $_COOKIE['PHPSESSID'];

if(isset($_POST['productId'])){		
	
	$productID	= $_POST['productId']; 
	$noJavaScript = 0;

	$productInBasket 	= 0;
	$productTotalPrice	= 0;
	
		$query2  = "SELECT COUNT(*) AS totalItems FROM shortlist WHERE SESSION_ID = '" . $sessionID . "' AND PROPERTY_ID = " . $productID;
		$result2 = mysql_query($query2);
		$row2 = mysql_fetch_array( $result2 );
		$totalItems = $row2['totalItems'];

	if($totalItems == 0)
	{
		$query  = "SELECT * FROM property_details_view WHERE PROPERTY_ID = " . $productID;
		$result = mysql_query($query);
		$row = mysql_fetch_array( $result );
	
		$price 		= $row['PRICE'];	
		$city		= $row['CITY'];	
		$beds		= $row['BEDROOMS'];	
		$desc = "";
		if($row['PARENT_TYPE_ID'] == 'Residential'){
				$desc = "<div class=\"offer_info\"><a href=\"property-details.php?FROMPAGE=SHORTLIST&PROPERTY_ID=".$row['PROPERTY_ID']."\"><span>".$row['FOR_SHORT_DESCRIPTION']. "</span>, ".$row['BEDROOMS']." Bed ".$row['TYPE_SHORT_DESCRIPTION']." ".$row['CITY']."<span> &pound;".number_format( $row['PRICE']) ."</span></a></div>";
		} else {
				$desc = "<div class=\"offer_info\"><a href=\"property-details.php?FROMPAGE=SHORTLIST&PROPERTY_ID=".$row['PROPERTY_ID']."\"><span>".$row['FOR_SHORT_DESCRIPTION']. "</span>, ".$row['TYPE_SHORT_DESCRIPTION']." ".$row['CITY']."<span> &pound;".number_format( $row['PRICE'])."</span></a></div>";
			
		}
		$query = "INSERT INTO shortlist (PROPERTY_ID, DETAIL_DESCRIPTION , SESSION_ID) VALUES ('$productID', '$desc', '$sessionID')";
		
		mysql_query($query) or die('Error, insert query failed');	
	
		$query  = "SELECT * FROM shortlist WHERE PROPERTY_ID = " . $productID . " AND SESSION_ID = '" . $sessionID . "'";
		$result = mysql_query($query) or die(mysql_error());;
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			echo $row[PROPERTY_ID]."|||".$row['DETAIL_DESCRIPTION'];
		}
	}
}
?>