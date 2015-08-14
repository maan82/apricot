<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php');?>
<?php require_once('class.Email.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_arrangevaluation") ) {
	
	$error = array();//Error messages array

	if(empty($_POST['form_arrangevaluation_HOME_NO']) 
					&& empty($_POST['form_arrangevaluation_MOB_NO']) 
					&& empty($_POST['form_arrangevaluation_WORK_NO']) ){

		$error['form_arrangevaluation_PH_NO'] = 'Required';
	} 
	include_once("captcha/securimage.php");
	$securimage = new Securimage();
	if ($securimage->check($_POST['form_arrangevaluation_captcha_code']) == false) {
		$error['CAPTCHA'] = 'CAPTCHA';
	}

}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_arrangevaluation") && !$error) {
  $insertSQL = sprintf("INSERT INTO property_valuation_request (PROPERTY_ID, TITLE, FIRST_NAME, SURNAME, POSTCODE, ADDRESS, EMAIL_ID, HOME_NO, WORK_NO, MOB_NO, PREF_TIME, PREF_NO, MESSAGE, PROPERTY_FOR_ID, PROPERTY_TYPE_ID, BEDROOMS, BATHROOMS, DRAWING_ROOMS) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['PROPERTY_ID'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_TITLE'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_FIRSTNAME'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_SURNAME'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_POSTCODE'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_ADDRESS'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_EMAIL_ID'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_HOME_NO'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_WORK_NO'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_MOB_NO'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_PREF_TIME'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_PREF_NO'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_MESSAGE'], "text"),
                       GetSQLValueString($_POST['form_arrangevaluation_propfor'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_propertytype'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_beds'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_baths'], "int"),
                       GetSQLValueString($_POST['form_arrangevaluation_drawingrooms'], "int"));


  $Result1 = mysql_query($insertSQL) or die(mysql_error());
  

		$query_emailconfRS = "SELECT * FROM email_configuration WHERE TYPE = 'VALUATION_REQUEST'";
		$emailconfRS = mysql_query($query_emailconfRS) or die(mysql_error());
		$row_emailconfRS = mysql_fetch_assoc($emailconfRS);
		$totalRows_emailconfRS = mysql_num_rows($emailconfRS);
 		  $CustomHeaders= '';
 		  $message = new Email($row_emailconfRS['TO'], $row_emailconfRS['FROM'], $row_emailconfRS['SUBJECT'], $CustomHeaders);

		  $text = "\n Title :- ".$_POST['form_arrangevaluation_TITLE'];
		  $text .= "\n Firstname :- ".$_POST['form_arrangevaluation_FIRSTNAME'];
		  $text .= "\n Surname :- ".$_POST['form_arrangevaluation_SURNAME'];	
		  $text .= "\n Postcode :- ".$_POST['form_arrangevaluation_POSTCODE'];	
		  $text .= "\n Address :- ".$_POST['form_arrangevaluation_ADDRESS'];	
		  $text .= "\n Email :- ".$_POST['form_arrangevaluation_EMAIL_ID'];	
		  $text .= "\n Home Contact No. :- ".$_POST['form_arrangevaluation_HOME_NO'];
		  $text .= "\n Work No. :- ".$_POST['form_arrangevaluation_WORK_NO'];
		  $text .= "\n Mobile No. :- ".$_POST['form_arrangevaluation_MOB_NO'];
  		  $text .= "\n Message :- ".$_POST['form_arrangevaluation_MESSAGE'];

		  $message->SetTextContent($text);
		  $message->Send();

}


$query_Prop_Type_RS = "SELECT * FROM property_type_master";
$Prop_Type_RS = mysql_query($query_Prop_Type_RS) or die(mysql_error());
$row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
$totalRows_Prop_Type_RS = mysql_num_rows($Prop_Type_RS);


$query_propforRS = "SELECT * FROM property_for_master";
$propforRS = mysql_query($query_propforRS) or die(mysql_error());
$row_propforRS = mysql_fetch_assoc($propforRS);
$totalRows_propforRS = mysql_num_rows($propforRS);


$query_Titles_List = "SELECT * FROM title_master";
$Titles_List = mysql_query($query_Titles_List) or die(mysql_error());
$row_Titles_List = mysql_fetch_assoc($Titles_List);
$totalRows_Titles_List = mysql_num_rows($Titles_List);


$query_Call_Time_List = "SELECT * FROM pref_call_time_master";
$Call_Time_List = mysql_query($query_Call_Time_List) or die(mysql_error());
$row_Call_Time_List = mysql_fetch_assoc($Call_Time_List);
$totalRows_Call_Time_List = mysql_num_rows($Call_Time_List);


$query_Phone_No_List = "SELECT * FROM pref_phone_master";
$Phone_No_List = mysql_query($query_Phone_No_List) or die(mysql_error());
$row_Phone_No_List = mysql_fetch_assoc($Phone_No_List);
$totalRows_Phone_No_List = mysql_num_rows($Phone_No_List);
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

<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />

</head>

<body >
<?php include('header.php'); ?>

<div id="maincontent" >
<div id="column1" >

 <div class="block">
<div >
 <div class="heading">Value My Home</div>
<form class="form" action="<?php echo $editFormAction; ?>" method="POST" name="form_arrangevaluation" id="form_arrangevaluation">



<?php  if(isset($Result1)){?>
  <fieldset>
  <legend>
  </legend>

Thanks for your interest.we shall be in tocuh with you shortly.
    </fieldset>
    <script>
		parent.showViewingSuccess();
	</script>

<?php } else {   ?>
  

 
    <div style="border:1px solid #999;padding:5px;text-align:justify;" class="label">
  
Please complete the following form to arrange free, no obligation valuation for your property.
    <?php if( isset($error) && $error['form_arrangevaluation_PH_NO']  == 'Required'){ ?>
  <div><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">Please provide atleast one contact number.</span></div>
    <?php } if (isset($error) && $error['CAPTCHA']  == 'CAPTCHA'){?>
  <div><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span></div>
    
    <?php }?>


    </div>

  <table align="center" class="form_table" cellpadding="5px">
    <tr valign="baseline">
      <td  align="left" width="50%" class="leftcol label " >Title * :<br />
      <span id="spryselect1_form_arrangevaluation_TITLE">
        <select name="form_arrangevaluation_TITLE">
          <?php
do {  
?>
          <option value="<?php echo $row_Titles_List['TITLE_ID']?>"<?php if (!(strcmp($row_Titles_List['TITLE_ID'], $_POST['form_arrangevaluation_TITLE']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Titles_List['TITLE']?></option>
          <?php
} while ($row_Titles_List = mysql_fetch_assoc($Titles_List));
  $rows = mysql_num_rows($Titles_List);
  if($rows > 0) {
      mysql_data_seek($Titles_List, 0);
	  $row_Titles_List = mysql_fetch_assoc($Titles_List);
  }

?>
        </select><br />
      <span class="selectRequiredMsg">Please select an item.</span></span>
      <br />
      First name * :<br />
      <span id="sprytextfield1_form_arrangevaluation_FIRSTNAME">
      <input type="text" name="form_arrangevaluation_FIRSTNAME" value="<?php if (isset($_POST['form_arrangevaluation_FIRSTNAME'])) {echo htmlentities($_POST['form_arrangevaluation_FIRSTNAME'],ENT_COMPAT,'UTF-8');} ?>" size="32" /><br />
      <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
      <br />
      Surname * :<br />
      <span id="sprytextfield2_form_arrangevaluation_SURNAME">  <input name="form_arrangevaluation_SURNAME" type="text" value="<?php if (isset($_POST['form_arrangevaluation_SURNAME'])) {echo htmlentities($_POST['form_arrangevaluation_SURNAME'],ENT_COMPAT,'UTF-8');} ?>" size="32" /><br /><span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
      <br />
      Postcode * :<br />
      <span id="sprytextfield3_form_arrangevaluation_POSTCODE"><input name="form_arrangevaluation_POSTCODE" type="text" value="<?php if (isset($_POST['form_arrangevaluation_POSTCODE'])) {echo htmlentities($_POST['form_arrangevaluation_POSTCODE'],ENT_COMPAT,'UTF-8');} ?>" size="32" /><br /><span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
      <br />
      Address * :<br />
      <span id="sprytextarea1_form_arrangevaluation_ADDRESS">
        <textarea name="form_arrangevaluation_ADDRESS" cols="32" rows="4"><?php if (isset($_POST['form_arrangevaluation_ADDRESS'])) {echo htmlentities($_POST['form_arrangevaluation_ADDRESS'],ENT_COMPAT,'UTF-8');} ?></textarea>
        <span id="countsprytextarea1_form_arrangevaluation_ADDRESS">&nbsp;</span><br /><span class="textareaRequiredMsg">Mandatory.</span><span class="textareaMaxCharsMsg">Exceeded maximum number of characters.</span></span>
        <br />
        Email address * :<br />
      <span id="sprytextfield4_form_arrangevaluation_EMAIL_ID">
      <input name="form_arrangevaluation_EMAIL_ID" type="text" id="form_arrangevaluation_EMAIL_ID" value="<?php if (isset($_POST['form_arrangevaluation_EMAIL_ID'])) {echo htmlentities($_POST['form_arrangevaluation_EMAIL_ID'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
      <br />
      <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
          <?php if( isset($error) && $error['form_arrangevaluation_PH_NO']  == 'Required'){ ?>
          <br />
<span class="label" style="color:#CC3333">Please provide atleast one contact number.</span>
    <?php } ?>

      <br />
      Home Number :<br />
            <input type="text" name="form_arrangevaluation_HOME_NO" value="<?php if (isset($_POST['form_arrangevaluation_HOME_NO'])) {echo htmlentities($_POST['form_arrangevaluation_HOME_NO'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
            <br />
            Work Number :<br />
      <input value="<?php if (isset($_POST['form_arrangevaluation_WORK_NO'])) {echo htmlentities($_POST['form_arrangevaluation_WORK_NO'],ENT_COMPAT,'UTF-8');} ?>" type="text" name="form_arrangevaluation_WORK_NO" size="32" />
      <br />
      Mobile Number :<br />
      <input type="text" name="form_arrangevaluation_MOB_NO" value="<?php if (isset($_POST['form_arrangevaluation_MOB_NO'])) {echo htmlentities($_POST['form_arrangevaluation_MOB_NO'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
      <br />
      Preferred time :<br />
      <span id="spryselect3_form_arrangevaluation_PREF_TIME">
        <select name="form_arrangevaluation_PREF_TIME">
          <?php
do {  
?>
          <option value="<?php echo $row_Call_Time_List['CALL_TIME_ID']?>"<?php if (!(strcmp($row_Call_Time_List['CALL_TIME_ID'], $_POST['form_arrangevaluation_PREF_TIME']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Call_Time_List['TIME']?></option>
          <?php
} while ($row_Call_Time_List = mysql_fetch_assoc($Call_Time_List));
  $rows = mysql_num_rows($Call_Time_List);
  if($rows > 0) {
      mysql_data_seek($Call_Time_List, 0);
	  $row_Call_Time_List = mysql_fetch_assoc($Call_Time_List);
  }
?>
        </select><br />
      <span class="selectRequiredMsg">Please select an item.</span></span>
      <br />
      Preferred phone :<br />
      <span id="spryselect4_form_arrangevaluation_PREF_NO">
        <select name="form_arrangevaluation_PREF_NO">
          <?php
do {  
?>
          <option value="<?php echo $row_Phone_No_List['PHONE_ID']?>"<?php if (!(strcmp($row_Phone_No_List['PHONE_ID'], $_POST['form_arrangevaluation_PREF_NO']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Phone_No_List['PHONE']?></option>
          <?php
} while ($row_Phone_No_List = mysql_fetch_assoc($Phone_No_List));
  $rows = mysql_num_rows($Phone_No_List);
  if($rows > 0) {
      mysql_data_seek($Phone_No_List, 0);
	  $row_Phone_No_List = mysql_fetch_assoc($Phone_No_List);
  }
?>
          </select><br />
        <span class="selectRequiredMsg">Please select an item.</span></span>
      </td>
      <td class="label rightcol" >
      Property Type :<br />
<select name="form_arrangevaluation_propertytype" >
  <?php
do {  
?>
  <option value="<?php echo $row_Prop_Type_RS['PROPERTY_TYPE_ID']?>"<?php if (!(strcmp($row_Prop_Type_RS['PROPERTY_TYPE_ID'], $_GET['form_arrangevaluation_propertytype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Prop_Type_RS['SHORT_DESCRIPTION']?></option>
  <?php
} while ($row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS));
  $rows = mysql_num_rows($Prop_Type_RS);
  if($rows > 0) {
      mysql_data_seek($Prop_Type_RS, 0);
	  $row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
  }
?>
</select>
<br />
      Property For<br />
<select name="form_arrangevaluation_propfor"  id="form_arrangevaluation_propfor_ID" >
  <?php
do {  
?>
  <option value="<?php echo $row_propforRS['PROPERTY_FOR_ID']?>"<?php if (!(strcmp($row_propforRS['PROPERTY_FOR_ID'], $_POST['form_arrangevaluation_propfor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_propforRS['SHORT_DESCRIPTION']?></option>
  <?php
} while ($row_propforRS = mysql_fetch_assoc($propforRS));
  $rows = mysql_num_rows($propforRS);
  if($rows > 0) {
      mysql_data_seek($propforRS, 0);
	  $row_propforRS = mysql_fetch_assoc($propforRS);
  }
?>
</select>
<br />
      No of Bedrooms:<br />
<select name="form_arrangevaluation_beds">
  <option value="_1" <?php if ((strcmp("_1", $_POST['form_arrangevaluation_beds'])== 0)) {echo "selected=\"selected\"";} ?>>Studio</option>
  <?php
for($i=1;$i<5;$i++){
?>
  <option value="<?php echo $i?>"<?php if ((strcmp($i, $_POST['form_arrangevaluation_beds'])==0)) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
  <?php
}
?>
</select>

      <br />
      No. of Reception Rooms:<br />
<select name="form_arrangevaluation_drawingrooms">
  <?php
for($i=1;$i<5;$i++){
?>
  <option value="<?php echo $i?>"<?php if ((strcmp($i, $_POST['form_arrangevaluation_drawingrooms'])==0)) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
  <?php
}
?>
</select>

<br />
      No. of Bathrooms:<br />
<select name="form_arrangevaluation_baths">
  <?php
for($i=1;$i<5;$i++){
?>
  <option value="<?php echo $i?>"<?php if ((strcmp($i, $_POST['form_arrangevaluation_baths'])==0)) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
  <?php
}
?>
</select>

<br />      Message :<br /><span id="sprytextarea1_form_arrangevaluation_MESSAGE">
        <textarea name="form_arrangevaluation_MESSAGE" cols="32" rows="4"><?php if (isset($_POST['form_arrangevaluation_MESSAGE'])) {echo htmlentities($_POST['form_arrangevaluation_MESSAGE'],ENT_COMPAT,'UTF-8');} ?></textarea>
        <span id="countsprytextarea1_form_arrangevaluation_MESSAGE">&nbsp;</span><br />
        <span class="textareaMaxCharsMsg">Exceeded maximum number of characters.</span></span>
<br />      CAPTCHA Code*:<br />
      <img id="captcha" src="captcha/securimage_show_small_4letters.php" alt="CAPTCHA Image" />
      <a href="#" onclick="document.getElementById('captcha').src = 'captcha/securimage_show_small_4letters.php?' + Math.random(); return false"><img src="captcha/images/refresh.gif" alt="Refresh" width="22" height="20" align="top" /></a>
      <a id="si_aud_ctf1" href="captcha/securimage_play.php" rel="nofollow" title="CAPTCHA Audio"> 
      <img src="captcha/images/audio_icon.gif" alt="CAPTCHA Audio" align="top" style="border-style:none; margin:0; padding:0px; vertical-align:top;" onclick="this.blur();" /></a> 
      <br />
      <label class="label">Please enter code shown in above image.</label><br />
      <span id="sprytextfield1_form_arrangevaluation_captcha_code">
      <input type="text" name="form_arrangevaluation_captcha_code" size="10" maxlength="6" style="width:100px"/><br />  
      <span class="textfieldRequiredMsg">Mandatory.</span></span>
          <?php if( isset($error) && $error['CAPTCHA']  == 'CAPTCHA'){ ?>

<span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span>
    <?php } ?>
<br />
<br />

<input class="button" type="submit" name="submit"  value="Submit" />

      </td>
    </tr>

    <tr valign="baseline">
      <td  align="right">&nbsp;</td>
      <td></td>
    </tr>
  </table>
    <input type="hidden" name="PROPERTY_ID" value="<?php echo $_POST['PROPERTY_ID']?>" />
  <input type="hidden" name="MM_insert" value="form_arrangevaluation" />

</form>
<script type="text/javascript">
<!--
var spryselect1_form_arrangevaluation_TITLE = new Spry.Widget.ValidationSelect("spryselect1_form_arrangevaluation_TITLE", {validateOn:["blur"]});
var sprytextfield1_form_arrangevaluation_FIRSTNAME = new Spry.Widget.ValidationTextField("sprytextfield1_form_arrangevaluation_FIRSTNAME", "none", {maxChars:100, validateOn:["blur"]});
var sprytextfield2_form_arrangevaluation_SURNAME = new Spry.Widget.ValidationTextField("sprytextfield2_form_arrangevaluation_SURNAME", "none", {maxChars:100, validateOn:["blur"]});
var sprytextfield3_form_arrangevaluation_POSTCODE = new Spry.Widget.ValidationTextField("sprytextfield3_form_arrangevaluation_POSTCODE", "none", {maxChars:10, validateOn:["blur"]});
var sprytextarea1_form_arrangevaluation_ADDRESS = new Spry.Widget.ValidationTextarea("sprytextarea1_form_arrangevaluation_ADDRESS", {maxChars:500, counterId:"countsprytextarea1_form_arrangevaluation_ADDRESS", counterType:"chars_count", validateOn:["blur"]});
var sprytextfield4_form_arrangevaluation_EMAIL_ID = new Spry.Widget.ValidationTextField("sprytextfield4_form_arrangevaluation_EMAIL_ID", "email", {maxChars:200, validateOn:["blur"]});
var spryselect3_form_arrangevaluation_PREF_TIME = new Spry.Widget.ValidationSelect("spryselect3_form_arrangevaluation_PREF_TIME", {validateOn:["blur"]});
var spryselect4_form_arrangevaluation_PREF_NO = new Spry.Widget.ValidationSelect("spryselect4_form_arrangevaluation_PREF_NO", {validateOn:["blur"]});
var sprytextarea1_form_arrangevaluation_MESSAGE = new Spry.Widget.ValidationTextarea("sprytextarea1_form_arrangevaluation_MESSAGE", {maxChars:500, counterId:"countsprytextarea1_form_arrangevaluation_MESSAGE", counterType:"chars_count", validateOn:["blur"], isRequired:false});
var sprytextfield1_form_arrangevaluation_captcha_code = new Spry.Widget.ValidationTextField("sprytextfield1_form_arrangevaluation_captcha_code", "none", {validateOn:["blur"]});


//-->
</script>

<?php }?>

</div>


    </div>

</div>
<div id="column2" >

        

</div>
</div>

<?php include('footer.php'); ?>



</body>
</html>