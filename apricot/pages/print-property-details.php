<?php
require_once ('Connections/adestate.php');
?>
<?php
	require_once ('constants.php');
?>
<?php
if (!isset($_SESSION)) {
	session_start();
}

if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		}

		$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

		switch ($theType) {
			case "text" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "long" :
			case "int" :
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double" :
				$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
				break;
			case "date" :
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined" :
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

if (strpos($_SESSION['MM_Roles'], "Add or Edit Property") === false) {//Do nothing
	$query_propRS = sprintf("SELECT * FROM property_details_website_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propRS, "int"));
} else {
	$query_propRS = sprintf("SELECT * FROM user_property_owner_receiver_amount_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propRS, "int"));

}

$propRS = mysql_query($query_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);
$totalRows_propRS = mysql_num_rows($propRS);

$colname_picturesRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
	$colname_picturesRS = $_GET['PROPERTY_ID'];
}

$query_picturesRS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s and  (TITLE IS NULL OR TITLE NOT IN('FLOORPLAN', 'EPC')) ORDER BY IS_MAIN DESC", GetSQLValueString($colname_picturesRS, "int"));
$picturesRS = mysql_query($query_picturesRS) or die(mysql_error());
$row_picturesRS = mysql_fetch_assoc($picturesRS);
$totalRows_picturesRS = mysql_num_rows($picturesRS);

$query_fp_RS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s and  (TITLE IN('FLOORPLAN'))", GetSQLValueString($colname_picturesRS, "int"));
$fp_RS = mysql_query($query_fp_RS) or die(mysql_error());
$row_fp_RS = mysql_fetch_assoc($fp_RS);
$totalRows_fp_RS = mysql_num_rows($fp_RS);

$query_epc_RS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s and  (TITLE IN('EPC'))", GetSQLValueString($colname_picturesRS, "int"));
$epc_RS = mysql_query($query_epc_RS) or die(mysql_error());
$row_epc_RS = mysql_fetch_assoc($epc_RS);
$totalRows_epc_RS = mysql_num_rows($epc_RS);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php
		include ('meta-title.php');
 ?>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" type="text/css" href="bg_blocks.css" />
		<script src="js/site.js" type="text/javascript"></script>

		<script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
		<link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />

		<script src="js/jquery-1.2.6.js" type="text/javascript"></script>
		<script src="js/fadeslideshow.js" type="text/javascript"></script>
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>
		<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>

		<script>
			function showPictures(propID){
document.getElementById('wrapper').innerHTML='<iframe src="property-pictures.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>
	" height="600" width="680" frameborder="0" scrolling="no" ></iframe>';
	}
	function showSlideShow(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-slideshow.php?PROPERTY_ID=
<?php echo $row_propRS['PROPERTY_ID']; ?>
	" height="500" width="680" frameborder="0" scrolling="no"></iframe>'
	}
	var pointData = new Array();
	pointData[0] = {
	id : "
<?php echo $row_propRS['PROPERTY_ID']; ?>
	",
	lat : 
 <?php echo $row_propRS['LATITUDE']; ?>
	,
	lon : 
 <?php echo $row_propRS['LONGITUDE']; ?>
	,
	desc : '<iframe src="propertyfadeshowformap.php?PROPERTY_ID=
<?php echo $row_propRS['PROPERTY_ID']; ?>
	" height="170" width="220" frameborder="0" scrolling="no"></iframe>'
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
	map.setCenter(point, 16, G_HYBRID_MAP);
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
	document.getElementById('wrapper').innerHTML='<iframe src="property-arrange-viewing.php?PROPERTY_ID=
<?php echo $row_propRS['PROPERTY_ID']; ?>
	" height="950" width="680" frameborder="0" scrolling="no" ></iframe>'
	}
	function tellAFriend(propID){
	document.getElementById('wrapper').innerHTML='<iframe src="property-tell-afriend.php?PROPERTY_ID=
<?php echo $row_propRS['PROPERTY_ID']; ?>
	" height="500" width="680" frameborder="0" scrolling="no"></iframe>'
	}

		</script>
	</head>
	<body style="background:none;" >
		<div id="main_container" style="width: 620px; margin: 0px auto;color: black">

			<div id="main_content" style="width:620px">
				<style>
					.menu_wrapper {
						display: none
					}
					#head {
						border-bottom: 2px solid #666
					}
					h2 {
						color: black;
					}
				</style>
				<div>
					<div style="width: 470px;float: left;" >
						<img src="images/new-images/aeon-logo-yellow-on-black.jpg" width="470" align="top" />
				      <div style="margin-top: 5px;padding-left: 5px; color: black; font-size: 15px; font-weight: bold "   >
					      <div><?php echo $ADDRESS_LINE1; ?>, <?php echo $ADDRESS_LINE2; ?></div>
					      <div>Phone : <?php echo $PHONE; ?> | Fax: <?php echo $FAX; ?></div>
					      <div><?php if ($row_propRS['PROPERTY_FOR_ID'] == 2) echo $EMAIL_LETTINGS; else echo $EMAIL_SALES ; ?></div>
				      </div>
				    </div>
					<div style="width: 150px; float: left;"  >
					<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?php echo urlencode("http://www.aeonestates.com/aeon/pages/property-details.php?PROPERTY_ID=".$row_propRS['PROPERTY_ID']);?>" align="top" />
				    
				    </div>
				</div>
				<div style="padding-top: 8px; clear: both; width=100%; border-bottom: 3px solid #F4DD42;"></div>
				<div>
				<div style="float:left;width:600px">

					<div >
						<table class="content_bg" cellpadding="0" cellspacing="0"  style="width:600px">
							<tr style="" height="9px">
								<td class="content_bg_tl" style="background-image:url(images/content_bg_top_left_wt.gif)"></td>
								<td class="content_bg_tr" style="background-image:url(images/content_bg_top_right_wt.gif)"></td>
							</tr>
							<tr>
								<td   class="content_bg_cl" style="background-image:url(images/content_bg_center_left_wt.gif)">
								<div>
									                <div><a style="color: #000000;" href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>">
                <span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?></span>     <?php if ($row_propRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propRS['BEDROOMS']." Bed" ; ?> <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?>
                <?php echo $row_propRS['CITY']; ?><br />
          <span> &pound;<?php echo number_format( $row_propRS['PRICE']); ?>   <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></span></a> </div>

								</div>
								<div >
									<img src="<?php echo $row_picturesRS['FULL_PIC_PATH']; ?>"  align="middle" />
								</div><div class="wt_head_text" style="padding-top:20px"></div>
								<div>
									<?php echo $row_propRS['BRIEF_DESCRIPTION']; ?>
								</div><div class="wt_head_text" style="margin-top:10px"></div>
								<div>
									<?php echo $row_propRS['DETAIL_DESCRIPTION']; ?>
								</div> 
								<?php do { ?>
								<div style="padding-top:10px; width: 260px; margin-right: 5px; float: left;">

									<img width="250"  src="<?php echo $row_picturesRS['FULL_PIC_PATH']; ?>" />
								</div>
								<?php } while ($row_picturesRS = mysql_fetch_assoc($picturesRS)); ?>
								<div style="clear: both;"/>
								<?php do { ?>
									
								<div style="padding-top:10px; ">

									<img   src="<?php echo $row_fp_RS['FULL_PIC_PATH']; ?>" />
								</div>
								<?php  } while ($row_fp_RS = mysql_fetch_assoc($fp_RS)); ?>
								<div style="clear: both;"/>

								<?php do { ?>
									
								<div style="padding-top:10px; ">

									<img   src="<?php echo $row_epc_RS['FULL_PIC_PATH']; ?>" />
								</div>
								<?php }  while ($row_epc_RS = mysql_fetch_assoc($epc_RS)); ?>
								<div style="clear: both;"/>

								<div style="text-align:center;padding-top:10px;margin-top: 10px">
									<img  src="http://maps.google.com/maps/api/staticmap?zoom=14&size=600x400&format=jpg&maptype=roadmap&markers=color:red|color:red|label:C|<?php echo $row_propRS['LATITUDE'] ?>,<?php echo $row_propRS['LONGITUDE'] ?>&sensor=false" />

								</div></td>
								<td class="content_bg_cr" style="background-image:url(images/content_bg_center_right_wt.gif)"></td>

							</tr>
							<tr>
								<td class="content_bg_bl" style="background-image:url(images/content_bg_bottom_left_wt.gif)"></td>
								<td class="content_bg_br" style="background-image:url(images/content_bg_bottom_right_wt.gif)"></td>
							</tr>
						</table>
					</div>

				</div>

				<div style="clear:both">
					&nbsp;
				</div>

				<div>

				</div>

			</div>
			<!-- end of main_content -->

		</div>
		<!-- end of main_container -->
		<script type="text/javascript">
			<!--showPictures('propID');
			//-->
		</script>

		<?php if(isset($_GET['PAGE'])){?>
		<script type="text/javascript">
			<!--

<?php if($_GET['PAGE'] == "AV"){?>arrangeViewing('propID');<?php } else if ($_GET['PAGE'] == "TF") { ?>tellAFriend('propID');<?php } else if($_GET['PAGE'] == "PICS"){ ?>
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
