<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php require_once('constants.php'); ?>
<?php
sessionStart("Add or Edit Property");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_addproperty")) {
	$error = array();//Error messages array
	 if (strlen($_POST['form_addproperty_briefdescription']) > 65000) {
		$error['form_addproperty_briefdescription'] = 'Max';

	}
	





	if( !$error)
	{

	
	
	$PROPERTY_ID = getSeqNextVal( $mannestateDB, $database_mannestateDB, "sequence_property_id" );

	//include('class.sts.php');
	//$uploaddir = 'property_audios/';
	//$propdir = $uploaddir.$PROPERTY_ID;
	//mkdir($propdir);

	//$text = strip_tags($_POST['form_addproperty_fulldescription']);
	//$s_ts = &new s_TS($text, 'eng');
	//$filePath = $propdir.'/'.$PROPERTY_ID.'.mp3';
	//$fp = fopen($filePath, 'w');
	//fwrite($fp, $s_ts->print_bufor());
	//fclose($fp);
if(!isset($_POST['form_addproperty_featured']))
	$_POST['form_addproperty_featured'] = "N";

$insertSQL = sprintf("INSERT INTO property_details (PROPERTY_ID,PROPERTY_FOR_ID, PROPERTY_TYPE_ID, PRICE, CURRENCY_ID, BEDROOMS, BATHROOMS, KITCHENS, DRAWING_ROOMS, DINING_ROOMS, PARKING, LAWN, AREA, AREA_UNIT_ID, CITY, `STATE`, COUNTRY, FLOORS, BRIEF_DESCRIPTION, DETAIL_DESCRIPTION, AUDIO_PATH, POSTCODE, UPDATION_DATE,IS_HOT,PROPERTY_OF_WEEK) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,%s,%s)",
					   GetSQLValueString($PROPERTY_ID, "double"),
                       GetSQLValueString($_POST['form_addproperty_propertyfor'], "int"),
                       GetSQLValueString($_POST['form_addproperty_propertytype'], "int"),
                       GetSQLValueString($_POST['form_addproperty_price'], "double"),
                       GetSQLValueString($_POST['form_addproperty_currency'], "int"),
                       GetSQLValueString($_POST['form_addproperty_bedrooms'], "int"),
                       GetSQLValueString($_POST['form_addproperty_bathrooms'], "int"),
                       GetSQLValueString($_POST['form_addproperty_kitchens'], "int"),
                       GetSQLValueString($_POST['form_addproperty_drawingrooms'], "int"),
                       GetSQLValueString($_POST['form_addproperty_diningrooms'], "int"),
                       GetSQLValueString($_POST['form_addproperty_parking'], "int"),
                       GetSQLValueString($_POST['form_addproperty_lawn'], "int"),
                       GetSQLValueString($_POST['form_addproperty_area'], "int"),
                       GetSQLValueString($_POST['form_addproperty_areaunit'], "int"),
                       GetSQLValueString($_POST['form_addproperty_city'], "text"),
                       GetSQLValueString($_POST['form_addproperty_state'], "text"),
                       GetSQLValueString($_POST['form_addproperty_country'], "text"),

                       GetSQLValueString($_POST['form_addproperty_floors'], "int"),
                       GetSQLValueString($_POST['form_addproperty_briefdescription'], "text"),
                       GetSQLValueString($_POST['form_addproperty_fulldescription'], "text"),
					   GetSQLValueString($filePath, "text"),
					   GetSQLValueString($_POST['form_addproperty_postcode'], "text"),
					   "NOW()",
					   GetSQLValueString($_POST['form_addproperty_featured'], "text"),
					   GetSQLValueString($_POST['form_addproperty_ofweek'], "text"));
//echo $insertSQL;
  $Result1 = mysql_query($insertSQL) or die(mysql_error());

  $propParam =  "?PROPERTY_ID=".$PROPERTY_ID."&POSTCODE=".$_POST['form_addproperty_postcode'];
  $insertGoTo = "adminaddpropertymap.php".$propParam;
  
    if (isset($_POST['form_newsletter_submit']) && $_POST['form_newsletter_submit'] == "Save and Preview") {  
	  $insertGoTo = "/aeon/pages/property-details.php".$propParam;
	}
  
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
	}
}

$query_Area_Units_RS = "SELECT * FROM area_unit_master ORDER BY SHORT_DESCRIPTION ASC";
$Area_Units_RS = mysql_query($query_Area_Units_RS) or die(mysql_error());
$row_Area_Units_RS = mysql_fetch_assoc($Area_Units_RS);
$totalRows_Area_Units_RS = mysql_num_rows($Area_Units_RS);


$query_Property_Type_RS = "SELECT * FROM property_type_master";
$Property_Type_RS = mysql_query($query_Property_Type_RS) or die(mysql_error());
$row_Property_Type_RS = mysql_fetch_assoc($Property_Type_RS);
$totalRows_Property_Type_RS = mysql_num_rows($Property_Type_RS);


$query_Currency_Type_RS = "SELECT * FROM currency_master ORDER BY CURRENCY_NAME ASC";
$Currency_Type_RS = mysql_query($query_Currency_Type_RS) or die(mysql_error());
$row_Currency_Type_RS = mysql_fetch_assoc($Currency_Type_RS);
$totalRows_Currency_Type_RS = mysql_num_rows($Currency_Type_RS);


$query_Property_For_RS = "SELECT * FROM property_for_master ORDER BY SHORT_DESCRIPTION ASC";
$Property_For_RS = mysql_query($query_Property_For_RS) or die(mysql_error());
$row_Property_For_RS = mysql_fetch_assoc($Property_For_RS);
$totalRows_Property_For_RS = mysql_num_rows($Property_For_RS);


$query_countryRS = "SELECT * FROM country_master ORDER BY COUNTRY_NAME_COUNTRY ASC";
$countryRS = mysql_query($query_countryRS) or die(mysql_error());
$row_countryRS = mysql_fetch_assoc($countryRS);
$totalRows_countryRS = mysql_num_rows($countryRS);

$query_grdbRS = "SELECT * FROM garden_type_master ORDER BY ID_GM ASC";
$grdbRS = mysql_query($query_grdbRS) or die(mysql_error());
$row_grdbRS = mysql_fetch_assoc($grdbRS);
$totalRows_grdbRS = mysql_num_rows($grdbRS);

if (!isset($_POST['form_addproperty_fulldescription'])) {
  $_POST['form_addproperty_fulldescription'] = $MISREPRESENTATION_ACT;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">



<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />

	<!-- markItUp! skin -->
	<link rel="stylesheet" type="text/css" href="js/latest/markitup/skins/markitup/style.css">
	<!--  markItUp! toolbar skin -->
	<link rel="stylesheet" type="text/css" href="js/latest/markitup/sets/default/style.css">
	<!-- jQuery -->
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	<!-- markItUp! -->
	<script type="text/javascript" src="js/latest/markitup/jquery.markitup.js"></script>
	<!-- markItUp! toolbar settings -->
	<script type="text/javascript" src="js/latest/markitup/sets/default/set.js"></script>


<script src="js/nicEdit.js" type="text/javascript"></script>
<meta content="width=device-width" name="viewport">
<link href="small-device.css" media="only screen and (max-device-width: 480px)" type="text/css" rel="stylesheet">
</head>

<body >
<?php include('top.php')?>
    <div >
    <div class="orangehead">Adding New Property </div>
<div class="steps"> <span class="stepactive">1. Add  Details</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="steppending">2. Set Map Location</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="steppending">3. Add Images</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="steppending">4. Preview & Publish</span></div>
    <form action="<?php echo $editFormAction; ?>" method="POST" name="form_addproperty" class="formcenter">
     <fieldset>
       <legend><span>Property Details Form</span>
  </legend>
  <table align="center" class="form_table" cellpadding="5px">
    <tr valign="baseline">
      <td  align="right" width="200" class="label">Property For * :</td>
<td><select name="form_addproperty_propertyfor">
  <?php
do {  
?>
  <option value="<?php echo $row_Property_For_RS['PROPERTY_FOR_ID']?>"<?php if (!(strcmp($row_Property_For_RS['PROPERTY_FOR_ID'], $_POST['form_addproperty_propertyfor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Property_For_RS['SHORT_DESCRIPTION']?></option>
  <?php
} while ($row_Property_For_RS = mysql_fetch_assoc($Property_For_RS));
  $rows = mysql_num_rows($Property_For_RS);
  if($rows > 0) {
      mysql_data_seek($Property_For_RS, 0);
	  $row_Property_For_RS = mysql_fetch_assoc($Property_For_RS);
  }
?>
</select>
</td>
</tr>  
    <tr valign="baseline">
      <td  align="right"  class="label">Type Of Property * :</td>
<td><span id="spryselect2_form_addproperty_propertytype">
  <select name="form_addproperty_propertytype">
    <?php
do {  
?>
    <option value="<?php echo $row_Property_Type_RS['PROPERTY_TYPE_ID']?>"<?php if (!(strcmp($row_Property_Type_RS['PROPERTY_TYPE_ID'], $_POST['form_addproperty_propertytype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Property_Type_RS['SHORT_DESCRIPTION']?></option>
    <?php
} while ($row_Property_Type_RS = mysql_fetch_assoc($Property_Type_RS));
  $rows = mysql_num_rows($Property_Type_RS);
  if($rows > 0) {
      mysql_data_seek($Property_Type_RS, 0);
	  $row_Property_Type_RS = mysql_fetch_assoc($Property_Type_RS);
  }
?>
  </select>
  <span class="selectRequiredMsg">Please select an item.</span></span></td>
</tr>
          <tr valign="baseline">
            <td align="right" class="label"> Price * :</td>
            <td><span id="sprytextfield1_form_addproperty_price">
            <input value="<?php stickyValue('form_addproperty',$_POST["form_addproperty_price"]); ?>" style="<?php textFieldStyle('form_addproperty',$error["form_addproperty_price"],$error); ?>"  name="form_addproperty_price" type="text" placeholde="&pound;" />
            <?php errMessage($error['form_addproperty_price'],'');?>
            <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
          </tr>
          <tr valign="baseline" style="display:none">
            <td align="right" class="label"> Price Currency Unit * :</td>
            <td><span id="spryselect3_form_addproperty_currency">
              <select name="form_addproperty_currency" id="select">
                <?php
do {  
?>
                <option value="<?php echo $row_Currency_Type_RS['CURRENCY_ID']?>"<?php if (!(strcmp($row_Currency_Type_RS['CURRENCY_ID'], $_POST['form_addproperty_currency']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Currency_Type_RS['CURRENCY_NAME']?></option>
                <?php
} while ($row_Currency_Type_RS = mysql_fetch_assoc($Currency_Type_RS));
  $rows = mysql_num_rows($Currency_Type_RS);
  if($rows > 0) {
      mysql_data_seek($Currency_Type_RS, 0);
	  $row_Currency_Type_RS = mysql_fetch_assoc($Currency_Type_RS);
  }
?>
              </select>
            <span class="selectRequiredMsg">Please select an item.</span></span></td>
          </tr>
          <tr valign="baseline">
            <td align="right" class="label"> Number Of Bedrooms :</td>
            <td>

              <select name="form_addproperty_bedrooms" id="select">
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_bedrooms']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

            
            </td>
</tr>
          <tr valign="baseline">
            <td align="right" class="label"> Number Of Bathrooms :</td>
            <td>
                          <select name="form_addproperty_bathrooms" id="select">
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_bathrooms']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

            
</td>
</tr>
          <tr valign="baseline">
            <td align="right" class="label"> Number Kitchens  :</td>
            <td>
                                      <select name="form_addproperty_kitchens" >
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_kitchens']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

            
</td>
</tr>   
  <tr valign="baseline">
            <td align="right" class="label"> Number Of Drawing Rooms  :</td>
            <td>
                                                  <select name="form_addproperty_drawingrooms" >
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_drawingrooms']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

            
</td>
         </tr> 
         <tr valign="baseline">
            <td align="right" class="label"> Number Of Dining Rooms  :</td>
            <td>
                                                              <select name="form_addproperty_diningrooms" >
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_diningrooms']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

</td>
         </tr>
          <tr valign="baseline">
            <td align="right"  class="label">  Number Of Parkings  :</td>
            <td>
                                                                          <select name="form_addproperty_parking" >
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_parking']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

</td>
         </tr> 
         <tr valign="baseline">
            <td align="right"  class="label"> Garden  :</td>
            <td>
              <select name="form_addproperty_lawn" >
                <option value="0">None</option>
                <?php
do {  
?>
                <option value="<?php echo $row_grdbRS['ID_GM']?>"<?php if (!(strcmp($row_grdbRS['ID_GM'], $_POST['form_addproperty_lawn']))) {echo "selected=\"selected\"";} ?>><?php echo $row_grdbRS['DESCRIPTION_GM']?></option>
                <?php
} while ($row_grdbRS = mysql_fetch_assoc($grdbRS));
  $rows = mysql_num_rows($grdbRS);
  if($rows > 0) {
      mysql_data_seek($grdbRS, 0);
	  $row_grdbRS = mysql_fetch_assoc($grdbRS);
  }
?>
              </select>

</td>
        </tr>  
                  <tr valign="baseline">
            <td align="right"  class="label"> Number Of Floors  :</td>
            <td>
                                                                                                  <select name="form_addproperty_floors" >
              <?php for($i=0;$i<=10;$i++){?>
                <option value="<?php echo $i?>"<?php if (!(strcmp($i, $_POST['form_addproperty_floors']))) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                            <?php } ?>
              </select>

</td>
    </tr>
        
        <tr valign="baseline">
            <td align="right"  class="label"> Area Of Property  :</td>
            <td><span id="sprytextfield11_form_addproperty_area">
            <input value="<?php stickyValue('form_addproperty',$_POST["form_addproperty_area"]); ?>" style="<?php textFieldStyle('form_addproperty',$error["form_addproperty_area"],$error); ?>"  name="form_addproperty_area" type="text" />
            <?php  errMessage($error['form_addproperty_area'],'');?>
            <span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
       </tr>   
       <tr valign="baseline">
            <td align="right"  class="label"> Unit Of Area  :</td>
            <td><span id="spryselect1_form_addproperty_areaunit">
              <select name="form_addproperty_areaunit">
                <?php
do {  
?>
                <option value="<?php echo $row_Area_Units_RS['AREA_UNIT_ID']?>"<?php if (!(strcmp($row_Area_Units_RS['AREA_UNIT_ID'], $_POST['form_addproperty_areaunit']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Area_Units_RS['SHORT_DESCRIPTION']?></option>
                <?php
} while ($row_Area_Units_RS = mysql_fetch_assoc($Area_Units_RS));
  $rows = mysql_num_rows($Area_Units_RS);
  if($rows > 0) {
      mysql_data_seek($Area_Units_RS, 0);
	  $row_Area_Units_RS = mysql_fetch_assoc($Area_Units_RS);
  }
?>
              </select>
</span></td>
     </tr>
     <tr valign="baseline">
            <td align="right"  class="label">  Postcode Of Property  :</td>
            <td><span id="sprytextfield12_form_addproperty_postcode">
            <input value="<?php stickyValue('form_addproperty',$_POST["form_addproperty_postcode"]); ?>" style="<?php textFieldStyle('form_addproperty',$error["form_addproperty_postcode"],$error); ?>"  name="form_addproperty_postcode" type="text" />
            <?php  errMessage($error['form_addproperty_postcode'],'');?>
            <span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldRequiredMsg">Mandatory.</span></span></td>
    </tr>
     
          <tr valign="baseline" style="display:none">
            <td align="right"  class="label">  City Of Property  :</td>
            <td><span id="sprytextfield12_form_addproperty_city">
            <input value="<?php stickyValue('form_addproperty',$_POST["form_addproperty_city"]); ?>" style="<?php textFieldStyle('form_addproperty',$error["form_addproperty_city"],$error); ?>"  name="form_addproperty_city" type="text" />
            <?php  errMessage($error['form_addproperty_city'],'');?>
            <span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
    </tr>   
       <tr valign="baseline" style="display:none">
            <td align="right"  class="label"> State Of Property  :</td>
            <td><span id="sprytextfield13_form_addproperty_state">
            <input value="<?php stickyValue('form_addproperty',$_POST["form_addproperty_state"]); ?>" style="<?php textFieldStyle('form_addproperty',$error["form_addproperty_state"],$error); ?>"  name="form_addproperty_state" type="text" />
            <?php  errMessage($error['form_addproperty_state'],'');?>
            <span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span></td>
   </tr>      
    <tr valign="baseline" >
            <td align="right"  class="label"> Country Of property * :</td>
            <td>
            <select name="form_addproperty_country">
              <?php
do {  if(empty($_POST['form_addproperty_country'])){$_POST['form_addproperty_country'] = '826';}
?>
              <option value="<?php echo $row_countryRS['COUNTRY_ID_COUNTRY']?>"<?php if (!(strcmp($row_countryRS['COUNTRY_ID_COUNTRY'], $_POST['form_addproperty_country']))) {echo "selected=\"selected\"";} ?>><?php echo $row_countryRS['COUNTRY_NAME_COUNTRY']?></option>
              <?php
} while ($row_countryRS = mysql_fetch_assoc($countryRS));
  $rows = mysql_num_rows($countryRS);
  if($rows > 0) {
      mysql_data_seek($countryRS, 0);
	  $row_countryRS = mysql_fetch_assoc($countryRS);
  }
?>
            
            </select></td>
            </tr>
      <tr valign="baseline">
            <td align="right"  class="label">  Brief Description  :</td>
            <td>
              <textarea id="form_addproperty_briefdescription_id" style="width:673px;<?php textFieldStyle('form_addproperty',$error["form_addproperty_briefdescription"],$error); ?>" name="form_addproperty_briefdescription" cols="" rows="5"><?php echo $_POST["form_addproperty_briefdescription"]; ?></textarea>
              <?php  errMessage($error['form_addproperty_briefdescription'],'required');?>
</td>
      </tr>    <tr valign="baseline">
            <td align="right"  class="label"> Detailed Description  :</td>
            <td>
            <textarea id="form_addproperty_fulldescription" style="width:673px;<?php textFieldStyle('form_addproperty',$error["form_addproperty_fulldescription"],$error); ?>" name="form_addproperty_fulldescription" cols="" rows="5"><?php echo $_POST["form_addproperty_fulldescription"]; ?></textarea>
<?php  errMessage($error['form_addproperty_fulldescription'],'required');?>
            </td>
          </tr>
          <tr valign="baseline">
            <td align="right"  class="label"> Display In Featured Property  :</td>
            <td>
            <input type="checkbox" value="Y" name="form_addproperty_featured" id="form_addproperty_featured"  <?php if($_POST['form_addproperty_featured'] == "Y") echo "checked=\"checked\""?> />
            </td>
          </tr>
          <tr valign="baseline">
            <td align="right"  class="label"> Display In Property Of the Week :</td>
            <td>
            <input type="checkbox" value="Y" name="form_addproperty_ofweek" id="form_addproperty_featured" <?php if($_POST['form_addproperty_ofweek'] == "Y") echo "checked=\"checked\""?>/>
            </td>
          </tr>

          <tr valign="baseline">
          <td></td>
            <td >
            	<input id="form_newsletter_submit_id" name="form_newsletter_submit" type="submit" class="button" value="Submit and Next" style="width: 150px">
            	<input id="form_newsletter_save_id" name="form_newsletter_submit" type="submit" class="button" value="Save and Preview" style="width: 150px">

            	<a href="adminpropertysearch.php" style="text-decoration:none;"><input type="button" class="button" value="Cancel" /></a>
           </td>
          </tr>
                

        </table>
  <input type="hidden" name="MM_insert" value="form_addproperty" />
  </fieldset>
    </form>

<script type="text/javascript">
<!--
var sprytextfield1_form_addproperty_price = new Spry.Widget.ValidationTextField("sprytextfield1_form_addproperty_price", "real");
var sprytextfield11_form_addproperty_area = new Spry.Widget.ValidationTextField("sprytextfield11_form_addproperty_area", "integer", {isRequired:false});
var spryselect1_form_addproperty_areaunit = new Spry.Widget.ValidationSelect("spryselect1_form_addproperty_areaunit", {isRequired:false});
var sprytextfield12_form_addproperty_city = new Spry.Widget.ValidationTextField("sprytextfield12_form_addproperty_city", "none", {maxChars:100, isRequired:false});
var sprytextfield12_form_addproperty_postcode = new Spry.Widget.ValidationTextField("sprytextfield12_form_addproperty_postcode", "none", {maxChars:10});

var sprytextfield13_form_addproperty_state = new Spry.Widget.ValidationTextField("sprytextfield13_form_addproperty_state", "none", {maxChars:100, isRequired:false});
var spryselect2_form_addproperty_propertytype = new Spry.Widget.ValidationSelect("spryselect2_form_addproperty_propertytype");
var spryselect3_form_addproperty_currency = new Spry.Widget.ValidationSelect("spryselect3_form_addproperty_currency");
//-->
</script>


<script type="text/javascript">
$(function() {
	// Add markItUp! to your textarea in one line
	// $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
	$('#form_addproperty_briefdescription_id').markItUp(mySettings);

$('#form_addproperty_fulldescription').markItUp(mySettings);

	// You can add content from anywhere in your page
	// $.markItUp( { Settings } );	
	$('.add').click(function() {
 		$('#markItUp').markItUp('insert',
			{ 	openWith:'<opening tag>',
				closeWith:'<\/closing tag>',
				placeHolder:"New content"
			}
		);
 		return false;
	});
	
	// And you can add/remove markItUp! whenever you want
	// $(textarea).markItUpRemove();
	$('.toggle').click(function() {
		if ($("#markItUp.markItUpEditor").length === 1) {
 			$("#markItUp").markItUp('remove');
			$("span", this).text("get markItUp! back");
		} else {
			$('#markItUp').markItUp(mySettings);
			$("span", this).text("remove markItUp!");
		}
 		return false;
	});
});
</script>

   
    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($Area_Units_RS);

mysql_free_result($Property_Type_RS);

mysql_free_result($Currency_Type_RS);

mysql_free_result($Property_For_RS);

mysql_free_result($countryRS);

mysql_free_result($grdbRS);
?>
