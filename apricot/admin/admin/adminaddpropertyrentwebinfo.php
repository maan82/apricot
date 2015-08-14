<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Add or Edit Property");

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_rentdetails")) {
	
	if($_POST["salestatus"] == 'RENTED'){

	      $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString(5, "int"),
								   GetSQLValueString($_POST['PROPERTY_ID'], "int"));

		  $mainRS = mysql_query($updateSQL) or die(mysql_error());
		  $_GET['PROPERTY_ID'] = $_POST['PROPERTY_ID'];
		    $insertGoTo = "adminpropertysearch.php?MESSAGE=PROPRENTED";
			  header(sprintf("Location: %s", $insertGoTo));

	} 
	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">

<script type="text/javascript">
function submitForm(formID){
	document.getElementById(formID).submit();
}

</script>


</head>

<body >
<?php include('top.php')?>
    <div >
   
<div class="orangehead">Renting Property</div>




<div id="new_client_form_div" >


<form action="<?php echo $editFormAction; ?>" class="form" method="POST" name="form_rentdetails" id="form_rentdetails">
<input name="PROPERTY_ID" type="hidden" value="<?php echo $_GET['PROPERTY_ID'] ?>" />
  <fieldset>
  <legend><span class="lbbhead" style="border-bottom:none">Website Updation Form</span>
  </legend>
  <table align="center" class="form_table" cellpadding="5px" cellspacing="0">
    <tr>
    <td colspan="2" >
      <p><br />
        <br /><br />
          <label class="label">
          <input type="radio" name="salestatus" value="RENTED" id="salestatus_0" checked="checked"/>
          Remove from website now.</label>

        <br /><br />

      </p></td>
    </tr>
    <tr valign="baseline">
      <td  align="right" width="50%">&nbsp;</td>
      <td><input  type="submit" class="button" value="Submit" ></td>
    </tr>
  </table>
  </fieldset>
  <input type="hidden" name="MM_insert" value="form_rentdetails" />
</form>


</div>


   
    </div>

<?php include('bottom.php')?>
</body>
</html>