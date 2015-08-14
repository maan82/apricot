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

$colname_picturesRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_picturesRS = $_GET['PROPERTY_ID'];
}

$query_picturesRS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s and (TITLE IS NULL or TITLE IN('STC'))  ORDER BY IS_MAIN DESC", GetSQLValueString($colname_picturesRS, "int"));
$picturesRS = mysql_query($query_picturesRS) or die(mysql_error());
$row_picturesRS = mysql_fetch_assoc($picturesRS);
$totalRows_picturesRS = mysql_num_rows($picturesRS);
?>

<!DOCTYPE html>

<html>

<!--
	This is a jQuery Tools standalone demo. Feel free to copy/paste.
	                                                         
	http://flowplayer.org/tools/demos/
	
	Do *not* reference CSS files and images from flowplayer.org when in production  

	Enjoy!
-->

<head>
	<title>jQuery Tools standalone demo</title>

	<!-- include the Tools -->
	<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js"></script>

	 

	<!-- standalone page styling (can be removed) -->
	<link rel="stylesheet" type="text/css" href="galleryfiles/standalone.css"/>	


	<link rel="stylesheet" type="text/css" href="galleryfiles/scrollable-horizontal.css" />
	<link rel="stylesheet" type="text/css" href="galleryfiles/scrollable-buttons.css" />

	<style>
	

	/* styling for the image wrapper  */
	#image_wrap {
		/* dimensions */
		width:650px;
		margin-bottom:20px;
		padding:7px 0;

		/* centered */
		text-align:center;

		/* some "skinning" */
		background-color:#efefef;
		border:2px solid #fff;
		outline:1px solid #ddd;
		-moz-ouline-radius:4px;
	}
	</style>
</head>

<body style="padding:10px 5px" >



<!-- wrapper element for the large image -->
<div style="height:368px" id="image_wrap">

	<!-- Initially the image is a simple 1x1 pixel transparent GIF -->
	<img src="http://static.flowplayer.org/tools/img/blank.gif"  />

</div>


<!-- HTML structures -->


<!-- "previous page" action -->
<a class="prev browse left"></a>

<!-- root element for scrollable -->
<div class="scrollable">   
   
   <!-- root element for the items -->
   <div class="items">
   
<?php $i=1;  do { ?>
<?php if($row_picturesRS['TITLE']=='STC')  continue; ?>
<?php if($i==1)  echo "<div>"; ?>
      
<div style="width:125px;float:left;margin:0px ;border:1px solid #F00">
        <div style="padding-left:20px">SOme sfjd</div>
         <div> <img  src="<?php echo $row_picturesRS['THUMB_PIC_PATH']; ?>" /></div>
      </div>   
<?php if($i==4)  {echo "</div>"; $i = 1;} else $i++; ?>
  
  <?php } while ($row_picturesRS = mysql_fetch_assoc($picturesRS)); if($i!=1)  {echo "</div>"; $i = 1;}?>


      
   </div>
   
</div>

<!-- "next page" action -->
<a class="next browse right"></a>

<script>
$(function() {

$(".scrollable").scrollable();

$(".items img").click(function() {

	// see if same thumb is being clicked
	if ($(this).hasClass("active")) { return; }

	// calclulate large image's URL based on the thumbnail URL (flickr specific)
	var url = $(this).attr("src").replace("THUMB", "FULL");

	// get handle to element that wraps the image and make it semi-transparent
	var wrap = $("#image_wrap").fadeTo("medium", 0.5);

	// the large image from www.flickr.com
	var img = new Image();


	// call this function after it's loaded
	img.onload = function() {

		// make wrapper fully visible
		wrap.fadeTo("fast", 1);

		// change the image
		wrap.find("img").attr("src", url);

	};

	// begin loading the image from www.flickr.com
	img.src = url;

	// activate item
	$(".items img").removeClass("active");
	$(this).addClass("active");

// when page loads simulate a "click" on the first image
}).filter(":first").click();
});
</script>


</body>

</html>
