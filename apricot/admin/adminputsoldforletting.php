<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Add or Edit Property");
$currentPage = $_SERVER["PHP_SELF"];


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_maintainence")) {
	$today = date("Y-m-d");
  mysql_query("START TRANSACTION");

  $updateSQL = sprintf("UPDATE property_details SET PRICE = %s, PROPERTY_FOR_ID=%s, STATUS_ID=%s, OWNER_ID = %s, RECEIVER_ID = %s WHERE PROPERTY_ID=%s",
					   GetSQLValueString($_POST['price'], "double"),
                       GetSQLValueString(2, "int"),
					   GetSQLValueString(1, "int"),
					   GetSQLValueString($_POST['RECEIVER_ID'], "text"),
					   'NULL',
					   GetSQLValueString($_POST['PROPERTY_ID'], "int"));

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
//  echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  
  $updateSQL = sprintf("DELETE from pictures WHERE PROPERTY_ID=%s and TITLE = 'STC'",
					   GetSQLValueString($_POST['PROPERTY_ID'], "int"));
//  echo $updateSQL;

  $Result3 = mysql_query($updateSQL) or die(mysql_error());
  
  $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString("N", "text"),
                       GetSQLValueString($_POST['PROPERTY_ID'], "int"));

   $Result4 = mysql_query($updateSQL) or die(mysql_error());
   
   $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PICTURE_ID=%s",
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($_POST['main'], "int"));

   $Result5 = mysql_query($updateSQL) or die(mysql_error());
mysql_query("COMMIT");  
  if($Result5){
		$url = "adminpropertysearch.php?MESSAGE=PROPADD".$_POST['putonweb'];
	  header(sprintf("Location: %s", $url));
  }

	
}



$colname_propRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_propRS = $_GET['PROPERTY_ID'];
  $_POST['PROPERTY_ID'] = $_GET['PROPERTY_ID'];
}

$query_propRS = sprintf("SELECT * FROM user_property_owner_receiver_amount_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propRS, "int"));
$propRS = mysql_query($query_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);
$totalRows_propRS = mysql_num_rows($propRS);

if (!((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_maintainence"))) {
	$_POST['form_maintainence_notes'] = $row_propRS['NOTES_SD'];
}

	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">
<!--end of Spry-->
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />

<script src="datepicker/datepicker.js" type="text/javascript"></script>

<link href="datepicker/datepicker.css" rel="stylesheet" type="text/css" />
<script src="js/nicEdit.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


</head>

<body >
<?php include('top.php')?>
    <div >
<div class="orangehead">Adding For Letting</div>

<table cellpadding="0" cellspacing="0" border="2px">
<tr>
<td width="100%" valign="top">
<div class="lbbhead">Property Details</div>
<?php include("admin-inc-propdet.php");?>
</td>

</tr>
</table>
<div id="new_client_form_div" >
<div class="phead">Letting Information</div>
<form class="form" action="<?php echo $editFormAction; ?>" method="POST" name="form_maintainence" id="form_maintainence">
<input name="PROPERTY_ID" type="hidden" value="<?php echo $_POST['PROPERTY_ID']; ?>" />
<input name="SALE_DETAILS_ID" type="hidden" value="<?php echo $row_propRS['SALE_DETAILS_ID']; ?>" />
<input name="OWNER_ID" type="hidden" value="<?php echo $_POST['OWNER_ID']; ?>" />
<input name="RECEIVER_ID" type="hidden" value="<?php echo $row_propRS['RECEIVER_ID']; ?>" />


  <fieldset>
  <legend><span class="lbbhead" style="border-bottom:none">Letting Form</span>
  </legend>
  <table align="center" class="form_table" cellpadding="5px" cellspacing="0">
  
    <tr>
    <td >
    <div class="label">
    1. By clicking submit button property will be <span style="color:#F00">put on website under For Letting</span> category.<br />
    2. This property will be available under For Letting category and will be <span style="color:#F00">removed from Sold category</span>.
    </div>
    
    </td>
    </tr>
<?php 
$colname_picsRS = "-1";
if (isset($_POST['PROPERTY_ID'])) {
  $colname_picsRS = $_POST['PROPERTY_ID'];
}

$query_picsRS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s ORDER BY IS_MAIN DESC", GetSQLValueString($colname_picsRS, "int"));
$picsRS = mysql_query($query_picsRS) or die(mysql_error());
$row_picsRS = mysql_fetch_assoc($picsRS);
$totalRows_picsRS = mysql_num_rows($picsRS);
?>
    <tr>
    <td>
    <div><span id="sprytextfield1">
    <label class="label">Price (Rent  PCM)  * :-<br />
      <input type="text" name="price" value="<?php echo $_POST['price']; ?>" />
    </label>
    <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></div>
    <div class="label"><br />Please select main image.</div>
         <?php do { ?>
         <?php if($row_picsRS['TITLE'] != 'STC') {?>
           <div style="float:left"> <img src="<?php echo $row_picsRS['THUMB_PIC_PATH']; ?>" /><br/>
             
             <a href="<?php echo $row_picsRS['ORIGINAL_PIC_PATH']; ?>" target="_blank"><img src="images/search.png" width="24" height="24" align="absmiddle" /></a>
             <?php if($row_picsRS['IS_MAIN'] == 'N'){?>
                  <label>
                    <input type="radio" name="main" value="<?php echo $row_picsRS['PICTURE_ID']; ?>" id="RadioGroup1_0" />
                  Set As Main</label>
                <?php } else {?>
             <label>
               <input type="radio" name="main" value="<?php echo $row_picsRS['PICTURE_ID']; ?>" disabled="disabled" id="RadioGroup1_0" checked="checked"/>
               Set As Main</label>
                <?php }?>
             <label>
           </div>
           <?php }  ?>
           <?php } while ($row_picsRS = mysql_fetch_assoc($picsRS)); ?></td>
    </tr>

    <tr valign="baseline">

      <td><input  type="submit" class="button" value="Submit" ></td>
    </tr>
  </table>
  </fieldset>

  <input type="hidden" name="MM_update" value="form_maintainence" />
</form>


</div>
    </div>
<script type="text/javascript">
<!--

var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {maxChars:10});
//-->
</script>
<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($propRS);

mysql_free_result($picsRS);
?>
