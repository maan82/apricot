<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Add or Edit Property");

$colname_propDetailsRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_propDetailsRS = $_GET['PROPERTY_ID'];
}

$query_propDetailsRS = sprintf("SELECT CITY, `STATE`, COUNTRY, POSTCODE, LATITUDE, LONGITUDE, PROP_COUNTY, PROP_LANDMARK FROM property_details WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propDetailsRS, "int"));
$propDetailsRS = mysql_query($query_propDetailsRS) or die(mysql_error());
$row_propDetailsRS = mysql_fetch_assoc($propDetailsRS);
$totalRows_propDetailsRS = mysql_num_rows($propDetailsRS);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//echo $_POST["MM_update"];
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_addpropertymap")) {
//	echo "OK";
$address = $_POST['form_addpropertymap_no'].", ".$_POST['form_addpropertymap_street'].", ".$_POST['form_addpropertymap_city'].", ".$_POST['form_addpropertymap_county'].", ".$_POST['form_addpropertymap_postcode'].", ".$_POST['form_addpropertymap_locality'].", ".$_POST['form_addpropertymap_subadmin'].", ".$_POST['form_addpropertymap_admin'];

  $updateSQL = sprintf("UPDATE property_details SET LATITUDE=%s, LONGITUDE=%s, PROP_HOUSE_NO=%s, POSTCODE=%s,  CITY=%s, PROP_COUNTY=%s, PROP_STREET=%s, PROP_SUBADMIN=%s, PROP_ADMIN=%s, PROP_LOCALITY=%s, PROP_ADDRESS=%s, PROP_LANDMARK=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString($_POST['form_addpropertymap_latitude'], "double"),
                       GetSQLValueString($_POST['form_addpropertymap_longitude'], "double"),
                       GetSQLValueString($_POST['form_addpropertymap_no'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_postcode'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_city'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_county'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_street'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_subadmin'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_admin'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_locality'], "text"),
					   GetSQLValueString($address, "text"),
					   GetSQLValueString($_POST['form_addpropertymap_landmark'], "text"),
                       GetSQLValueString($_POST['form_addpropertymap_propertyID'], "int"));
//echo $updateSQL;

  //echo $updateSQL;
  $Result1 = mysql_query($updateSQL) or die(mysql_error());
  
  $propParam =  "?PROPERTY_ID=".$_POST['form_addpropertymap_propertyID'];
  $insertGoTo ="adminaddpropertyimages.php".$propParam;

	if (isset($_POST['form_newsletter_submit']) && $_POST['form_newsletter_submit'] == "Save and Preview") {  
	  $insertGoTo = "/aeon/pages/property-details.php".$propParam;
	}
  
  
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
 header(sprintf("Location: %s", $insertGoTo));

}

?>
<?php include("constants.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">
<meta content="width=device-width" name="viewport">
<link href="small-device.css" media="only screen and (max-device-width: 480px)" type="text/css" rel="stylesheet">
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>
<script src="gmap.js" type="text/javascript"></script>
<script type="text/javascript">
  // Call this function when the page has been loaded
function initialize() {
	<?php
	if(!isset($_GET['POSTCODE'])){
		$_GET['POSTCODE'] = $row_propDetailsRS['POSTCODE'];
	}
	?>
	
	<?php
		if(isset($row_propDetailsRS['LATITUDE']) && isset($row_propDetailsRS['LONGITUDE'])){
	?>
	editPropMap("<?php echo $row_propDetailsRS['LATITUDE']?>","<?php echo $row_propDetailsRS['LONGITUDE']?>","<?php echo $row_propDetailsRS['POSTCODE']?>");
	<?php } else {?>
		usePointFromPostcode("<?php echo $_GET['POSTCODE']?>", mapLoad);
	<?php }?>
}
</script>


</head>

<body onload="initialize()">
<?php include('top.php')?>

<div>
<div class="orangehead">Adding New Property </div>
  <div class="steps"> <span class="stepdone">1. Add  Details</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="stepactive">2. Set Map Location</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="steppending">3. Add Images</span>  <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="steppending">4. Preview & Publish</span></div>

  <form action="adminaddpropertymap.php" method="post" name="form_addpropertymap" enctype="application/x-www-form-urlencoded">
  <input id="form_addpropertymap_property_ID" name="form_addpropertymap_propertyID" type="hidden" value="<?php echo $_GET['PROPERTY_ID']?>"/>
  <input id="form_addpropertymap_latitudes_ID" name="form_addpropertymap_latitude" type="hidden" />
  <input id="form_addpropertymap_longitude_ID" name="form_addpropertymap_longitude" type="hidden" />

<div style="float:left;margin-left:10px">
  <label class="label">No :-<br />
  <input id="form_addpropertymap_no_ID" name="form_addpropertymap_no" type="text" style="width:50px"/></label>
  </div>
    <div style="float:left;margin-left:10px">
  <label class="label">Postcode * :- <br />
  <input style="width:100px" id="form_addpropertymap_postcode_ID" name="form_addpropertymap_postcode" type="text" value="<?php echo $row_propDetailsRS['POSTCODE']?>"/></label>
  </div>

  <div style="float:left;margin-left:10px">

  <label class="label">City  :-<br />
  <input name="form_addpropertymap_city" type="text" id="form_addpropertymap_city_ID" value="<?php echo $row_propDetailsRS['CITY']; ?>"  /></label>
    </div>
  <div style="float:left;margin-left:10px">
  <label class="label">County  :- <br />
  <input name="form_addpropertymap_county" type="text" id="form_addpropertymap_county_ID" value="<?php echo $row_propDetailsRS['PROP_COUNTY']; ?>" /></label>
  </div>
    <div style="float:left;margin-left:10px">
  <label class="label">Landmark :- <br />
  <input id="form_addpropertymap_landmark_ID" name="form_addpropertymap_landmark" type="text" value="<?php echo $row_propDetailsRS['PROP_LANDMARK']; ?>"/></label>
  </div>

  <div style="clear:both"></div>
  <div style="display:none">
  <img src="images/marker_red.png" alt="For Locality,admin, subadmin and street" width="20" height="34" align="absmiddle" style="float:left"/>
<div style="float:left;margin-left:10px">
  <label class="label">Street :-<br />
  <input id="form_addpropertymap_street_ID" name="form_addpropertymap_street" type="text" /></label>
</div>
  <div style="float:left;margin-left:10px">

  <label class="label">Sub Admin Area :-<br />
  <input id="form_addpropertymap_subadmin_ID" name="form_addpropertymap_subadmin" type="text" /></label>
    </div>
  <div style="float:left;margin-left:10px">
  <label class="label">Admin Area * :- <br />
  <input id="form_addpropertymap_admin_ID" name="form_addpropertymap_admin" type="text" /></label>
  </div>
    <div style="float:left;margin-left:10px">
  <label class="label">Locality :-<br />
    <input id="form_addpropertymap_locality_ID" name="form_addpropertymap_locality" type="text" /></label>

  </div>
</div>

  <div style="clear:both;padding:5px"></div>
    <input name="MM_update" type="hidden" value="form_addpropertymap"/>

  <a href="admineditproperty.php?PROPERTY_ID=<?php echo $_GET['PROPERTY_ID'] ;?>" style="text-decoration:none; float: right">
            		<input type="button" class="button" value="Previous" />
                </a>
  <input  style="float:right;width: 150px" id="form_newsletter_save_id" name="form_newsletter_submit" type="submit" class="button" value="Save and Preview">

  <input type="submit" value="Submit" class="button" style="float:right"/>

    </form>
    <div style="clear:both" class="label">
  <img src="images/marker_red.png" alt="For property locatiion on map" width="20" height="34" align="absmiddle" /><span style="color:red"> This is Important !</span> Please set red marker to set property location.</div>
<div style="clear:both" id="error_div_id"></div>

<div id="map" style="width: 100%; height: 400px"></div>
        <div id="pano" style="width: 100%; height: 400px"></div>
        <br />
</div>

<?php include('bottom.php')?>
</body>
</html>