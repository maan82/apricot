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

$colname_Image_List_RS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_Image_List_RS = $_GET['PROPERTY_ID'];
}
else if (isset($_POST['PROPERTY_ID'])) {
  $colname_Image_List_RS = $_POST['PROPERTY_ID'];
}


$query_Image_List_RS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s", GetSQLValueString($colname_Image_List_RS, "int"));
$Image_List_RS = mysql_query($query_Image_List_RS) or die(mysql_error());
$row_Image_List_RS = mysql_fetch_assoc($Image_List_RS);
$totalRows_Image_List_RS = mysql_num_rows($Image_List_RS);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<script src="js/jquery-1.2.6.js" type="text/javascript"></script>
<script src="js/fadeslideshow.js" type="text/javascript"></script>

</head>
<body >
<div id="fadeshow" style="background:#FFF"></div>
<script type="text/javascript">
<!--
var mygallery2=new fadeSlideShow({
	wrapperid: "fadeshow", //ID of blank DIV on page to house Slideshow
	dimensions: [200, 150], //width/height of gallery in pixels. Should reflect dimensions of largest image
	imagearray: [
      <?php $i = 0; do { ?>
        ["<?php echo $row_Image_List_RS['SLIDE_PIC_PATH']; ?>","","",""]
		<?php if($i < $totalRows_Image_List_RS-1) { ?>
		,
		<?php } ?>
        <?php $i = $i + 1; } while ($row_Image_List_RS = mysql_fetch_assoc($Image_List_RS)); ?>				 
        //<--no trailing comma after very last image element!
	],
	displaymode: {type:'auto', pause:2500, cycles:0, wraparound:false},
	persist: false, //remember last viewed slide and recall within same session?
	fadeduration: 500, //transition duration (milliseconds)
	descreveal: "always",
	togglerid: ""
})
//-->
</script>

</body>
</html>
<?php
mysql_free_result($Image_List_RS);
?>
