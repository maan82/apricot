<?php include("constants.php");?>
<?php require_once('Connections/adestate.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_arrangeViewing") ) {
	
	$error = array();//Error messages array

	if(empty($_POST['form_arrangeViewing_HOME_NO']) 
					&& empty($_POST['form_arrangeViewing_MOB_NO']) 
					&& empty($_POST['form_arrangeViewing_WORK_NO']) ){

		$error['form_arrangeViewing_PH_NO'] = 'Required';
	} 
	include_once("captcha/securimage.php");
	$securimage = new Securimage();
	if ($securimage->check($_POST['form_arrangeViewing_captcha_code']) == false) {
		$error['CAPTCHA'] = 'CAPTCHA';
	}

}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_arrangeViewing") && !$error) {
  $insertSQL = sprintf("INSERT INTO property_view_request (USER_ID, PROPERTY_ID, TITLE, FIRST_NAME, SURNAME, POSTCODE, ADDRESS, EMAIL_ID, HOME_NO, WORK_NO, MOB_NO, PREF_TIME, PREF_NO, MESSAGE) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_SESSION['MM_Username'], "text"),
                       GetSQLValueString($_POST['PROPERTY_ID'], "text"),
					   GetSQLValueString($_POST['form_arrangeViewing_TITLE'], "int"),
                       GetSQLValueString($_POST['form_arrangeViewing_FIRSTNAME'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_SURNAME'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_POSTCODE'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_ADDRESS'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_EMAIL_ID'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_HOME_NO'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_WORK_NO'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_MOB_NO'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_PREF_TIME'], "int"),
                       GetSQLValueString($_POST['form_arrangeViewing_PREF_NO'], "int"),
                       GetSQLValueString($_POST['form_arrangeViewing_MESSAGE'], "text"));


 // echo $insertSQL;
 $Result1 = mysql_query($insertSQL) or die(mysql_error());
 $queryPosted = "Y";

		$query_emailconfRS = "SELECT * FROM email_configuration WHERE TYPE = 'VIEWING_REQUEST'";
		$emailconfRS = mysql_query($query_emailconfRS) or die(mysql_error());
		$row_emailconfRS = mysql_fetch_assoc($emailconfRS);
		$totalRows_emailconfRS = mysql_num_rows($emailconfRS);
 		  $CustomHeaders= '';
 		  $message = new Email($row_emailconfRS['TO'], $row_emailconfRS['FROM'], $row_emailconfRS['SUBJECT'], $CustomHeaders);

		  $text = "\n Property ID :- ".$_POST['PROPERTY_ID'];
		  $text .= "\n-------------- Viewer Information ------------\n";
		  $text .= "\n Title :- ".$_POST['form_arrangeViewing_TITLE'];
		  $text .= "\n Firstname :- ".$_POST['form_arrangeViewing_FIRSTNAME'];
		  $text .= "\n Surname :- ".$_POST['form_arrangeViewing_SURNAME'];	
		  $text .= "\n Postcode :- ".$_POST['form_arrangeViewing_POSTCODE'];	
		  $text .= "\n Address :- ".$_POST['form_arrangeViewing_ADDRESS'];	
		  $text .= "\n Email :- ".$_POST['form_arrangeViewing_EMAIL_ID'];	
		  $text .= "\n Home Contact No. :- ".$_POST['form_arrangeViewing_HOME_NO'];
		  $text .= "\n Work No. :- ".$_POST['form_arrangeViewing_WORK_NO'];
		  $text .= "\n Mobile No. :- ".$_POST['form_arrangeViewing_MOB_NO'];
  		  $text .= "\n Message :- ".$_POST['form_arrangeViewing_MESSAGE'];

		  $message->SetTextContent($text);
		  $message->Send();

 
}
else
{

	if (isset($_GET['PROPERTY_ID'])) {
 		 $_POST['PROPERTY_ID'] = $_GET['PROPERTY_ID'];
	}



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


$freeRS = "Y";

}
$colname_Image_List_RS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_Image_List_RS = $_GET['PROPERTY_ID'];
}else if (isset($_POST['PROPERTY_ID'])) {
  $colname_Image_List_RS = $_POST['PROPERTY_ID'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="bg_blocks.css" />


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
<body class="block">

<div class="block" >
  <div class="box_title">Request For Viewing</div>
<form class="formcenter" action="<?php echo $editFormAction; ?>" method="POST" name="form_arrangeViewing" id="form_arrangeViewing">



<?php  if(isset($Result1)){?>

Thanks for your interest.we shall be in tocuh with you shortly.
    <script>
		parent.showViewingSuccess();
	</script>

<?php } else {   ?>
  

  

  
    <div style="border:1px solid #999;padding:5px;text-align:justify;margin-bottom:10px" class="label">
  
Please complete the following form to arrange viewing for this property.
    <?php if( isset($error) && $error['form_arrangeViewing_PH_NO']  == 'Required'){ ?>
  <div><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">Please provide atleast one contact number.</span></div>
    <?php } if (isset($error) && $error['CAPTCHA']  == 'CAPTCHA'){?>
  <div><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span></div>
    
    <?php }?>


    </div>

  <table align="center" class="form" cellpadding="0" cellspacing="0" width="100%">
    <tr valign="baseline">
      <td  class="label leftcol">Title * :<br />
      <span id="spryselect1_form_arrangeViewing_TITLE">
        <select name="form_arrangeViewing_TITLE">
          <?php
do {  
?>
          <option value="<?php echo $row_Titles_List['TITLE_ID']?>"<?php if (!(strcmp($row_Titles_List['TITLE_ID'], $_POST['form_arrangeViewing_TITLE']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Titles_List['TITLE']?></option>
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
      <span id="sprytextfield1_form_arrangeViewing_FIRSTNAME">
      <input type="text" name="form_arrangeViewing_FIRSTNAME" value="<?php if (isset($_POST['form_arrangeViewing_FIRSTNAME'])) {echo htmlentities($_POST['form_arrangeViewing_FIRSTNAME'],ENT_COMPAT,'UTF-8');} ?>" size="32" /><br />
      <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
      <br />Surname * :<br />
      <span id="sprytextfield2_form_arrangeViewing_SURNAME">  <input name="form_arrangeViewing_SURNAME" type="text" value="<?php if (isset($_POST['form_arrangeViewing_SURNAME'])) {echo htmlentities($_POST['form_arrangeViewing_SURNAME'],ENT_COMPAT,'UTF-8');} ?>" size="32" /><br /><span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
    <br />
    Postcode * :<br />
      <span id="sprytextfield3_form_arrangeViewing_POSTCODE"><input name="form_arrangeViewing_POSTCODE" type="text" value="<?php if (isset($_POST['form_arrangeViewing_POSTCODE'])) {echo htmlentities($_POST['form_arrangeViewing_POSTCODE'],ENT_COMPAT,'UTF-8');} ?>" size="32" /><br /><span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
      <br />
      Address * :<br />
      <span id="sprytextarea1_form_arrangeViewing_ADDRESS">
        <textarea name="form_arrangeViewing_ADDRESS" cols="32" rows="4"><?php if (isset($_POST['form_arrangeViewing_ADDRESS'])) {echo htmlentities($_POST['form_arrangeViewing_ADDRESS'],ENT_COMPAT,'UTF-8');} ?></textarea>
        <span id="countsprytextarea1_form_arrangeViewing_ADDRESS">&nbsp;</span><br /><span class="textareaRequiredMsg">Mandatory.</span><span class="textareaMaxCharsMsg">Exceeded maximum number of characters.</span></span>
        <br />
        Email address * :<br />
        <span id="sprytextfield4_form_arrangeViewing_EMAIL_ID">
      <input name="form_arrangeViewing_EMAIL_ID" type="text" id="form_arrangeViewing_EMAIL_ID" value="<?php if (isset($_POST['form_arrangeViewing_EMAIL_ID'])) {echo htmlentities($_POST['form_arrangeViewing_EMAIL_ID'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
      <br />
      <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
</td>
<td class="label">
    <?php if( isset($error) && $error['form_arrangeViewing_PH_NO']  == 'Required'){ ?>
<span class="label" style="color:#CC3333">Please provide atleast one contact number.</span><br />
    <?php } ?>
Home Number :<br />
      <input type="text" name="form_arrangeViewing_HOME_NO" value="<?php if (isset($_POST['form_arrangeViewing_HOME_NO'])) {echo htmlentities($_POST['form_arrangeViewing_HOME_NO'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
                  
<br /><br />Work Number :<br />
<input value="<?php if (isset($_POST['form_arrangeViewing_WORK_NO'])) {echo htmlentities($_POST['form_arrangeViewing_WORK_NO'],ENT_COMPAT,'UTF-8');} ?>" type="text" name="form_arrangeViewing_WORK_NO" size="32" />
<br /><br />Mobile Number :
<br /><input type="text" name="form_arrangeViewing_MOB_NO" value="<?php if (isset($_POST['form_arrangeViewing_MOB_NO'])) {echo htmlentities($_POST['form_arrangeViewing_MOB_NO'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
<br /><br />Preferred time :
<br /><span id="spryselect3_form_arrangeViewing_PREF_TIME">
        <select name="form_arrangeViewing_PREF_TIME">
          <?php
do {  
?>
          <option value="<?php echo $row_Call_Time_List['CALL_TIME_ID']?>"<?php if (!(strcmp($row_Call_Time_List['CALL_TIME_ID'], $_POST['form_arrangeViewing_PREF_TIME']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Call_Time_List['TIME']?></option>
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
      <br />Preferred phone :
      <br /><span id="spryselect4_form_arrangeViewing_PREF_NO">
        <select name="form_arrangeViewing_PREF_NO">
          <?php
do {  
?>
          <option value="<?php echo $row_Phone_No_List['PHONE_ID']?>"<?php if (!(strcmp($row_Phone_No_List['PHONE_ID'], $_POST['form_arrangeViewing_PREF_NO']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Phone_No_List['PHONE']?></option>
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
        <br />Message :
        <br /><span id="sprytextarea1_form_arrangeViewing_MESSAGE">
        <textarea name="form_arrangeViewing_MESSAGE" cols="32" rows="4"><?php if (isset($_POST['form_arrangeViewing_MESSAGE'])) {echo htmlentities($_POST['form_arrangeViewing_MESSAGE'],ENT_COMPAT,'UTF-8');} ?></textarea>
        <span id="countsprytextarea1_form_arrangeViewing_MESSAGE">&nbsp;</span><br />
        <span class="textareaMaxCharsMsg">Exceeded maximum number of characters.</span></span>
        <br />CAPTCHA Code*:<br />
      <img id="captcha" src="captcha/securimage_show_small_4letters.php" alt="CAPTCHA Image" />
      <a href="#" onclick="document.getElementById('captcha').src = 'captcha/securimage_show_small_4letters.php?' + Math.random(); return false"><img src="captcha/images/refresh.gif" alt="Refresh" width="22" height="20" align="top" /></a>
      <a id="si_aud_ctf1" href="captcha/securimage_play.php" rel="nofollow" title="CAPTCHA Audio"> 
      <img src="captcha/images/audio_icon.gif" alt="CAPTCHA Audio" align="top" style="border-style:none; margin:0; padding:0px; vertical-align:top;" onclick="this.blur();" /></a> 
      <br />
      <label class="label">Please enter code shown in above image.</label><br />
      <span id="sprytextfield1_form_arrangeViewing_captcha_code">
      <input type="text" name="form_arrangeViewing_captcha_code" size="10" maxlength="6" style="width:100px"/>
      <span class="textfieldRequiredMsg">Mandatory.</span></span>
          <?php if( isset($error) && $error['CAPTCHA']  == 'CAPTCHA'){ ?>
<br />  
<span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span>
    <?php } ?>

<br /><input type="submit" name="submit" class="button" value="Submit" />
</td>
    </tr>
  </table>
    <input type="hidden" name="PROPERTY_ID" value="<?php echo $_POST['PROPERTY_ID']?>" />
  <input type="hidden" name="MM_insert" value="form_arrangeViewing" />
</form>
<script type="text/javascript">
<!--
var spryselect1_form_arrangeViewing_TITLE = new Spry.Widget.ValidationSelect("spryselect1_form_arrangeViewing_TITLE", {validateOn:["blur"]});
var sprytextfield1_form_arrangeViewing_FIRSTNAME = new Spry.Widget.ValidationTextField("sprytextfield1_form_arrangeViewing_FIRSTNAME", "none", {maxChars:100, validateOn:["blur"]});
var sprytextfield2_form_arrangeViewing_SURNAME = new Spry.Widget.ValidationTextField("sprytextfield2_form_arrangeViewing_SURNAME", "none", {maxChars:100, validateOn:["blur"]});
var sprytextfield3_form_arrangeViewing_POSTCODE = new Spry.Widget.ValidationTextField("sprytextfield3_form_arrangeViewing_POSTCODE", "none", {maxChars:10, validateOn:["blur"]});
var sprytextarea1_form_arrangeViewing_ADDRESS = new Spry.Widget.ValidationTextarea("sprytextarea1_form_arrangeViewing_ADDRESS", {maxChars:500, counterId:"countsprytextarea1_form_arrangeViewing_ADDRESS", counterType:"chars_count", validateOn:["blur"]});
var sprytextfield4_form_arrangeViewing_EMAIL_ID = new Spry.Widget.ValidationTextField("sprytextfield4_form_arrangeViewing_EMAIL_ID", "email", {maxChars:200, validateOn:["blur"]});
var spryselect3_form_arrangeViewing_PREF_TIME = new Spry.Widget.ValidationSelect("spryselect3_form_arrangeViewing_PREF_TIME", {validateOn:["blur"]});
var spryselect4_form_arrangeViewing_PREF_NO = new Spry.Widget.ValidationSelect("spryselect4_form_arrangeViewing_PREF_NO", {validateOn:["blur"]});
var sprytextarea1_form_arrangeViewing_MESSAGE = new Spry.Widget.ValidationTextarea("sprytextarea1_form_arrangeViewing_MESSAGE", {maxChars:500, counterId:"countsprytextarea1_form_arrangeViewing_MESSAGE", counterType:"chars_count", validateOn:["blur"], isRequired:false});
var sprytextfield1_form_arrangeViewing_captcha_code = new Spry.Widget.ValidationTextField("sprytextfield1_form_arrangeViewing_captcha_code", "none", {validateOn:["blur"]});


//-->
</script>

<?php }?>

</div>


</body>
</html>
<?php if($freeRS == "Y"){

mysql_free_result($Titles_List);

mysql_free_result($Call_Time_List);

mysql_free_result($Phone_No_List);

//mysql_free_result($emailconfRS);
}
?>
