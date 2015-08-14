<?php require_once('functions.php');?>
<?php require_once('Connections/adestate.php'); ?>
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

$query_Prop_Type_RS = "SELECT * FROM property_type_master";
$Prop_Type_RS = mysql_query($query_Prop_Type_RS) or die(mysql_error());
$row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
$totalRows_Prop_Type_RS = mysql_num_rows($Prop_Type_RS);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
//  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_emailalerts") ) {
	
	$error = array();//Error messages array

	include_once("captcha/securimage.php");
	$securimage = new Securimage();
	if ($securimage->check($_POST['form_emailalerts_captcha_code']) == false) {
		$error['form_emailalerts'] = 'Y';
		$error['CAPTCHA'] = 'EMAILALERTFORM';
	}

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_emailalerts") && ($error['form_emailalerts'] != 'Y')) {
  $insertSQL = sprintf("INSERT INTO email_alerts (PROPERTY_FOR_ID, PROPERTY_TYPE_ID, MIN_PRICE, MAX_PRICE, BEDROOMS, EMAIL_ID, CONTACT_NO, NAME) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['form_emailalerts_lookingfor'], "int"),
                       GetSQLValueString($_POST['form_emailalerts_propertytype'], "int"),
                       GetSQLValueString(0, "int"),
                       GetSQLValueString($_POST['form_quicksearch_price'], "int"),
                       GetSQLValueString($_POST['form_emailalerts_beds'], "int"),
                       GetSQLValueString($_POST['form_emailalerts_email'], "text"),
                       GetSQLValueString($_POST['form_emailalerts_phone'], "text"),
					   GetSQLValueString($_POST['form_emailalerts_name'], "text"));

  $Result1 = mysql_query($insertSQL) or die(mysql_error());
  $SUCCESS = 'form_emailalerts';
} else {
	if(!empty($_GET['lookingfor']) && (empty($_POST['form_emailalerts_lookingfor']) || !isset($_POST['form_emailalerts_lookingfor']))){
		$_POST['form_emailalerts_lookingfor'] = $_GET['lookingfor'];
		$_POST['form_emailalerts_propertytype'] = $_GET['propertytype'];
		$_POST['form_quicksearch_price'] = $_GET['price'];
		$_POST['form_emailalerts_beds'] = $_GET['beds'];
	}
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
<div id="column1" >

<div class="block">
    
<div >
<div class="heading">Email Alerts Configuration</div>
<div style="padding-left:100px">
<?php if($SUCCESS == 'form_emailalerts'){?>
  <div class="actionsuccess" id="form_emailalerts_success"><img src="images/ok.png" width="32" height="32" align="absmiddle" />You have succesfully configured email alert.</div>
                  <script>
var elem=document.getElementById('form_emailalerts_success');
if(elem)
 elem.scrollIntoView()
                  </script>
<?php } else {?>
 <?php  if (isset($error) && $error['CAPTCHA']  == 'EMAILALERTFORM'){?>
  <div  id="form_emailalerts_error_div_ID"><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span></div>
    
    <?php }?>

    <form class="form" action="<?php echo $editFormAction; ?>" method="POST" name="form_emailalerts" id="form_emailalerts_ID">

  <table align="center" cellspacing="0" width="100%" >
      <tr>
<td  class="label" valign="top">
Looking For :<br />
  <label class="label">
    <input type="radio" name="form_emailalerts_lookingfor" value="1" id="lookingfor_0" <?php if(strcmp($_POST['form_emailalerts_lookingfor'],"2")!= 0) echo "checked=\"checked\"";?> onclick="popPrice(this,'form_emailalerts_priceDiv')"/>
    Buy</label>

  <label class="label">
    <input type="radio" name="form_emailalerts_lookingfor" value="2" id="lookingfor_1" <?php if(strcmp($_POST['form_emailalerts_lookingfor'],"2")== 0) echo "checked=\"checked\"";?> onclick="popPrice(this,'form_emailalerts_priceDiv')"/>
    Rent</label>
<br />
<br />
Property Type :<br />
<select name="form_emailalerts_propertytype" >
  <option value="0" <?php if (!(strcmp("0", $_POST['form_emailalerts_propertytype']))) {echo "selected=\"selected\"";} ?>>Any</option>
  <?php
do {  
?>
  <option value="<?php echo $row_Prop_Type_RS['PROPERTY_TYPE_ID']?>"<?php if (!(strcmp($row_Prop_Type_RS['PROPERTY_TYPE_ID'], $_POST['form_emailalerts_propertytype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Prop_Type_RS['SHORT_DESCRIPTION']?></option>
  <?php
} while ($row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS));
  $rows = mysql_num_rows($Prop_Type_RS);
  if($rows > 0) {
      mysql_data_seek($Prop_Type_RS, 0);
	  $row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
  }
?>
</select>
<br /><br />
Price Range : <br />
<div id="form_emailalerts_priceDiv">
<select name="form_quicksearch_price" id="form_quicksearch_price_id">

<?php if($_POST['form_emailalerts_lookingfor']  != '2'){?>
<option value="50000"<?php if (!(strcmp("50000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;50000</option>
<option value="100000"<?php if (!(strcmp("100000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;100000</option>
<option value="150000"<?php if (!(strcmp("150000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;150000</option>
<option value="200000"<?php if (!(strcmp("200000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;200000</option>
<option value="250000"<?php if (!(strcmp("250000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;250000</option>
<option value="300000"<?php if (!(strcmp("300000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;300000</option>
<option value="350000"<?php if (!(strcmp("350000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;350000</option>
<option value="400000"<?php if (!(strcmp("400000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;400000</option>
<option value="450000"<?php if (!(strcmp("450000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;450000</option>
<option value="500000"<?php if (!(strcmp("500000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;500000</option>
<option value="550000"<?php if (!(strcmp("550000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;550000</option>
<option value="600000"<?php if (!(strcmp("600000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;600000</option>
<option value="650000"<?php if (!(strcmp("650000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;650000</option>
<option value="700000"<?php if (!(strcmp("700000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;700000</option>
<option value="750000"<?php if (!(strcmp("750000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;750000</option>
<option value="800000"<?php if (!(strcmp("800000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;800000</option>
<option value="850000"<?php if (!(strcmp("850000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;850000</option>
<option value="900000"<?php if (!(strcmp("900000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;900000</option>
<option value="950000"<?php if (!(strcmp("950000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;950000</option>
<option value="9999999999999"<?php if (!(strcmp("9999999999999", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Any Price</option>
<?php }else if($_POST['form_emailalerts_lookingfor']  == '2'){?>
<option value="300"<?php if (!(strcmp("300", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;300</option>
<option value="400"<?php if (!(strcmp("400", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;400</option>
<option value="500"<?php if (!(strcmp("500", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;500</option>
<option value="600"<?php if (!(strcmp("600", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;600</option>
<option value="700"<?php if (!(strcmp("700", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;700</option>
<option value="800"<?php if (!(strcmp("800", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;800</option>
<option value="900"<?php if (!(strcmp("900", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;900</option>
<option value="1000"<?php if (!(strcmp("1000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1000</option>
<option value="1100"<?php if (!(strcmp("1100", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1100</option>
<option value="1200"<?php if (!(strcmp("1200", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1200</option>
<option value="1300"<?php if (!(strcmp("1300", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1300</option>
<option value="1400"<?php if (!(strcmp("1400", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1400</option>
<option value="1500"<?php if (!(strcmp("1500", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1500</option>
<option value="1600"<?php if (!(strcmp("1600", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1600</option>
<option value="1700"<?php if (!(strcmp("1700", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1700</option>
<option value="1800"<?php if (!(strcmp("1800", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1800</option>
<option value="1900"<?php if (!(strcmp("1900", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1900</option>
<option value="2000"<?php if (!(strcmp("2000", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;2000</option>
<option value="9999999999999"<?php if (!(strcmp("9999999999999", $_POST['form_quicksearch_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Any Price</option>

<?php }?>

</select>
</div>


  <br />
  Bedrooms:<br />
<select name="form_emailalerts_beds">
 <option value="-1" <?php if ((strcmp("-1", $_POST['form_emailalerts_beds'])== 0)) {echo "selected=\"selected\"";} ?>>Any</option>
  <option value="0" <?php if ((strcmp("0", $_POST['form_emailalerts_beds'])== 0)) {echo "selected=\"selected\"";} ?>>Studio</option>
  <?php
for($i=1;$i<5;$i++){
?>
  <option value="<?php echo $i?>"<?php if ((strcmp($i, $_POST['form_emailalerts_beds'])==0)) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
  <?php
}
?>
</select>

</td>
<td class="label" valign="top">
Name * :<br />
<span id="sprytextfield1_form_emailalerts_name">
<input type="text" id="form_emailalerts_name" name="form_emailalerts_name" value="<?php echo $_POST['form_emailalerts_name'] ?>"/><br />
<span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
<br />

Contact No * :<br />
<span id="sprytextfield1_form_emailalerts_phone">
<input type="text" id="form_emailalerts_phone" name="form_emailalerts_phone" value="<?php echo $_POST['form_emailalerts_phone'] ?>"/><br />
<span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldInvalidFormatMsg">Invalid number.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
<br />

Email * : Use comma(,) to enter multiple emails<br />
<span id="sprytextfield1_form_emailalerts_email">
<input type="text" id="form_emailalerts_email" name="form_emailalerts_email" value="<?php echo $_POST['form_emailalerts_email'] ?>"/><br />
<span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span>
<br />
CAPTCHA Code*:<br />
      <img id="captcha_form_emailalerts" src="captcha/securimage_show_small_4letters.php" alt="CAPTCHA Image" />
      <a href="#" onclick="document.getElementById('captcha_form_emailalerts').src = 'captcha/securimage_show_small_4letters.php?' + Math.random(); return false"><img src="captcha/images/refresh.gif" width="22" height="20" alt="Refresh" style="border-style:none; margin:0; padding:0px; vertical-align:top;"/></a>
      

      <a id="si_aud_ctf1" href="captcha/securimage_play.php" rel="nofollow" title="CAPTCHA Audio"> 
      <img src="captcha/images/audio_icon.gif" alt="CAPTCHA Audio" style="border-style:none; margin:0; padding:0px; vertical-align:top;" onclick="this.blur();" /></a> 
      <br />
      <span class="label">Please enter code shown in above image.</span><br />
      <span id="sprytextfield4_form_emailalerts_captcha_code">
      <input type="text" name="form_emailalerts_captcha_code" size="10" maxlength="6" style="width:100px"/><br />
      <span class="textfieldRequiredMsg">Mandatory.</span></span>
          <?php if( isset($error) && $error['CAPTCHA']  == 'EMAILALERTFORM'){ ?>
<br />  
<span class="label" style="color:#CC3333">You have entered wrong code.Please try again.</span>
    <?php } ?>

<br />

<input class="button" type="submit" name="submit" value="Submit"/>
</td>

</tr>
</table>
  <input type="hidden" name="MM_insert" value="form_emailalerts" />
    </form>
    <script type="text/javascript">
<!--
var sprytextfield1_form_emailalerts_email = new Spry.Widget.ValidationTextField("sprytextfield1_form_emailalerts_email", "none", {maxChars:200});
var sprytextfield1_form_emailalerts_name = new Spry.Widget.ValidationTextField("sprytextfield1_form_emailalerts_name", "none", {maxChars:200});
var sprytextfield1_form_emailalerts_phone = new Spry.Widget.ValidationTextField("sprytextfield1_form_emailalerts_phone", "none", {maxChars:20});

var sprytextfield4_form_emailalerts_captcha_code = new Spry.Widget.ValidationTextField("sprytextfield4_form_emailalerts_captcha_code", "none", {validateOn:["blur"]});

//-->
</script>

    
                          <?php  if (isset($error) && $error['form_emailalerts']  == 'Y'){?>
                  <script>
var elem=document.getElementById('form_emailalerts_error_div_ID');
if(elem)
 elem.scrollIntoView()
                  </script>
    
    <?php }?>

<?php }?>
</div>
</div>



</td>
<td class="content_bg_cr">
</td>


</tr>
<tr>
<td class="content_bg_bl">
</td>
<td class="content_bg_br">
</td>
</tr>
</table>
    </div>

</div>
<div id="column2" >

    <div class="block">
<div class="heading">Resources For Buyers</div>
  <a href="mortgages.php" class="largebutton"> Mortgages</a>
   <a href="mortgage-calculator.php" target="_blank" onclick="return showUrl(this.href)" class="largebutton"> Mortgage Calculator</a>
   <a href="buy_reasons.php" class="largebutton"> Why Buy Through Aeon</a>
   <a href="step-by-step-guides.php#buyingguide" class="largebutton"> Guide For Buyers</a>
   <a href="emailalerts.php" class="largebutton"> Register For Property Alerts</a>



  </div>
       
    <div class="block">
<div class="heading">Resources For Tenants</div>
   <a href="rent_reasons.php" class="largebutton"> Why Rent Through Aeon</a>
   <a href="step-by-step-guides.php#rentingguide" class="largebutton"> Guide For Renting </a>
   <a href="emailalerts.php" class="largebutton"> Register For Property Alerts </a>


  </div>      

</div>
</div>

<?php include('footer.php'); ?>




</body>
</html>