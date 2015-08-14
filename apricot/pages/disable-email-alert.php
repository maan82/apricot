<?php require_once('constants.php'); ?>
<?php require_once('functions.php'); ?>
<?php require_once('Connections/adestate.php'); ?>
<?php
	require_once ('constants.php');
	
	$id = $_GET["id"];
	$token = $_GET["token"];
	if (sha1($id+$SHA_SEED) == $token) {
		$updateSQL = sprintf("UPDATE email_alerts SET ENABLED='0' WHERE EMAIL_ALERT_ID=%s",						
					   GetSQLValueString($_GET['id'], "int"));
		$Result1 = mysql_query($updateSQL) or die(mysql_error());
					   
	    $update = TRUE;  				   
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<link href="bg_blocks.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>

<body >
<?php include('header.php'); ?>
<style>
 strong {
					 font:20px/1.3em tahoma; 
			 border-bottom: 3px solid #F4DD42;
			 margin-bottom: 5px;
	}
</style>

  <div id="maincontent" >
  <div id="column1" >
    <div>
<div class="block">
	<?php if ($update == TRUE) {?>
	Your alert has been disabled.
	<?php } else { echo sha1($_GET['id']+$SHA_SEED) ?>
		
		Invalid token. Please click the link you got in email. 
	<?php } ?> 

</div>
    </div>	
  </div>
<div id="column2" >

    <div class="block">
<div class="heading">Resources For Buyers</div>

    <a href="mortgage-calculator.php" target="_blank" onclick="return showUrl(this.href)" class="largebutton"> Mortgage Calculator</a>
   <a href="buy_reasons.php" class="largebutton"> Why Buy Through Aeon</a>
   <a href="step-by-step-guides.php#buyingguide" class="largebutton">  Guide For Buyers</a>
   <a href="emailalerts.php" class="largebutton">Register For Property Alerts</a>



  </div>
       
     <div class="block">
<div class="heading">Resources For Tenants</div>

   <a href="rent_reasons.php" class="largebutton"> Why Rent Through Aeon</a>
   <a href="step-by-step-guides.php#rentingguide" class="largebutton"> Guide For Renting</a>
   <a href="emailalerts.php" class="largebutton"> Register For Property Alerts</a>
  </div>





<div class="block">
<div class="heading">Resources For Sellers</div>

  <a href="valuation.php" class="largebutton"> Free Valuation Request</a>
   <a href="step-by-step-guides.php#sellingguide" class="largebutton">Guide For Sellers</a>
  </div>

<div class="block">
<div class="heading">Resources For Landlords</div>
  <a href="valuation.php" class="largebutton"> Free Valuation Request </a>
   <a href="step-by-step-guides.php#lettingguide" class="largebutton"> Guide For Landlords</a>
  </div>
  

</div>

</div>
<?php include('new-footer.php'); ?>



</body>
</html>