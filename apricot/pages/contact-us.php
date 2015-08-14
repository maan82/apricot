<?php require_once('Connections/adestate.php'); ?>
<?php 
require_once('functions.php');
require_once('class.Email.php'); 
$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_contactform") ) {
	
	$error = array();//Error messages array

	include_once("captcha/securimage.php");
	$securimage = new Securimage();
	if ($securimage->check($_POST['form_contactform_captcha_code']) == false) {
		$error['form_contactform'] = 'form_contactform';
		$error['CAPTCHA'] = 'CONTACTFORM';
	}

}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_contactform") && ($error['form_contactform'] != 'form_contactform')) {
  $insertSQL = sprintf("INSERT INTO query (NATURE, NAME, PHONE, EMAIL_ID, QUERY_TEXT) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['form_contactform_subject'], "text"),
                       GetSQLValueString($_POST['form_contactform_name'], "text"),
                       GetSQLValueString($_POST['form_contactform_phone'], "text"),
                       GetSQLValueString($_POST['form_contactform_email'], "text"),
                       GetSQLValueString($_POST['form_contactform_query'], "text"));


  $Result1 = mysql_query($insertSQL) or die(mysql_error());
  
		$query_emailconfRS = "SELECT * FROM email_configuration WHERE TYPE = 'CONTACT_US'";
		$emailconfRS = mysql_query($query_emailconfRS) or die(mysql_error());
		$row_emailconfRS = mysql_fetch_assoc($emailconfRS);
		$totalRows_emailconfRS = mysql_num_rows($emailconfRS);
 		  $CustomHeaders= '';
 		  $message = new Email($row_emailconfRS['TO'], $row_emailconfRS['FROM'], $row_emailconfRS['SUBJECT'], $CustomHeaders);

		  $text = "\n Subject :- ".$_POST['form_contactform_subject'];
		  $text .= "\n Name :- ".$_POST['form_contactform_name'];
		  $text .= "\n Phone :- ".$_POST['form_contactform_phone'];	
		  $text .= "\n Email :- ".$_POST['form_contactform_email'];	
  		  $text .= "\n Message :- ".$_POST['form_contactform_query'];

		  $message->SetTextContent($text);
		  $message->Send();

  $form_contactform_success = "Y";
} else {

$query_subjectRS = "SELECT * FROM contact_us_subject_master ORDER BY SUBJECT DESC";
$subjectRS = mysql_query($query_subjectRS) or die(mysql_error());
$row_subjectRS = mysql_fetch_assoc($subjectRS);
$totalRows_subjectRS = mysql_num_rows($subjectRS);
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

<div id="maincontent" >
    <?php  if(isset($_GET['message'])){?>
<div id="sorting">
    <div class="label">
        <img src="images/info.png" alt="Information" width="32" height="32" align="absmiddle"> 
        Please fill the Contact US form, we shall be in tocuh with you shortly.
<div class="clear"></div>
    </div>
           
            
    
    
    
    </div>

 
<?php }  ?>
<div id="column1" >
<div class="block">
    <div class="heading">Contact Details</div>
    <div style="padding-left:10px;font-size: 18px" class="label">
    <div style="float:left">
      <img src="images/icon-green-mail1.png" width="60" align="absmiddle" /></div>
<div style="width:270px;float:left;padding-left:5px"><br />
	<?php echo $ADDRESS_LINE1;?>,<br /> <?php echo $ADDRESS_LINE2;?>
 
<br />
<br />
</div>
<div style="float:left;width:300px;margin-left: 20px">
<h1 class="block_title">Driving Directions</h1>
       	<form name="ddform" id="ddformid" action="http://maps.google.co.uk/maps" method="get" target="_blank" onsubmit="return showUrl(this.action,'formname')">
        <label class="label">
From Postcode :-
<input type="text" name="saddr" id="saddr"/>
</label>
            <input type="submit" value="Go" />
            <input type="hidden" id="daddr" name="daddr" value="<?php echo $POST_CODE;?>" />
            <input type="hidden" name="hl" value="en" /></p>
        </form>

</div>
<div class="clear"></div>

<img src="images/Phone.png"  height="60" align="absmiddle" />Telephone: <?php echo $PHONE;?><br />
<br />
<br />
<img src="images/fax_icon.png"  height="60" align="absmiddle" />Fax: <?php echo $FAX;?><br />
<br />
<br />

<div style="float: left">
<img src="images/eMail Icon.png" width="60" align="absmiddle" /> 
</div>
<br />
<div style="float: left;margin-left: 20px">
Email: <a class="largebutton" style="display: inline" href="mailto:<?php echo $EMAIL_HOUNSLOW;?>">  <?php echo $EMAIL_HOUNSLOW;?></a>
<br />
<br />
For sales: <a class="largebutton" style="display: inline" href="mailto:<?php echo $EMAIL_SALES;?>">  <?php echo $EMAIL_SALES;?></a>
<br />
<br />
For sales: <a class="largebutton" style="display: inline" href="mailto:<?php echo $EMAIL_LETTINGS;?>">  <?php echo $EMAIL_LETTINGS;?></a>
</div>
<div style="clear: both"></div>

<br />
    </div>

</div>


</div>
<div id="column2" >


          <div class="block">
                	<div class="heading">Contact  Us </div>
                 
                  
                  <div id="contactformDiv"   class="quick_contact_wrapper">

				 
                  <?php if($form_contactform_success == "Y"){?>
                  <div class="actionsuccess" id="form_contactform_success"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Thanks for contacting us. We shall be in tocuh with you shortly..</div>
                  <script>
					var elem=document.getElementById('contactformDiv');
					if(elem)
					 elem.scrollIntoView()
                  </script>
                  <?php } else {?>
                      <?php  if (isset($error) && $error['form_contactform']  == 'form_contactform'){?>
  <div><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">You have entered wrong captcha code.Please try again.</span></div>
                  <script>
var elem=document.getElementById('contactformDiv');
if(elem)
 elem.scrollIntoView()
                  </script>
    
    <?php }?>

                              <form action="<?php echo $editFormAction; ?>" class="form" method="POST" name="form_contactform" id="form_contactform" enctype="multipart/form-data">
            <table align="center" cellspacing="0" class="form_table_search" >
              <tr>
                <td class="label">Name :*<br />
                  <span id="sprytextfield1_form_contactform_name">
                  <input name="form_contactform_name" id="form_contactform_name" type="text" maxlength="50" style="" value="<?php echo $_POST['form_contactform_name']?>" /><br />
                <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
              </tr>
              <tr>
                <td class="label">
                  Phone No. :*<br  />
                  <span id="sprytextfield2_form_contactform_phone">
                  <input name="form_contactform_phone" id="form_contactform_phone" type="text" maxlength="50"  style="" value="<?php echo $_POST['form_contactform_phone']?>" /><br />
                  <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
              </tr>
              <tr>
                <td class="label">
                  EMail :*<br />
                  <span id="sprytextfield3_form_contactform_email">
                  <input name="form_contactform_email" id="form_contactform_email" type="text" maxlength="100"  style="" value="<?php echo $_POST['form_contactform_email']?>" /><br />
                  <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
              </tr>
                            <tr>
                <td class="label">
                  Subject :*<br />

                  <select name="form_contactform_subject" id="form_contactform_subject"  >
                    <?php
do {  
?>
                    <option value="<?php echo $row_subjectRS['SUBJECT']?>"<?php if (!(strcmp($_POST['form_contactform_subject'], $row_subjectRS['SUBJECT']))) {echo "selected=\"selected\"";} ?>><?php echo $row_subjectRS['SUBJECT']?></option>
                    <?php
} while ($row_subjectRS = mysql_fetch_assoc($subjectRS));
  $rows = mysql_num_rows($subjectRS);
  if($rows > 0) {
      mysql_data_seek($subjectRS, 0);
	  $row_subjectRS = mysql_fetch_assoc($subjectRS);
  }
?>
                  </select>
                  
</td>
              </tr>

              <tr>
                <td class="label">
                  Your Query :*<br />
                  <span id="sprytextarea1_form_contactform_query">
                  <textarea id="form_contactform_query" name="form_contactform_query" style=""  rows="5"><?php echo $_POST['form_contactform_query']?></textarea><br />
                  <span class="textareaRequiredMsg">Mandatory.</span><span class="textareaMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
              </tr>
                  <tr valign="baseline">
     
      <td class="label">CAPTCHA Code*:<br />
      <img id="captcha_form_contactform" src="captcha/securimage_show_small_4letters.php" alt="CAPTCHA Image" />
      <a href="#" onclick="document.getElementById('captcha_form_contactform').src = 'captcha/securimage_show_small_4letters.php?' + Math.random(); return false"><img src="captcha/images/refresh.gif" width="22" height="20" alt="Refresh" style="border-style:none; margin:0; padding:0px; vertical-align:top;"/></a>
      

      <a id="si_aud_ctf1" href="captcha/securimage_play.php" rel="nofollow" title="CAPTCHA Audio"> 
      <img src="captcha/images/audio_icon.gif" alt="CAPTCHA Audio" style="border-style:none; margin:0; padding:0px; vertical-align:top;" onclick="this.blur();" /></a> 
      <br />
      <span class="label">Please enter code shown in above image.</span><br  />
      <span id="sprytextfield4_form_contactform_captcha_code">
      <input type="text" name="form_contactform_captcha_code" size="10" maxlength="6" style="width:100px"/><br />
      <span class="textfieldRequiredMsg">Mandatory.</span></span>
          <?php if( isset($error) && $error['CAPTCHA']  == 'CONTACTFORM'){ ?>
  
<span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span>
    <?php } ?>

      </td>
    </tr>

              <tr>
              
                <td>
                  <input type="submit" name="submit" class="button" value="Submit" />

                </td>
              </tr>
              </table>
            <input type="hidden" name="MM_insert" value="form_contactform" />
                              </form>
 <script type="text/javascript">
<!--
var sprytextfield1_form_contactform_name = new Spry.Widget.ValidationTextField("sprytextfield1_form_contactform_name", "none", {maxChars:53});
var sprytextfield2_form_contactform_phone = new Spry.Widget.ValidationTextField("sprytextfield2_form_contactform_phone", "none", {maxChars:20});
var sprytextfield3_form_contactform_email = new Spry.Widget.ValidationTextField("sprytextfield3_form_contactform_email", "email", {maxChars:200});
var sprytextarea1_form_contactform_query = new Spry.Widget.ValidationTextarea("sprytextarea1_form_contactform_query", {maxChars:500});
var sprytextfield4_form_contactform_captcha_code = new Spry.Widget.ValidationTextField("sprytextfield4_form_contactform_captcha_code", "none", {validateOn:["blur"]});

//-->
</script>

                              <?php } ?>
				  </div>



           </div>

</div>
</div>

<?php include('footer.php'); ?>





</body>
</html>