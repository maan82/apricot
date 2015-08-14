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

if (isset($_GET['PICTURE_ID']) ) {
  $colname_picturesID = $_GET['PICTURE_ID'];
	$query_fp_Img_RS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s and TITLE IN('EPC') and PICTURE_ID = %s ORDER BY COMMENTS DESC",GetSQLValueString($colname_picturesRS, "int"),GetSQLValueString($colname_picturesID, "int"));   
$fp_Img_RS = mysql_query($query_fp_Img_RS) or die(mysql_error());
$row_fp_Img_RS = mysql_fetch_assoc($fp_Img_RS);
$totalRows_fp_Img_RS = mysql_num_rows($fp_Img_RS);
	
} 

$query_fp_RS = sprintf("SELECT * FROM pictures WHERE PROPERTY_ID = %s and TITLE IN('EPC') ORDER BY COMMENTS ASC", GetSQLValueString($colname_picturesRS, "int"));
$fp_RS = mysql_query($query_fp_RS) or die(mysql_error());
$row_fp_RS = mysql_fetch_assoc($fp_RS);
$totalRows_fp_RS = mysql_num_rows($fp_RS);



//echo $query_fp_RS;


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="bg_blocks.css" />
<link rel="stylesheet" href="floorplan/resources/jquery-ui-1.7.1.custom.css" type="text/css" media="screen" /> 
<link rel="stylesheet" href="floorplan/resources/jquery.gzoom.css" type="text/css" media="screen" /> 
	  <script type="text/javascript" src="floorplan/resources/jquery-1.3.2.min.js"></script> 
	  <script type="text/javascript" src="floorplan/resources/ui.core.min.js"></script> 
	  <script type="text/javascript" src="floorplan/resources/ui.slider.min.js"></script> 
	  <script type="text/javascript" src="floorplan/resources/jquery.mousewheel.js"></script> <!-- optional --> 
	  <script type="text/javascript" src="floorplan/resources/jquery.gzoom.js"></script> 


</head>
<body class="block">



<?php if($totalRows_fp_RS > 0) {?>
<div >
 <div class="box_title">EPC & Other Information </div>
 <div style="float:right">
 <form id="floorformId" method="get" action="property-epc.php" >
 <input name="PROPERTY_ID" type="hidden" value="<?php echo $_GET['PROPERTY_ID'] ?>" />
<span class="label">Title :- </span> <select style="width:180px" name="PICTURE_ID" onchange="document.getElementById('floorformId').submit()">
   <?php
do {  
?>
   <option value="<?php echo $row_fp_RS['PICTURE_ID']?>"<?php if (!(strcmp($row_fp_RS['PICTURE_ID'], $_GET['PICTURE_ID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_fp_RS['COMMENTS']?></option>
   <?php
} while ($row_fp_RS = mysql_fetch_assoc($fp_RS));
  $rows = mysql_num_rows($fp_RS);
  if($rows > 0) {
      mysql_data_seek($fp_RS, 0);
	  $row_fp_RS = mysql_fetch_assoc($fp_RS);
  }
?>
 </select>
 </form>
 </div>
 <div style="clear:both">
 
	
<div id="wrapp" > 
			
 
			<div id="zoom04" class="zoom"> 
           <?php if (isset($_GET['PICTURE_ID']) ) { ?>
           	<img src="<?php echo $row_fp_Img_RS['ORIGINAL_PIC_PATH']; ?>"  title="Title : <?php echo $row_fp_Img_RS['COMMENTS']; ?>" /> 
           <?php } else { ?>
				<img src="<?php echo $row_fp_RS['ORIGINAL_PIC_PATH']; ?>"  title="Title : <?php echo $row_fp_RS['COMMENTS']; ?>" /> 
                <?php } ?>
</div> 
 			<br style="clear:both" /> 
 
			 
			
  
  		
  	</div>
 
  	<script type= "text/javascript"> 
  	  /*<![CDATA[*/
    	$(function() {
    		$("#zoom04").gzoom({
							   
           <?php if (isset($_GET['PICTURE_ID']) ) { ?>
    			sW: <?php echo $row_fp_Img_RS['FULL_WIDTH']; ?>,
  	  			sH: <?php echo $row_fp_Img_RS['FULL_HEIGHT']; ?>,
  		  		lW: <?php echo $row_fp_Img_RS['ORIGINAL_WIDTH']; ?>,
  			  	lH: <?php echo $row_fp_Img_RS['ORIGINAL_HEIGHT']; ?>, 

           <?php } else { ?>
    			sW: <?php echo $row_fp_RS['FULL_WIDTH']; ?>,
  	  			sH: <?php echo $row_fp_RS['FULL_HEIGHT']; ?>,
  		  		lW: <?php echo $row_fp_RS['ORIGINAL_WIDTH']; ?>,
  			  	lH: <?php echo $row_fp_RS['ORIGINAL_HEIGHT']; ?>, 
            <?php } ?>

  			  	lightbox: true, 
  			  	zoomIcon: 'floorplan/resources/gtk-zoom-in.png'
      	});
    	});
  	  /*]]>*/
  	</script> 
 </div>

</div>
<?php } else {?>
    <div class="label">
		<img src="images/info.png" alt="Information" width="32" height="32" align="absmiddle"> EPC not uploaded for this property.
<div class="clear"></div>
    </div>
           

<?php }?>



</body>
</html>
<?php
mysql_free_result($fp_RS);
?>
