<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Add or Edit Property");
$colname_propRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_propRS = $_GET['PROPERTY_ID'];
}
$query_propRS = sprintf("SELECT * FROM property_details_view WHERE PROPERTY_ID = %s", GetSQLValueString($colname_propRS, "int"));
$propRS = mysql_query($query_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);
$totalRows_propRS = mysql_num_rows($propRS);

$colname_saleRS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_saleRS = $_GET['PROPERTY_ID'];
}
$query_saleRS = sprintf("SELECT * FROM sale_details WHERE SOLD_PROPERTY_ID_SD = %s", GetSQLValueString($colname_saleRS, "int"));
$saleRS = mysql_query($query_saleRS) or die(mysql_error());
$row_saleRS = mysql_fetch_assoc($saleRS);
$totalRows_saleRS = mysql_num_rows($saleRS);

/** 
 * Put logo on low right jpeg image 
 * used stefan's script for position 
 **/ 
 function addSTC($propRS){
	include('Connections/adestate.php'); 
//	echo "thmb =".$propRS['THUMB_PIC_PATH'];
	$logo_file = "images/soldstc.png"; 
	$image_file = $propRS['THUMB_PIC_PATH']; 
    $image_info = getimagesize($image_file);
	//echo "mannestateDB = ".$mannestateDB;
	$picture_ID = getSeqNextVal( $mannestateDB, $database_mannestateDB, "sequence_picture_id" );
    $uploadfilename = $picture_ID.'_STC_THUMB';
    $photo = "";
    $image_type = $image_info[2];
	//echo "type = ".$image_info;
	  if( $image_type == IMAGETYPE_JPEG ) {
		 $photo = imagecreatefromjpeg($image_file);
		 $uploadfile = '../property_images/'.$propRS['PROPERTY_ID'].'/'.$uploadfilename.".jpg";
	  } elseif( $image_type == IMAGETYPE_GIF ) {
		 $photo = imagecreatefromgif($image_file);
 		 $uploadfile = '../property_images/'.$propRS['PROPERTY_ID'].'/'.$uploadfilename.".gif";
	  } elseif( $image_type == IMAGETYPE_PNG ) {
		 $photo = imagecreatefrompng($image_file);
		 $uploadfile = '../property_images/'.$propRS['PROPERTY_ID'].'/'.$uploadfilename.".png";
	  }
    //echo "photo".$photo;
	$fotoW = imagesx($photo); 
	$fotoH = imagesy($photo); 
	$targetfile = $uploadfile; 

    $logoImage = imagecreatefrompng($logo_file); 
	$logoW = imagesx($logoImage); 
	$logoH = imagesy($logoImage); 
	$photoFrame = imagecreatetruecolor($fotoW,$fotoH); 
	$dest_x = $fotoW - $logoW; 
	$dest_y = $fotoH - $logoH; 
	imagecopyresampled($photoFrame, $photo, 0, 0, 0, 0, $fotoW, $fotoH, $fotoW, $fotoH); 
	imagecopy($photoFrame, $logoImage, 0, 0, 0, 0, $logoW, $logoH); 
	imagejpeg($photoFrame, $targetfile);  
//	echo "dest_x".$dest_x;
	
	$image_file = $propRS['SLIDE_PIC_PATH']; 
    $uploadfilename = $picture_ID.'_STC_SLIDE';
	  if( $image_type == IMAGETYPE_JPEG ) {
		 $photo = imagecreatefromjpeg($image_file);
		 $uploadfile = '../property_images/'.$propRS['PROPERTY_ID'].'/'.$uploadfilename.".jpg";
	  } elseif( $image_type == IMAGETYPE_GIF ) {
		 $photo = imagecreatefromgif($image_file);
 		 $uploadfile = '../property_images/'.$propRS['PROPERTY_ID'].'/'.$uploadfilename.".gif";
	  } elseif( $image_type == IMAGETYPE_PNG ) {
		 $photo = imagecreatefrompng($image_file);
		 $uploadfile = '../property_images/'.$propRS['PROPERTY_ID'].'/'.$uploadfilename.".png";
	  }
	$fotoW = imagesx($photo); 
	$fotoH = imagesy($photo); 
	$slidetargetfile = $uploadfile; 

    $logoImage = imagecreatefrompng($logo_file); 
	$logoW = imagesx($logoImage); 
	$logoH = imagesy($logoImage); 
	$photoFrame = imagecreatetruecolor($fotoW,$fotoH); 
	$dest_x = $fotoW - $logoW; 
	$dest_y = $fotoH - $logoH; 
	imagecopyresampled($photoFrame, $photo, 0, 0, 0, 0, $fotoW, $fotoH, $fotoW, $fotoH); 
	imagecopy($photoFrame, $logoImage, 0, 0, 0, 0, $logoW, $logoH); 
	imagejpeg($photoFrame, $slidetargetfile);  
	
	
    $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString("N", "text"),
								   GetSQLValueString($propRS['PROPERTY_ID'], "int"));
    $mainRS = mysql_query($updateSQL) or die(mysql_error());
	//echo $updateSQL;
	$insertSQL = sprintf("INSERT INTO pictures ( PICTURE_ID, PROPERTY_ID, IS_MAIN, TITLE, COMMENTS, THUMB_PIC_PATH, SLIDE_PIC_PATH, FULL_PIC_PATH,    ORIGINAL_PIC_PATH) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   GetSQLValueString($picture_ID, "double"),																												               GetSQLValueString($propRS['PROPERTY_ID'], "int"),
			   GetSQLValueString('Y', "text"),
			   GetSQLValueString("STC", "text"),
			   GetSQLValueString("", "text"),
			   GetSQLValueString($targetfile, "text"),
			   GetSQLValueString($slidetargetfile, "text"),
			   GetSQLValueString($slidetargetfile, "text"),
			   GetSQLValueString($slidetargetfile, "text"));
	//echo $insertSQL;
    $picRS = mysql_query($insertSQL) or die(mysql_error());
//echo "STC added";
 }

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_rentdetails")) {
	
	if($_POST["salestatus"] == 'STCAR'){
		  $insertSQL = sprintf("INSERT INTO auto_action (ACTION_TYPE, ACTION_ON, ACTION_TIME) VALUES (%s, %s, %s)",
					   GetSQLValueString(1, "int"),																																					                       GetSQLValueString($_POST['PROPERTY_ID'], "int"),
                       GetSQLValueString($row_saleRS['COMPLETION_DATE'] , "date"));
		  $actionRS = mysql_query($insertSQL) or die(mysql_error());
		  addSTC($row_propRS);
	      $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString(3, "int"),
								   GetSQLValueString($row_propRS['PROPERTY_ID'], "int"));
		  $mainRS = mysql_query($updateSQL) or die(mysql_error());
		 // echo $updateSQL;
	} else if($_POST["salestatus"] == 'STCAN'){
		  $insertSQL = sprintf("INSERT INTO auto_action (ACTION_TYPE, ACTION_ON, ACTION_TIME) VALUES (%s, %s, %s)",
					   GetSQLValueString(2, "int"),																																					                       GetSQLValueString($_POST['PROPERTY_ID'], "int"),
			   		   GetSQLValueString($row_saleRS['COMPLETION_DATE'] , "date"));
		  $actionRS = mysql_query($insertSQL) or die(mysql_error());
		  addSTC($row_propRS);
	      $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString(6, "int"),
								   GetSQLValueString($row_propRS['PROPERTY_ID'], "int"));
		  $mainRS = mysql_query($updateSQL) or die(mysql_error());
	} else if($_POST["salestatus"] == 'AR'){
		  $insertSQL = sprintf("INSERT INTO auto_action (ACTION_TYPE, ACTION_ON, ACTION_TIME) VALUES (%s, %s, %s)",
			   GetSQLValueString(1, "int"),																																					               GetSQLValueString($_POST['PROPERTY_ID'], "int"),
			   GetSQLValueString($row_saleRS['COMPLETION_DATE'] , "date"));
		  $actionRS = mysql_query($insertSQL) or die(mysql_error());
	      $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString(7, "int"),
								   GetSQLValueString($row_propRS['PROPERTY_ID'], "int"));
		  $mainRS = mysql_query($updateSQL) or die(mysql_error());
	} else if($_POST["salestatus"] == 'AN'){
 		  $insertSQL = sprintf("INSERT INTO auto_action (ACTION_TYPE, ACTION_ON, ACTION_TIME) VALUES (%s, %s, %s)",
			   GetSQLValueString(2, "int"),																																					               GetSQLValueString($_POST['PROPERTY_ID'], "int"),
			   GetSQLValueString($row_saleRS['COMPLETION_DATE'] , "date"));
	  $actionRS = mysql_query($insertSQL) or die(mysql_error());
	      $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString(8, "int"),
								   GetSQLValueString($row_propRS['PROPERTY_ID'], "int"));
		  $mainRS = mysql_query($updateSQL) or die(mysql_error());
	} else if($_POST["salestatus"] == 'RM'){
	      $updateSQL = sprintf("UPDATE property_details SET STATUS_ID=%s WHERE PROPERTY_ID=%s",
						           GetSQLValueString(4, "int"),
								   GetSQLValueString($row_propRS['PROPERTY_ID'], "int"));
		  $mainRS = mysql_query($updateSQL) or die(mysql_error());
	} 
		   $insertGoTo = "adminpropertysearch.php?MESSAGE=PROPSOLD";
			//header(sprintf("Location: %s", $insertGoTo));
		   if (!headers_sent($filename, $linenum)) {
				header(sprintf("Location: %s", $insertGoTo));
				exit;
			
			// You would most likely trigger an error here.
			} else {
			
				echo "Headers already sent in $filename on line $linenum\n" .
					  "Cannot redirect, for now please click this <a " .
					  "href=\"http://www.example.com\">link</a> instead\n";
				exit;
			}

	//echo "Header sent";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">

<!--end of Spry-->
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />

<script src="datepicker/datepicker.js" type="text/javascript"></script>

<link href="datepicker/datepicker.css" rel="stylesheet" type="text/css" />
<script src="js/nicEdit.js" type="text/javascript"></script>

<script type="text/javascript">
function submitForm(formID){
	document.getElementById(formID).submit();
}

</script>



</head>

<body >
<?php include('top.php')?>
    <div >
   <div class="orangehead">Selling Property</div>
  
<div>
 <div style="clear:both"></div>
</div>

<div class="orangehead">Website Updation</div>




<div id="new_client_form_div" >


<form action="<?php echo $editFormAction; ?>" class="form" method="POST" name="form_rentdetails" id="form_rentdetails">
<input name="PROPERTY_ID" type="hidden" value="<?php echo $_GET['PROPERTY_ID'] ?>" />
  <fieldset>
  <legend><span class="lbbhead" style="border-bottom:none">Website Updation Form</span>
  </legend>
  <table align="center" class="form_table" cellpadding="5px" cellspacing="0">
    <tr>
    <td colspan="2" >
      <p><br />
           <label class="label">
          <input type="radio" name="salestatus" value="STCAR" id="salestatus_1" checked="checked"/>
          Put (SOLD STC Logo) .</label>
        
              
        <br /><br />
          <label class="label">
          <input type="radio" name="salestatus" value="RM" id="salestatus_0" />
          Remove from website now.</label>

        <br /><br />

      </p></td>
    </tr>
    <tr valign="baseline">
      <td  align="right" width="50%">&nbsp;</td>
      <td><input  type="submit" class="button" value="Submit" ></td>
    </tr>
  </table>
  </fieldset>
  <input type="hidden" name="MM_insert" value="form_rentdetails" />
</form>


</div>


   
    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($propRS);

mysql_free_result($saleRS);
?>