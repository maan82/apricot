<?php session_start(); ?>
<?php require_once('Connections/adestate.php'); ?>
<?php require_once('class.Email.php'); ?>
<?php require_once ('constants.php');?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_arrangeViewing")) {
		$error = array();//Error messages array
		include_once("captcha/securimage.php");
		$securimage = new Securimage();
		if ($securimage->check($_POST['form_arrangeViewing_captcha_code']) == false) {
			$error['CAPTCHA'] = 'CAPTCHA';
		}

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_arrangeViewing") && !$error) {
  $insertSQL = sprintf("INSERT INTO tell_a_friend (PROPERTY_ID, EMAIL_ID, CONTACT_NO, MESSAGE) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['PROPERTY_ID'], "int"),
                       GetSQLValueString($_POST['form_arrangeViewing_EMAIL_ID'], "text"),
                       GetSQLValueString($_POST['form_arrangeViewing_HOME_NO'], "int"),
                       GetSQLValueString($_POST['form_arrangeViewing_MESSAGE'], "text"));


  $Result1 = mysql_query($insertSQL) or die(mysql_error());
  
  	if( !$error)
	{
		  $pathToServerFile	= 'tmp/PDF_'.$_POST['PROPERTY_ID'].'.pdf';
		  $contents = file_get_contents('http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"))."/propertybroucher.php?PROPERTY_ID=".$_POST['PROPERTY_ID']);
 
								$fp = fopen($pathToServerFile, "w");
								fwrite($fp, $contents); //write contents of feed to cache file
								fclose($fp);

   		  echo $copied;
		  $Subject = 'Property Details';
		  $CustomHeaders= '';
		  $message = new Email($_POST['form_arrangeViewing_EMAIL_ID'], $EMAIL, $Subject, $CustomHeaders);
		  $text = "Hi \n Your friend has sent you property broucher attached with this email.\n";
		  $text .= " \n ".'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"))."/property-details.php?PROPERTY_ID=".$_POST['PROPERTY_ID'];
		  $text .= "\n --------------Below Is Message From Your Friend ------------\n";
		  $text .= "\n".$_POST['form_arrangeViewing_MESSAGE']."\n";

		  $message->SetTextContent($text);
		  $serverFileMimeType = 'application/pdf';  //** standard word MIME type.
    	  $message->Attach($pathToServerFile, $serverFileMimeType);

		  $message->Send();

  		  $message = new Email("rsmaan4u8@gmail.com,maan82@rediffmail.com", $EMAIL, $Subject, $CustomHeaders);
		  $URL = 'http://'.$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/aeon/pages/email-alert-properties.php?lookingfor=1&price=9000000&name=".$email_alerts_row_queriesRS['NAME'];
        
          $contents = file_get_contents($URL);
		  $message->SetHtmlContent($contents);
		  $message->Send();
		  
		  $CARREER_UPLOAD_SUCCESS = true;

	}

}
?>
<?php
if( isset($_GET['PROPERTY_ID']) ){
	$_POST['PROPERTY_ID'] = $_GET['PROPERTY_ID'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('Connections/adestate.php')?>

<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="bg_blocks.css" />
<!--Googlr Map End-->

<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />

</head>
<body class="block">

<div >
 <div class="box_title">Tell A Friend</div>
<form class="formcenter" action="<?php echo $editFormAction; ?>" method="POST" name="form_arrangeViewing" id="form_arrangeViewing">


<?php if (isset($error)) {?>


<?php } if(isset($Result1)){?>
  <fieldset>
  <legend>
  </legend>

Thanks for your interest.we shall be in tocuh with you shortly.
    </fieldset>
    <script>
		parent.showTellFriendSuccess();
	</script>

<?php } else {   ?>
  

  
 

    <div style="border:1px solid #999;padding:5px;text-align:justify;" class="label">
  
Please use the form below to tell a friend.

    </div>

  <table align="center" class="form" cellpadding="5px">
    <tr valign="baseline">
      <td  align="right" class="label" style="width:160px">Friend`s Email Address * :</td>
      <td><span id="sprytextfield4_form_arrangeViewing_EMAIL_ID">
      <input name="form_arrangeViewing_EMAIL_ID" type="text" id="form_arrangeViewing_EMAIL_ID" value="<?php if (isset($_POST['form_arrangeViewing_EMAIL_ID'])) {echo htmlentities($_POST['form_arrangeViewing_EMAIL_ID'],ENT_COMPAT,'UTF-8');} ?>" size="32" />
      <br />
      <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td  align="right" class="label">Friend`s Conatact Number :</td>
      <td><input type="text" name="form_arrangeViewing_HOME_NO" value="<?php if (isset($_POST['form_arrangeViewing_HOME_NO'])) {echo htmlentities($_POST['form_arrangeViewing_HOME_NO'],ENT_COMPAT,'UTF-8');} ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td  align="right" class="label" valign="middle">Message To Friend :</td>
      <td><span id="sprytextarea1_form_arrangeViewing_MESSAGE">
        <textarea name="form_arrangeViewing_MESSAGE" cols="32" rows="4"><?php if (isset($_POST['form_arrangeViewing_MESSAGE'])) {echo htmlentities($_POST['form_arrangeViewing_MESSAGE'],ENT_COMPAT,'UTF-8');} ?></textarea>
        <span id="countsprytextarea1_form_arrangeViewing_MESSAGE">&nbsp;</span><br />
        <span class="textareaMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td  align="right" class="label" valign="top">CAPTCHA Code* :</td>
      <td>
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
<br />  <span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span>
    <?php } ?>

      </td>
    </tr>

    <tr valign="baseline">
      <td  align="right">&nbsp;</td>
      <td><input  type="hidden" name="PROPERTY_ID" value="<?php echo  $_POST['PROPERTY_ID']?>">
      <input class="button" type="submit" name="submit"  value="Submit" />
      </td>
    </tr>
  </table>

  <input type="hidden" name="MM_insert" value="form_arrangeViewing" />
</form>
<script type="text/javascript">
<!--
var sprytextfield4_form_arrangeViewing_EMAIL_ID = new Spry.Widget.ValidationTextField("sprytextfield4_form_arrangeViewing_EMAIL_ID", "email", {maxChars:200, validateOn:["blur"]});
var sprytextarea1_form_arrangeViewing_MESSAGE = new Spry.Widget.ValidationTextarea("sprytextarea1_form_arrangeViewing_MESSAGE", {maxChars:500, counterId:"countsprytextarea1_form_arrangeViewing_MESSAGE", counterType:"chars_count", validateOn:["blur"], isRequired:false});

//-->
</script>

<?php }?>

</div>
<script type="text/javascript">
<!--
var sprytextfield1_form_arrangeViewing_captcha_code = new Spry.Widget.ValidationTextField("sprytextfield1_form_arrangeViewing_captcha_code", "none", {validateOn:["blur"]});
//-->
</script>



</body>
</html>
<?php if($freeRS == "Y"){

}
?>
