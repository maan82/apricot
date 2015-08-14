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
  $updateSQL = sprintf("UPDATE rent_details SET VACATE_DATE_RD=%s, VACATE_NOTES=%s, RENT_DETAILS_STATUS=%s WHERE RENTED_PROPERTY_ID=%s and RENT_DETAILS_STATUS = 'ACTIVE'",
                       GetSQLValueString($_POST['form_maintainence_startdate'], "date"),
                       GetSQLValueString($_POST['form_maintainence_notes'], "text"),
                       GetSQLValueString('HISTORY', "text"),
					   GetSQLValueString($_POST['PROPERTY_ID'], "int"));
//echo $updateSQL;
  $Result1 = mysql_query($updateSQL) or die(mysql_error());
  $updateSQL = sprintf("UPDATE property_details SET RECEIVER_ID=%s, STATUS_ID=%s WHERE PROPERTY_ID=%s",
                       'NULL',
                       GetSQLValueString($_POST['putonweb'], "int"),
					   GetSQLValueString($_POST['PROPERTY_ID'], "int"));
//echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  if($Result2){
		$url = "adminpropertysearch.php?MESSAGE=VACATE".$_POST['putonweb'];
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
<script>
function gotoUrl(url){
		window.location.href = url;
}

</script>




</head>

<body >
<?php include('top.php')?>
    <div >
   <div class="orangehead">Vacating Property</div>



  


<table cellpadding="0" cellspacing="0" border="2px">
<tr>
<td width="100%" valign="top">
<div class="lbbhead">Property Details</div>
<?php include("admin-inc-propdet.php");?>
</td>
</tr>
</table>



<div id="new_client_form_div" >
<div class="phead">Vacation Information</div>
<?php if ($_GET['MESSAGE'] == 'INSERT'){?>
<div class="actionsuccess">Added new maintainence record.</div>
<?php } else if( $_GET['MESSAGE'] == 'UPDATE'){?>
<div class="actionsuccess">Updated maintainence record.</div>
<?php }?>
<form class="form" action="<?php echo $editFormAction; ?>" method="POST" name="form_maintainence" id="form_maintainence">
<input name="PROPERTY_ID" type="hidden" value="<?php echo $_POST['PROPERTY_ID']; ?>" />



  <fieldset>
  <legend><span class="lbbhead" style="border-bottom:none">Vacation Form</span>
  </legend>
  <table align="center" class="form_table" cellpadding="5px" cellspacing="0" style="width:100%">
    <tr >
      <td   class="label">
            
      
   
      


      
      <div >
<div style="width:380px;float:left;padding-left:20px">
  <span style="color:#F00" class="label">Place On Webiste For Letting ?</span><br />
      <p>
        <label>
          <input <?php if (!(strcmp($_POST['putonweb'],"1")) || empty($_POST['putonweb'])) {echo "checked=\"checked\"";} ?> type="radio" name="putonweb" value="1" id="RadioGroup1_1" />
          Put on website </label>
        <br />
        <label>
          <input <?php if (!(strcmp($_POST['putonweb'],"9"))) {echo "checked=\"checked\"";} ?> type="radio" name="putonweb" value="9" id="RadioGroup1_2" />
          Do not put on website</label>
        <br />
      </p>
      <div style="clear:both"></div>
          </div>
</div>

<br />
           

</td>
    </tr>
  
    
    <tr valign="baseline">

      <td><input  type="submit" class="button" value="Submit" ></td>
    </tr>
  </table>
  </fieldset>
  <input type="hidden" name="MM_insert" value="form_maintainence" />
  <input type="hidden" name="MM_update" value="form_maintainence" />
</form>


</div>


   
    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($propRS);
?>
