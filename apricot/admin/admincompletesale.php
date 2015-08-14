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
  $updateSQL = sprintf("UPDATE sale_details SET SALE_STATUS_SD=%s, NOTES_SD=%s, LAST_ACTION_DATE=%s WHERE SALE_DETAILS_ID=%s ",
                       GetSQLValueString('COMPLETED', "text"),
                       GetSQLValueString($_POST['form_maintainence_notes'], "text"),
					   GetSQLValueString($today, "date"),
					   GetSQLValueString($_POST['SALE_DETAILS_ID'], "int"));
//echo $updateSQL;

  $Result1 = mysql_query($updateSQL) or die(mysql_error());
  
  $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString(4, "int"),
					   GetSQLValueString($_POST['PROPERTY_ID'], "int"));
 // echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  
  $updateSQL = sprintf("DELETE from pictures WHERE PROPERTY_ID=%s and TITLE = 'STC'",
					   GetSQLValueString($_POST['PROPERTY_ID'], "int"));
 // echo $updateSQL;

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
		$url = "adminpropertysearch.php?MESSAGE=PROPSALECOMPLETED".$_POST['putonweb'];
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

</head>

<body >
<?php include('top.php')?>
    <div >
   
<div class="orangehead">Complete Sale</div>

<table cellpadding="0" cellspacing="0" border="2px">
<tr>
<td width="100%" valign="top">
<div class="lbbhead">Property Details</div>
<?php include("admin-inc-propdet.php");?>
</td>
</tr>
</table>
<div id="new_client_form_div" >
<div class="phead">Sale Completion Information</div>
<form class="form" action="<?php echo $editFormAction; ?>" method="POST" name="form_maintainence" id="form_maintainence">
<input name="PROPERTY_ID" type="hidden" value="<?php echo $_POST['PROPERTY_ID']; ?>" />
<input name="SALE_DETAILS_ID" type="hidden" value="<?php echo $row_propRS['SALE_DETAILS_ID']; ?>" />


  <fieldset>
  <legend><span class="lbbhead" style="border-bottom:none">Sale Completion Form</span>
  </legend>
  <table align="center" class="form_table" cellpadding="5px" cellspacing="0">
  
 
    <?php if ($row_propRS['STATUS_ID'] == 3 || $row_propRS['STATUS_ID'] == 6 ) {?>
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
    <div class="label">Previously STC logo was placed on the property image.Please select image which will be shown as main image.</div>
    <br />
        <br />
         <?php do { ?>
         <?php if($row_picsRS['TITLE'] != 'STC') {?>
           <div style="float:left"> <img src="<?php echo $row_picsRS['THUMB_PIC_PATH']; ?>" /><br/>
             
             <a href="<?php echo $row_picsRS['ORIGINAL_PIC_PATH']; ?>" target="_blank"><img src="images/search.png" width="24" height="24" align="absmiddle" /></a>
            
             <label>
               <input type="radio" name="main" value="<?php echo $row_picsRS['PICTURE_ID']; ?>" id="RadioGroup1_0" checked="checked"/>
               Set As Main</label>
           </div>
           <?php }  ?>
           <?php } while ($row_picsRS = mysql_fetch_assoc($picsRS)); ?></td>
    </tr>
    <?php }?>
    <tr valign="baseline">

      <td><input  type="submit" class="button" value="Submit" ></td>
    </tr>
  </table>
  </fieldset>

  <input type="hidden" name="MM_update" value="form_maintainence" />
</form>


</div>
</div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($propRS);

mysql_free_result($picsRS);
?>
