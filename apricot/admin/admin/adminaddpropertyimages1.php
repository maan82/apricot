<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Add or Edit Property");

 require('imageresize.php'); 
 

 
 ?>

<?php  
$form_adminaddprojectimages_Action = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $form_adminaddprojectimages_Action .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_adminaddprojectimages")) 
{
	$form_adminaddprojectimages_Action = $_SERVER['PHP_SELF']."?PROPERTY_ID=".$_POST['PROPERTY_ID'];
	//Upload Images
	$uploaddir = '../property_images/';
	$propdir = $uploaddir.$_POST['PROPERTY_ID'];
	if(!file_exists($propdir))
		mkdir($propdir);
	
    $counter = 0;
	foreach ($_FILES["userfile"]["error"] as $key => $error) 
	{
			/* File Checking */
			if ($_FILES['userfile']['size'][$key] > 0)
			{
				if ($_FILES['userfile']['size'][$key] > 2*1024*1024)
					$errorMsg[$_FILES['userfile']['name'][$key]] = 'FileMaxLength';
				
				if ($_FILES['userfile']['type'][$key] == "image/gif"){$fileext = '.gif';}
				else if ($_FILES['userfile']['type'][$key] == "image/jpg"){$fileext = '.jpg';}
				else if ($_FILES['userfile']['type'][$key] == "image/jpeg"){$fileext = '.jpeg';}
				else if ($_FILES['userfile']['type'][$key] == "image/bmp"){$fileext = '.bmp';}
				else if ($_FILES['userfile']['type'][$key] == "image/png"){$fileext = '.png';}
				else
					 $errorMsg[$_FILES['userfile']['name'][$key]] = 'FileFormat';
			}
	
	
			if ($error == UPLOAD_ERR_OK && !$$errorMsg[$_FILES['userfile']['name'][$key]]) 
			{
				$mainimg = 'N';
					if(isset($_POST['defaultimageindex']) && $_POST['defaultimageindex'] != '' && ($_POST['defaultimageindex'] == $counter))
					{
						$mainimg = 'Y';
					}
			   $uid = uniqid();

			   $picture_ID = getSeqNextVal( $sehyogDB, $database_sehyogDB, "sequence_picture_id" );
			   $uploadfilename = $picture_ID.'_ORIG_'.$uid .$fileext;
			   $uploadfile = $propdir.'/'.$uploadfilename;
			   move_uploaded_file($_FILES["userfile"]["tmp_name"][$key],$uploadfile) or die("Problems with upload");
			   
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $thumbpath = $propdir.'/'.$picture_ID.'_THUMB_'.$uid .$fileext;
			   $image->resizeToThumb();
			   $image->save($thumbpath);
		
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $slidepath = $propdir.'/'.$picture_ID.'_SLIDE_'.$uid .$fileext;
			   $image->resizeToSlide();
			   $image->save($slidepath);
			   
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $fullpath = $propdir.'/'.$picture_ID.'_FULL_'.$uid .$fileext;
			   $image->resizeToFull();
			   $image->save($fullpath);
			   mysql_query("START TRANSACTION");
					if($mainimg == 'Y')
					{ //If Main image is set let us set previous main image to N
					  $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
								   GetSQLValueString("N", "text"),
								   GetSQLValueString($_POST['PROPERTY_ID'], "int"));

     				   $Result1 = mysql_query($updateSQL) or die(mysql_error());
					}
	
			   $insertSQL = sprintf("INSERT INTO pictures ( PICTURE_ID, PROPERTY_ID, IS_MAIN, TITLE, COMMENTS, THUMB_PIC_PATH, SLIDE_PIC_PATH, FULL_PIC_PATH,    ORIGINAL_PIC_PATH) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   GetSQLValueString($picture_ID, "double"),																												               GetSQLValueString($_POST['PROPERTY_ID'], "int"),
			   GetSQLValueString($mainimg, "text"),
			   GetSQLValueString($_POST['title'][$counter], "text"),
			   GetSQLValueString($_POST['comments'][$counter], "text"),
			   GetSQLValueString($thumbpath, "text"),
			   GetSQLValueString($slidepath, "text"),
			   GetSQLValueString($fullpath, "text"),
			   GetSQLValueString($uploadfile, "text"));
	

			  $Result1 = mysql_query($insertSQL) or die(mysql_error());
			  mysql_query("COMMIT");
		}
   		$counter = $counter + 1;
	} //End of for loop
}//end of if
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_adminaddprojectimagesFP")) 
{
	$form_adminaddprojectimages_Action = $_SERVER['PHP_SELF']."?PROPERTY_ID=".$_POST['PROPERTY_ID'];
	//Upload Images
	$uploaddir = '../property_images/';
	$propdir = $uploaddir.$_POST['PROPERTY_ID'];
	if(!file_exists($propdir))
		mkdir($propdir);
	
    $counter = 0;
	foreach ($_FILES["userfile"]["error"] as $key => $error) 
	{
			/* File Checking */
			if ($_FILES['userfile']['size'][$key] > 0)
			{
				if ($_FILES['userfile']['size'][$key] > 2*1024*1024)
					$errorMsg[$_FILES['userfile']['name'][$key]] = 'FileMaxLength';
				
				if ($_FILES['userfile']['type'][$key] == "image/gif"){$fileext = '.gif';}
				else if ($_FILES['userfile']['type'][$key] == "image/jpg"){$fileext = '.jpg';}
				else if ($_FILES['userfile']['type'][$key] == "image/jpeg"){$fileext = '.jpeg';}
				else if ($_FILES['userfile']['type'][$key] == "image/bmp"){$fileext = '.bmp';}
				else if ($_FILES['userfile']['type'][$key] == "image/png"){$fileext = '.png';}
				else
					 $errorMsg[$_FILES['userfile']['name'][$key]] = 'FileFormat';
			}
	
	
			if ($error == UPLOAD_ERR_OK && !$$errorMsg[$_FILES['userfile']['name'][$key]]) 
			{
				$mainimg = 'N';
					if(isset($_POST['defaultimageindex']) && $_POST['defaultimageindex'] != '' && ($_POST['defaultimageindex'] == $counter))
					{
						$mainimg = 'Y';
					}
			   $uid = uniqid();

			   $picture_ID = getSeqNextVal( $sehyogDB, $database_sehyogDB, "sequence_picture_id" );
			   $uploadfilename = $picture_ID.'_ORIG_'.$uid .$fileext;
			   $uploadfile = $propdir.'/'.$uploadfilename;
			   move_uploaded_file($_FILES["userfile"]["tmp_name"][$key],$uploadfile) or die("Problems with upload");
			   
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $thumbpath = $propdir.'/'.$picture_ID.'_THUMB_'.$uid .$fileext;
			   $image->resizeToThumb();
			   $image->save($thumbpath);
		
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $slidepath = $propdir.'/'.$picture_ID.'_SLIDE_'.$uid .$fileext;
			   $image->resizeToSlide();
			   $image->save($slidepath);
			   
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $fullpath = $propdir.'/'.$picture_ID.'_FULL_'.$uid .$fileext;
			   $image->resizeToFull();
			   $image->save($fullpath);
			   mysql_query("START TRANSACTION");
					if($mainimg == 'Y')
					{ //If Main image is set let us set previous main image to N
					  $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
								   GetSQLValueString("N", "text"),
								   GetSQLValueString($_POST['PROPERTY_ID'], "int"));

     				   $Result1 = mysql_query($updateSQL) or die(mysql_error());
					}
	
			   $insertSQL = sprintf("INSERT INTO pictures ( PICTURE_ID, PROPERTY_ID, IS_MAIN, TITLE, COMMENTS, THUMB_PIC_PATH, SLIDE_PIC_PATH, FULL_PIC_PATH,    ORIGINAL_PIC_PATH) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   GetSQLValueString($picture_ID, "double"),																												               GetSQLValueString($_POST['PROPERTY_ID'], "int"),
			   GetSQLValueString($mainimg, "text"),
			   GetSQLValueString('FLOORPLAN', "text"),
			   GetSQLValueString($_POST['comments'][$counter], "text"),
			   GetSQLValueString($thumbpath, "text"),
			   GetSQLValueString($slidepath, "text"),
			   GetSQLValueString($fullpath, "text"),
			   GetSQLValueString($uploadfile, "text"));
	

			  $Result1 = mysql_query($insertSQL) or die(mysql_error());
			  mysql_query("COMMIT");
		}
   		$counter = $counter + 1;
	} //End of for loop
}//end of if for  floorplan

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_adminaddprojectimagesEPC")) 
{
	$form_adminaddprojectimages_Action = $_SERVER['PHP_SELF']."?PROPERTY_ID=".$_POST['PROPERTY_ID'];
	//Upload Images
	$uploaddir = '../property_images/';
	$propdir = $uploaddir.$_POST['PROPERTY_ID'];
	if(!file_exists($propdir))
		mkdir($propdir);
	
    $counter = 0;
	foreach ($_FILES["userfile"]["error"] as $key => $error) 
	{
			/* File Checking */
			if ($_FILES['userfile']['size'][$key] > 0)
			{
				if ($_FILES['userfile']['size'][$key] > 2*1024*1024)
					$errorMsg[$_FILES['userfile']['name'][$key]] = 'FileMaxLength';
				
				if ($_FILES['userfile']['type'][$key] == "image/gif"){$fileext = '.gif';}
				else if ($_FILES['userfile']['type'][$key] == "image/jpg"){$fileext = '.jpg';}
				else if ($_FILES['userfile']['type'][$key] == "image/jpeg"){$fileext = '.jpeg';}
				else if ($_FILES['userfile']['type'][$key] == "image/bmp"){$fileext = '.bmp';}
				else if ($_FILES['userfile']['type'][$key] == "image/png"){$fileext = '.png';}
				else
					 $errorMsg[$_FILES['userfile']['name'][$key]] = 'FileFormat';
			}
	
	
			if ($error == UPLOAD_ERR_OK && !$$errorMsg[$_FILES['userfile']['name'][$key]]) 
			{
				$mainimg = 'N';
					if(isset($_POST['defaultimageindex']) && $_POST['defaultimageindex'] != '' && ($_POST['defaultimageindex'] == $counter))
					{
						$mainimg = 'Y';
					}
			   $uid = uniqid();

			   $picture_ID = getSeqNextVal( $sehyogDB, $database_sehyogDB, "sequence_picture_id" );
			   $uploadfilename = $picture_ID.'_ORIG_'.$uid .$fileext;
			   $uploadfile = $propdir.'/'.$uploadfilename;
			   move_uploaded_file($_FILES["userfile"]["tmp_name"][$key],$uploadfile) or die("Problems with upload");
			   
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $thumbpath = $propdir.'/'.$picture_ID.'_THUMB_'.$uid .$fileext;
			   $image->resizeToThumb();
			   $image->save($thumbpath);
		
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $slidepath = $propdir.'/'.$picture_ID.'_SLIDE_'.$uid .$fileext;
			   $image->resizeToSlide();
			   $image->save($slidepath);
			   
			   $image = new SimpleImage();
			   $image->load($uploadfile);
			   $fullpath = $propdir.'/'.$picture_ID.'_FULL_'.$uid .$fileext;
			   $image->resizeToFull();
			   $image->save($fullpath);
			   mysql_query("START TRANSACTION");
					if($mainimg == 'Y')
					{ //If Main image is set let us set previous main image to N
					  $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
								   GetSQLValueString("N", "text"),
								   GetSQLValueString($_POST['PROPERTY_ID'], "int"));

     				   $Result1 = mysql_query($updateSQL) or die(mysql_error());
					}
	
			   $insertSQL = sprintf("INSERT INTO pictures ( PICTURE_ID, PROPERTY_ID, IS_MAIN, TITLE, COMMENTS, THUMB_PIC_PATH, SLIDE_PIC_PATH, FULL_PIC_PATH,    ORIGINAL_PIC_PATH) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
			   GetSQLValueString($picture_ID, "double"),																												               GetSQLValueString($_POST['PROPERTY_ID'], "int"),
			   GetSQLValueString($mainimg, "text"),
			   GetSQLValueString('EPC', "text"),
			   GetSQLValueString($_POST['comments'][$counter], "text"),
			   GetSQLValueString($thumbpath, "text"),
			   GetSQLValueString($slidepath, "text"),
			   GetSQLValueString($fullpath, "text"),
			   GetSQLValueString($uploadfile, "text"));
	

			  $Result1 = mysql_query($insertSQL) or die(mysql_error());
			  mysql_query("COMMIT");
		}
   		$counter = $counter + 1;
	} //End of for loop
}//end of if for  EPC

if ((isset($_GET['PICTURE_ID'])) && ($_GET['PICTURE_ID'] != "") && (isset($_GET['MAKE_MAIN']))) {
		$form_adminaddprojectimages_Action = $_SERVER['PHP_SELF']."?PROPERTY_ID=".$_GET['PROPERTY_ID'];
		  $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString("N", "text"),
                       GetSQLValueString($_GET['PROPERTY_ID'], "int"));

		   
			$Result1 = mysql_query($updateSQL) or die(mysql_error());

		  $updateSQL = sprintf("UPDATE pictures SET IS_MAIN=%s WHERE PICTURE_ID=%s",
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($_GET['PICTURE_ID'], "int"));

		   
			$Result1 = mysql_query($updateSQL) or die(mysql_error());
}


if ((isset($_GET['PICTURE_ID'])) && ($_GET['PICTURE_ID'] != "") && (isset($_GET['DELETE_PICTURE']))) {
		$form_adminaddprojectimages_Action = $_SERVER['PHP_SELF']."?PROPERTY_ID=".$_GET['PROPERTY_ID'];
	

	$query_Recordset1 = sprintf("SELECT * FROM pictures WHERE PICTURE_ID = %s", GetSQLValueString($_GET['PICTURE_ID'], "double"));
	$Recordset1 = mysql_query($query_Recordset1) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
	
	
	
	unlink($row_Recordset1['THUMB_PIC_PATH']);
	unlink($row_Recordset1['FULL_PIC_PATH']);
	unlink($row_Recordset1['SLIDE_PIC_PATH']);
	unlink($row_Recordset1['ORIGINAL_PIC_PATH']);
	mysql_free_result($Recordset1);
	
  $deleteSQL = sprintf("DELETE FROM pictures WHERE PICTURE_ID=%s",
                       GetSQLValueString($_GET['PICTURE_ID'], "int"));


  $Result1 = mysql_query($deleteSQL) or die(mysql_error());
}

$colname_image_preview_RS = "-1";
if (isset($_GET['PROPERTY_ID'])) {
  $colname_image_preview_RS = $_GET['PROPERTY_ID'];
}

$query_image_preview_RS = sprintf("SELECT PICTURE_ID, IS_MAIN, THUMB_PIC_PATH, FULL_PIC_PATH, ORIGINAL_PIC_PATH FROM pictures WHERE PROPERTY_ID = %s and (TITLE IS NULL or TITLE IN('STC')) ORDER BY PICTURE_ID ASC", GetSQLValueString($colname_image_preview_RS, "int"));
$image_preview_RS = mysql_query($query_image_preview_RS) or die(mysql_error());
$row_image_preview_RS = mysql_fetch_assoc($image_preview_RS);
$totalRows_image_preview_RS = mysql_num_rows($image_preview_RS);

$query_fp_preview_RS = sprintf("SELECT PICTURE_ID, COMMENTS, THUMB_PIC_PATH, FULL_PIC_PATH, ORIGINAL_PIC_PATH FROM pictures WHERE PROPERTY_ID = %s and TITLE IN('FLOORPLAN') ORDER BY PICTURE_ID ASC", GetSQLValueString($colname_image_preview_RS, "int"));
$fp_preview_RS = mysql_query($query_fp_preview_RS) or die(mysql_error());
$row_fp_preview_RS = mysql_fetch_assoc($fp_preview_RS);
$totalRows_fp_preview_RS = mysql_num_rows($fp_preview_RS);

$query_epc_preview_RS = sprintf("SELECT PICTURE_ID, COMMENTS, THUMB_PIC_PATH, FULL_PIC_PATH, ORIGINAL_PIC_PATH FROM pictures WHERE PROPERTY_ID = %s and TITLE IN('EPC') ORDER BY PICTURE_ID ASC", GetSQLValueString($colname_image_preview_RS, "int"));
$epc_preview_RS = mysql_query($query_epc_preview_RS) or die(mysql_error());
$row_epc_preview_RS = mysql_fetch_assoc($epc_preview_RS);
$totalRows_epc_preview_RS = mysql_num_rows($epc_preview_RS);


$query_propDetailRS = "SELECT * FROM property_details_view WHERE PROPERTY_ID = $colname_image_preview_RS";
$propDetailRS = mysql_query($query_propDetailRS) or die(mysql_error());
$row_propDetailRS = mysql_fetch_assoc($propDetailRS);
$totalRows_propDetailRS = mysql_num_rows($propDetailRS);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link  rel="stylesheet"  href="adminstyle.css" type="text/css" />
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>

<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
	  var counter = 0;
      function add_new_uploader(times, data ,containerID ,hiddenID) {
		  if(counter >= 5 ){
			  alert("You cannot upload more then 5 images at a time.");
			  return;
		  }

        var container = document.getElementById(containerID);

		  // Make a new div and append to form...
          var div =  document.createElement('div');
          div.className = 'uploader_div';
		  div.style.marginTop = '5px';
          div.style.paddingTop = '1em';
		  div.style.paddingLeft = '5px';
          div.style.border = '1px solid #000';
		  div.style.clear = 'both';
		  container.appendChild(div);
		  
          // Make the Image Label
          var imagelabel = document.createElement('div');
          imagelabel.style.paddingTop = '5px';
		  imagelabel.style.paddingLeft = '5px';
          imagelabel.innerHTML = "Image :";

		  // Make the file upload thingy
          var fileupload = document.createElement('input');;
          fileupload.setAttribute('type', 'file');
		  fileupload.setAttribute('name', 'userfile[]');
		  fileupload.style.paddingLeft = '5px';

          // Make the Main Image Label
          var mainlabel = document.createElement('div');
          mainlabel.style.paddingTop = '5px';
		  mainlabel.style.paddingLeft = '5px';
          mainlabel.innerHTML = "Is Main Image:";

		  // Make the Title text box
          var radio = document.createElement('input');
		  radio.setAttribute("name","radiobtnsitejs");
		  radio.setAttribute("type","radio");
          radio.onclick = function(hiddenID) { 
			  for( i = 0 ;i < document.getElementsByName('radiobtnsitejs').length; i++){
				  if(document.getElementsByName('radiobtnsitejs')[i].checked  == true){
				  	document.getElementById('defaultimageindex_ID').value = i;
					
				  }
			  }
          };



		  

          // Make the "remove" link
          var rm = document.createElement('a');
          rm.href = '#';
          rm.onclick = function() { 
          	if (confirm('Are you sure you want to remove this picture?')) {
          		this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
				counter = parseInt(counter)-1; 
          	}
          	return false;
          };
			var img = document.createElement('IMG');
			img.src = 'images/remove.gif';
			rm.appendChild(img);
          var rmDiv = document.createElement('div');
		  rmDiv.style.textAlign = 'right';
		  rmDiv.appendChild(rm);
          
          div.appendChild(imagelabel);
		  div.appendChild(fileupload);
		  div.appendChild(mainlabel);
		  div.appendChild(radio);
		  div.appendChild(rmDiv);     
		  
		  counter = parseInt(counter)+1; 
      }
	  
	  var counterFP = 0;
      function add_new_uploaderFP(times, data ,containerID ,hiddenID) {
		  if(counterFP >= 5 ){
			  alert("You cannot upload more then 5 images at a time.");
			  return;
		  }

        var container = document.getElementById(containerID);

		  // Make a new div and append to form...
          var div =  document.createElement('div');
          div.className = 'uploader_div';
		  div.style.marginTop = '5px';
          div.style.paddingTop = '1em';
		  div.style.paddingLeft = '5px';
          div.style.border = '1px solid #000';
		  div.style.clear = 'both';
		  container.appendChild(div);
		  
          // Make the Image Label
          var imagelabel = document.createElement('div');
          imagelabel.style.paddingTop = '5px';
		  imagelabel.style.paddingLeft = '5px';
          imagelabel.innerHTML = "Image :";

		  // Make the file upload thingy
          var fileupload = document.createElement('input');;
          fileupload.setAttribute('type', 'file');
		  fileupload.setAttribute('name', 'userfile[]');
		  fileupload.style.paddingLeft = '5px';

          // Make the Main Image Label
          var mainlabel = document.createElement('div');
          mainlabel.style.paddingTop = '5px';
		  mainlabel.style.paddingLeft = '5px';
          mainlabel.innerHTML = "Floor Number:";


		  // Make the Title text box
          var radio = document.createElement('div');
		  radio.innerHTML  = "<select name = 'comments[]'><option value='-3'>-3</option><option value='-2'>-2</option><option value='-1'>-1</option><option value='0'>0</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option></select>";
		  //radio.setAttribute("name","comment[]");
		  //radio.setAttribute("type","text");
//		  radio.innerHTML = "<option value='0'>0</option>"

          // Make the "remove" link
          var rm = document.createElement('a');
          rm.href = '#';
          rm.onclick = function() { 
          	if (confirm('Are you sure you want to remove this picture?')) {
          		this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
				counterFP = parseInt(counterFP)-1; 
          	}
          	return false;
          };
			var img = document.createElement('IMG');
			img.src = 'images/remove.gif';
			rm.appendChild(img);
          var rmDiv = document.createElement('div');
		  rmDiv.style.textAlign = 'right';
		  rmDiv.appendChild(rm);
          
          div.appendChild(imagelabel);
		  div.appendChild(fileupload);
		  div.appendChild(mainlabel);
		  div.appendChild(radio);
		  div.appendChild(rmDiv);     
		  
		  counterFP = parseInt(counterFP)+1; 
      }

	  var counterEPC = 0;
      function add_new_uploaderEPC(times, data ,containerID ,hiddenID) {
		  if(counterEPC >= 5 ){
			  alert("You cannot upload more then 5 images at a time.");
			  return;
		  }

        var container = document.getElementById(containerID);

		  // Make a new div and append to form...
          var div =  document.createElement('div');
          div.className = 'uploader_div';
		  div.style.marginTop = '5px';
          div.style.paddingTop = '1em';
		  div.style.paddingLeft = '5px';
          div.style.border = '1px solid #000';
		  div.style.clear = 'both';
		  container.appendChild(div);
		  
          // Make the Image Label
          var imagelabel = document.createElement('div');
          imagelabel.style.paddingTop = '5px';
		  imagelabel.style.paddingLeft = '5px';
          imagelabel.innerHTML = "Image :";

		  // Make the file upload thingy
          var fileupload = document.createElement('input');;
          fileupload.setAttribute('type', 'file');
		  fileupload.setAttribute('name', 'userfile[]');
		  fileupload.style.paddingLeft = '5px';

          // Make the Main Image Label
          var mainlabel = document.createElement('div');
          mainlabel.style.paddingTop = '5px';
		  mainlabel.style.paddingLeft = '5px';
          mainlabel.innerHTML = "Image Description:";

		  // Make the Title text box
          var radio = document.createElement('input');
		  radio.setAttribute("name","comments[]");
		  radio.setAttribute("type","text");
          // Make the "remove" link
          var rm = document.createElement('a');
          rm.href = '#';
          rm.onclick = function() { 
          	if (confirm('Are you sure you want to remove this picture?')) {
          		this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode);
				counterEPC = parseInt(counterEPC)-1; 
          	}
          	return false;
          };
			var img = document.createElement('IMG');
			img.src = 'images/remove.gif';
			rm.appendChild(img);
          var rmDiv = document.createElement('div');
		  rmDiv.style.textAlign = 'right';
		  rmDiv.appendChild(rm);
          
          div.appendChild(imagelabel);
		  div.appendChild(fileupload);
		  div.appendChild(mainlabel);
		  div.appendChild(radio);
		  div.appendChild(rmDiv);     
		  
		  counterEPC = parseInt(counterEPC)+1; 
      }

//-->
</script>
<script>
function gotoUrl(url){
		window.location.href = url;
}

</script>
</head>

<body >
<?php include('top.php')?>

 <div class="orangehead">Adding New Property </div>
  <div class="steps"> <span class="stepdone">1. Add  Details</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="stepdone">2. Set Map Location</span> <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="stepactive">3. Add Images</span>  <img src="images/arrow.png" alt="Next Step" width="34" height="26" align="absmiddle" /> <span class="steppending">4. Preview & Publish</span></div>


<div class="offer_info" style="text-align:left"><span><?php echo $row_propDetailRS['FOR_SHORT_DESCRIPTION'];  ?></span>    <?php if ($row_propDetailRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propDetailRS['BEDROOMS']." Bed" ; ?>  <?php echo $row_propDetailRS['TYPE_SHORT_DESCRIPTION']; ?> <?php echo $row_propDetailRS['CITY']; ?><span> &pound;<?php echo $row_propDetailRS['PRICE']; ?> </span>
    
    <div style="text-align:right;padding-top:10px;float:right">
<input type="button" name="cancel" class="button" value="Done" onclick="gotoUrl('../pages/property-details.php?PROPERTY_ID=<?php if(isset($_GET['PROPERTY_ID'])){ echo $_GET['PROPERTY_ID'];}else if (isset($_POST['PROPERTY_ID'])){ echo $_POST['PROPERTY_ID'];} else { echo("-1") ;} ?>')"/>
     </div>    
        
    </div>

<div style="padding-top:10px;margin:10px 0px;border-top:2px solid #5D5d5d;clear:both">
<div class="phead" style="margin-bottom:10px">
Upload New Property Images 
</div>
  

    <div style="width:298px;float:left;border:1px dashed #333">
          
<?php if($_GET['error'] == 'error'){  ?>
<div style="text-align:center;color:#F00;font-size:12px;"><img src="images/error.png" width="32" height="32" align="absmiddle" /> The username or password you entered is incorrect.</div>
<?php } ?>
              <form action="<?php echo $form_adminaddprojectimages_Action; ?>" method="post" enctype="multipart/form-data" name="form_adminaddprojectimages">
   <!-- <input name="PROPERTY_ID" type="text" value="<?php echo $_POST['PROPERTY_ID']; ?>" />-->
 <input name="PROPERTY_ID" type="hidden" value="<?php if(isset($_GET['PROPERTY_ID'])){ echo $_GET['PROPERTY_ID'];}else if (isset($_POST['PROPERTY_ID'])){ echo $_POST['PROPERTY_ID'];} else { echo("1") ;} ?>" />
  <table align="center" class="form_table" cellpadding="5px" width="100%">
                <tr valign="baseline">
            <td width="100%"><div id="imagesUploadDiv" style="text-align:left;padding-left:30px">
</div>
          </td>
          </tr>
          <tr valign="baseline">
            <td align="right"><a name="top"></a>


            <a href="#" onclick="javascript:add_new_uploader('','','imagesUploadDiv','defaultimageindex_ID');return false;">Add Image</a><br/></td>
          </tr>

          <tr valign="baseline">
            <td align="right">                                    <input type="submit" name="submit" class="button" value="Submit" style="float:right"/></td>
          </tr>
        </table>
  <input type="hidden" name="MM_insert" value="form_adminaddprojectimages" />
<input name="defaultimageindex" id="defaultimageindex_ID" type="hidden" value="" />
    </form>
    </div>
        <div style="width:410px;float:right;text-align:center;">
        <span class="phead">Uploaded Property Images Preview</span>
        <table cellpadding="10">
          <?php if ($totalRows_image_preview_RS == 0) { // Show if recordset empty ?>
            <tr>
              <td align="center" >
                No Image Uploaded 
              </td>
            </tr>
            <?php } else  { // Show if recordset empty ?>

<?php 
	         $counter = 1;
		      do { ?>
          
            <?php if($counter == 1){ ?>
             <tr>
            <td align="center">
            <?php } else { ?>
            <td align="center">
            <?php } ?>
              <div > <img src="<?php echo $row_image_preview_RS['THUMB_PIC_PATH']; ?>" /><br/>

                <a href="<?php echo $row_image_preview_RS['ORIGINAL_PIC_PATH']; ?>" target="_blank"><img src="images/search.png" width="24" height="24" align="absmiddle" /></a>
                <?php if($row_image_preview_RS['IS_MAIN'] == 'N'){?>
                <a href="adminaddpropertyimages.php?DELETE_PICTURE=DELETE_PICTURE&amp;PICTURE_ID=<?php echo $row_image_preview_RS['PICTURE_ID']; ?>&amp;PROPERTY_ID=<?php echo $_GET['PROPERTY_ID']; ?>"><img src="images/delete.png" width="24" height="24" align="absmiddle" /></a> 
                  <label>
                    <input type="radio" name="RadioGroup1" value="<?php echo $row_image_preview_RS['PICTURE_ID']; ?>" id="RadioGroup1_0" onclick="window.location.href='adminaddpropertyimages.php?MAKE_MAIN=MAKE_MAIN&amp;PICTURE_ID=<?php echo $row_image_preview_RS['PICTURE_ID']; ?>&amp;PROPERTY_ID=<?php echo $_GET['PROPERTY_ID']; ?>'"/>
                  Set As Main</label>
                <?php } else {?>
                <label>
                <input type="radio" value="" id="RadioGroup1_0" disabled="disabled" checked="checked"/>
                Set As Main
                </label>
                <?php }?>
              </div>
            <?php if($counter == 2){ $counter = 1; ?>
            
             </td>
            </tr >
            <?php } else { ?>
            <td >
            <?php $counter += 1; } ?>

          <?php } while ($row_image_preview_RS = mysql_fetch_assoc($image_preview_RS)); 
			}?>
          </table>
        </div>
     
	<div class="clear">&nbsp;</div>
    </div>
    
<div style="padding-top:10px;margin:10px 0px;border-top:2px solid #5D5d5d;clear:both">

  <div class="phead" style="margin-bottom:10px">
  Upload Floorplan Images
  </div>

    <div style="width:298px;float:left;border:1px dashed #333">
          
<?php if($_GET['error'] == 'error'){  ?>
<div style="text-align:center;color:#F00;font-size:12px;"><img src="images/error.png" width="32" height="32" align="absmiddle" /> The username or password you entered is incorrect.</div>
<?php } ?>
              <form action="<?php echo $form_adminaddprojectimages_Action; ?>" method="post" enctype="multipart/form-data" name="form_adminaddprojectimagesFP">
   <!-- <input name="PROPERTY_ID" type="text" value="<?php echo $_POST['PROPERTY_ID']; ?>" />-->
 <input name="PROPERTY_ID" type="hidden" value="<?php if(isset($_GET['PROPERTY_ID'])){ echo $_GET['PROPERTY_ID'];}else if (isset($_POST['PROPERTY_ID'])){ echo $_POST['PROPERTY_ID'];} else { echo("1") ;} ?>" />
  <table align="center" class="form_table" cellpadding="5px" width="100%">
                <tr valign="baseline">
            <td width="100%"><div id="imagesUploadDivFP" style="text-align:left;padding-left:30px">
</div>
          </td>
          </tr>
          <tr valign="baseline">
            <td align="right"><a name="top"></a>


            <a href="#" onclick="javascript:add_new_uploaderFP('','','imagesUploadDivFP','defaultimageindexFP_ID');return false;">Add Image</a><br/></td>
          </tr>

          <tr valign="baseline">
            <td align="right">                                    <input type="submit" name="submit" class="button" value="Submit" style="float:right"/></td>
          </tr>
        </table>
  <input type="hidden" name="MM_insert" value="form_adminaddprojectimagesFP" />
<input name="defaultimageindexFP" id="defaultimageindexFP_ID" type="hidden" value="" />
    </form>
    </div>
        <div style="width:410px;float:right;text-align:center;">
        <span class="phead">Uploaded Floorplan Images Preview</span>
        <table cellpadding="10">
          <?php if ($totalRows_fp_preview_RS == 0) { // Show if recordset empty ?>
            <tr>
              <td align="center" >
                No Floorplan Uploaded 
              </td>
            </tr>
            <?php } else  { // Show if recordset empty ?>

<?php 
	         $counter = 1;
		      do { ?>
          
            <?php if($counter == 1){ ?>
             <tr>
            <td align="center">
            <?php } else { ?>
            <td align="center">
            <?php } ?>
              <div > <img src="<?php echo $row_fp_preview_RS['THUMB_PIC_PATH']; ?>" /><br/>

                <a href="<?php echo $row_fp_preview_RS['ORIGINAL_PIC_PATH']; ?>" target="_blank"><img src="images/search.png" width="24" height="24" align="absmiddle" /></a>
                <a href="adminaddpropertyimages.php?DELETE_PICTURE=DELETE_PICTURE&amp;PICTURE_ID=<?php echo $row_fp_preview_RS['PICTURE_ID']; ?>&amp;PROPERTY_ID=<?php echo $_GET['PROPERTY_ID']; ?>"><img src="images/delete.png" width="24" height="24" align="absmiddle" /></a> 
                <span class="label">Floor :-</span> <?php echo $row_fp_preview_RS['COMMENTS']; ?>
              </div>
            <?php if($counter == 2){ $counter = 1; ?>
            
             </td>
            </tr >
            <?php } else { ?>
            <td >
            <?php $counter += 1; } ?>

          <?php } while ($row_fp_preview_RS = mysql_fetch_assoc($fp_preview_RS)); 
			}?>
          </table>
        </div>
     
	<div class="clear">&nbsp;</div>
    </div>
    

<div style="clear:both;height:10px">&nbsp;
</div>
<div style="padding-top:10px;margin:10px 0px;border-top:2px solid #5D5d5d;clear:both;">
<div class="phead" style="margin-bottom:10px">
Upload EPC or Other Images
</div>
  

    <div style="width:298px;float:left;border:1px dashed #333">
          
<?php if($_GET['error'] == 'error'){  ?>
<div style="text-align:center;color:#F00;font-size:12px;"><img src="images/error.png" width="32" height="32" align="absmiddle" /> The username or password you entered is incorrect.</div>
<?php } ?>
              <form action="<?php echo $form_adminaddprojectimages_Action; ?>" method="post" enctype="multipart/form-data" name="form_adminaddprojectimagesEPC">
   <!-- <input name="PROPERTY_ID" type="text" value="<?php echo $_POST['PROPERTY_ID']; ?>" />-->
 <input name="PROPERTY_ID" type="hidden" value="<?php if(isset($_GET['PROPERTY_ID'])){ echo $_GET['PROPERTY_ID'];}else if (isset($_POST['PROPERTY_ID'])){ echo $_POST['PROPERTY_ID'];} else { echo("1") ;} ?>" />
  <table align="center" class="form_table" cellpadding="5px" width="100%">
                <tr valign="baseline">
            <td width="100%"><div id="imagesUploadDivEPC" style="text-align:left;padding-left:30px">
</div>
          </td>
          </tr>
          <tr valign="baseline">
            <td align="right"><a name="top"></a>


            <a href="#" onclick="javascript:add_new_uploaderEPC('','','imagesUploadDivEPC','defaultimageindexEPC_ID');return false;">Add Image</a><br/></td>
          </tr>

          <tr valign="baseline">
            <td align="right">                                    <input type="submit" name="submit" class="button" value="Submit" style="float:right"/></td>
          </tr>
        </table>
  <input type="hidden" name="MM_insert" value="form_adminaddprojectimagesEPC" />
<input name="defaultimageindexEPC" id="defaultimageindexEPC_ID" type="hidden" value="" />
    </form>
    </div>
        <div style="width:410px;float:right;text-align:center;">
        <span class="phead">Uploaded EPC Images Preview</span>
        <table cellpadding="10">
          <?php if ($totalRows_epc_preview_RS == 0) { // Show if recordset empty ?>
            <tr>
              <td align="center" >
                No EPC Image Uploaded 
              </td>
            </tr>
            <?php } else  { // Show if recordset empty ?>

<?php 
	         $counter = 1;
		      do { ?>
          
            <?php if($counter == 1){ ?>
             <tr>
            <td align="center">
            <?php } else { ?>
            <td align="center">
            <?php } ?>
              <div > <img src="<?php echo $row_epc_preview_RS['THUMB_PIC_PATH']; ?>" /><br/>

                <a href="<?php echo $row_epc_preview_RS['ORIGINAL_PIC_PATH']; ?>" target="_blank"><img src="images/search.png" width="24" height="24" align="absmiddle" /></a>
                <a href="adminaddpropertyimages.php?DELETE_PICTURE=DELETE_PICTURE&amp;PICTURE_ID=<?php echo $row_epc_preview_RS['PICTURE_ID']; ?>&amp;PROPERTY_ID=<?php echo $_GET['PROPERTY_ID']; ?>"><img src="images/delete.png" width="24" height="24" align="absmiddle" /></a> 
                <br />
                <span class="label">Image Description :-</span><br />
                <?php echo $row_epc_preview_RS['COMMENTS']; ?>
              </div>
            <?php if($counter == 2){ $counter = 1; ?>
            
             </td>
            </tr >
            <?php } else { ?>
            <td >
            <?php $counter += 1; } ?>

          <?php } while ($row_epc_preview_RS = mysql_fetch_assoc($epc_preview_RS)); 
			}?>
          </table>
        </div>
     
	<div class="clear">&nbsp;</div>
    </div>    
<div style="clear:both">
<div style="text-align:right;padding-top:10px">
<input type="button" name="cancel" class="button" value="Done" onclick="gotoUrl('../pages/property-details.php?PROPERTY_ID=<?php if(isset($_GET['PROPERTY_ID'])){ echo $_GET['PROPERTY_ID'];}else if (isset($_POST['PROPERTY_ID'])){ echo $_POST['PROPERTY_ID'];} else { echo("-1") ;} ?>')"/>
     </div>    
    <div class="clear">&nbsp;</div>
</div>
  


<?php include('bottom.php')?>
</body>
</html>