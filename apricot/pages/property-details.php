<?php require_once('Connections/adestate.php');?>
<?php require_once('functions.php'); ?>
<?php require_once('constants.php');?>
<?php
if (!isset($_SESSION)) {
	  session_start();
	}

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

$colname_propRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_propRS = $_GET['PROPERTY_ID'];
}

if(strpos($_SESSION['MM_Roles'],"Add or Edit Property") === false ){ //Do nothing
$query_propRS = sprintf("SELECT * FROM property_details_website_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propRS, "int"));
} else {
$query_propRS = sprintf("SELECT * FROM user_property_owner_receiver_amount_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propRS, "int"));

}

$propRS = mysql_query($query_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);
$totalRows_propRS = mysql_num_rows($propRS);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<link href="bg_blocks.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="js/site.js" type="text/javascript"></script> 

<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

<script src="js/jquery-1.2.6.js" type="text/javascript"></script> 
<script src="js/fadeslideshow.js" type="text/javascript"></script> 
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>

<script>
function showPictures(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-pictures.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="600" width="680" frameborder="0" scrolling="no" class="block" ></iframe>';
}
function showSlideShow(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-slideshow.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="500" width="680" frameborder="0" scrolling="no"></iframe>'
}
function showFloorPlan(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-floorplan.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="600" width="680" frameborder="0" scrolling="no"></iframe>'
}
function showEPC(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-epc.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="600" width="680" frameborder="0" scrolling="no"></iframe>'
}


var pointData = new Array();
pointData[0] = {
				id : "<?php echo $row_propRS['PROPERTY_ID']; ?>",
				lat : <?php echo $row_propRS['LATITUDE']; ?>,
				lon : <?php echo $row_propRS['LONGITUDE']; ?>,
				desc : '<iframe src="propertyfadeshowformap.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="170" width="220" frameborder="0" scrolling="no"></iframe>'
			   };

function showPropOnMap() {
    pointArr = pointData;
		document.getElementById('wrapper').innerHTML='';
	document.getElementById('wrapper').innerHTML='<div id="map" style="width: 100%; height: 480px"></div>';
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		var point = new GLatLng(pointArr[0].lat,pointArr[0].lon);
		map.setCenter(point, 17, G_NORMAL_MAP);
		var openMarker = true;
		pointElement = pointArr[0];
        var marker = new GMarker(point);
		map.addOverlay(marker);
        marker.openInfoWindowHtml(pointElement.desc);
	}
}
function showPropOnStreetView() {
	pointArr = pointData;
    document.getElementById('wrapper').innerHTML='<div id="streetViewDivId" style="width: 100%; height: 480px"></div>';
	if (GBrowserIsCompatible()) {
		var point = new GLatLng(pointArr[0].lat,pointArr[0].lon);
		panoramaOptions = { latlng:point, features: {  streetView: true,    userPhotos: false  }};
		var myPano = new GStreetviewPanorama(document.getElementById("streetViewDivId"), panoramaOptions);
	}
}
function arrangeViewing(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-arrange-viewing.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="950" width="680" frameborder="0" scrolling="no" class="block"></iframe>'
}
function tellAFriend(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-tell-afriend.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" height="500" width="680" frameborder="0" scrolling="no"></iframe>'
}

</script>


</head>

<body >
<?php include('header.php'); ?>

<div id="maincontent" >
<div id="column1" >

<div class="block">

  <div>
   
<div class="submenu" style="margin:10px 0px">
    <ul>
    <li>
<a href="#" onclick="showPictures('<?php echo $_GET['PROPERTY_ID'];?>');return false;"> Pictures</a>
</li>
<li>
<a href="#" onclick="showSlideShow('<?php echo $_GET['PROPERTY_ID'];?>');return false;"> Slideshow</a>
</li>
 <?php if($row_propRS['PROPERTY_FOR_ID'] <= 2) { ?>
<li>
<a href="#" onclick="showFloorPlan('<?php echo $_GET['PROPERTY_ID'];?>');return false;"> Floorplan</a>
</li>
<li>
<a href="#" onclick="showEPC('<?php echo $_GET['PROPERTY_ID'];?>');return false;"> EPC</a>
</li>
 <?php } ?>


<li>

<a href="#" onclick="showPropOnMap();return false;"> Map View</a>
</li>
<li>

<a href="#" onclick="showPropOnStreetView();return false;"> Street View</a>
</li>
</ul>
</div>
    <div id="wrapper" style="text-align:center" ><img src="" width="680"  /></div>
 


</div>



</div>

<div class="block">

    
<div class="heading">Key Features</div>
<div>
<div style="width:460px;float:left">
    <?php echo $row_propRS['BRIEF_DESCRIPTION']; ?>
</div>
 <?php if($row_propRS['PROPERTY_FOR_ID'] <= 2) { ?>
  <div style="width:200px;float:right">
<div class="box_title">Driving Directions</div>
   <div style="padding-left:10px">
       	<form name="ddform" id="ddformid" action="http://maps.google.co.uk/maps" method="get" target="_blank" onsubmit="return showUrl(this.action,'formname')">
        <label class="label">
From Postcode :-
<input type="text" name="saddr" id="saddr"/>
</label>
            <input type="submit" value="Go" class="buttonsmall"/>
            <input type="hidden" id="daddr" name="daddr" value="<?php echo $row_propRS['POSTCODE']; ?>" />
            <input type="hidden" name="hl" value="en" /></p>
        </form>
</div>


</div>
 <?php } ?>
</div>
<div style="clear:both"> 
</div>
<div class="box_title" style="margin-top:10px">Full Details</div>


<div>
    <?php echo $row_propRS['DETAIL_DESCRIPTION']; ?>
</div>

</div>



</div>
<div id="column2" >
      <div class="block">     
  <a href="#" class="largebutton" onclick="history.go(-1);return false;"> Back To Search Results</a>
</div>

    <?php if($row_propRS['PROPERTY_FOR_ID'] <= 2) { ?>

    <div id="adminactionsdiv" class="block">
    <?php if(strpos($_SESSION['MM_Roles'],"Add or Edit Property") === false ){ //Do nothing?>

<?php }else{?>
    
  <div>
  <div class="heading">Admin Actions</div>
<?php include('../admin/adminpropertyactions.php');?>
</div>


<?php }?>
    </div>
       

  <div id="propdetailsdiv" class="block">
    
<div class="heading">Property Details</div>
                <?php include('inc-property-desc-list-view.php'); ?>
<div style="text-align:center">
<img id="slidingProduct<?php echo $_GET['PROPERTY_ID']; ?>" src="<?php echo $row_propRS['SLIDE_PIC_PATH']?>"  alt="" title="" border="0"/>
</div>
 
<div style="padding-left:18px">

<a href="#" onclick="arrangeViewing('ok');return false;" class="mediumbutton">  Arrange Viewing</a>
<a href="#"  onclick="tellAFriend('ok');return false;" class="mediumbutton"> Tell A Friend</a>
<a  target="_top"  class="mediumbutton" href="<?php include('inc-email-details.php'); ?>">Email Details</a>

	                

</a>
</div>



  </div>


<div class="block">
 
    <div class="heading"> <img src="images/Info-icon2.png"  height="20" align="absmiddle" class="box_img"/>Local Amenities</div>
<div class="actions_list" >
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Schools&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Schools</span></a>
    <a class="mediumbutton" class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Bus Stop&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Bus Stops</span></a> 
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Railway&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Railway Stations</span></a>
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Doctors&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Doctors</span></a> 
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Dentists&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Dentists</span></a> 
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Hospitals&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Hospitals</span></a> 
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Supermarkets&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Supermarkets</span></a> 
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Restaurants&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Restaurants</span></a> 
    <a class="mediumbutton" href="http://local.google.co.uk/local?f=l&amp;hl=en&amp;q=category:+Takeaways&amp;om=1&amp;near=<?php echo $row_propRS['POSTCODE']; ?>" target="_blank" onclick="return showUrl(this.href);"><span>Takeaways</span></a> 
  
  </div>


</div>

<?php }else{?>
  <div class="block">     
  <a href="contact-us.php" class="largebutton"> Contact Us</a>
</div>    
    <?php }?>
    
</div>

</div>

<?php include('footer.php'); ?>



<!-- end of main_container -->
<script type="text/javascript">
<!--

showPictures('propID');
//-->
</script>

<?php if(isset($_GET['PAGE'])){?>
<script type="text/javascript">
<!--

<?php if($_GET['PAGE'] == "AV"){?>
  arrangeViewing('propID');
<?php } else if ($_GET['PAGE'] == "TF") {?>
  tellAFriend('propID');
<?php } else if($_GET['PAGE'] == "PICS"){?>
   showPictures('propID');
<?php }?>
//-->
</script>
<?php }?>

</body>
</html>
<?php
mysql_free_result($propRS);
?>