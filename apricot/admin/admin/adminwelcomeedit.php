<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Welcome Message Edit");

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//echo $_POST["MM_update"] ;
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_welcome_edit")) {
  $updateSQL = sprintf("UPDATE text SET HEADING=%s, MESSAGE=%s WHERE `KEY`=%s",
                       GetSQLValueString($_POST['form_welcome_edit_heading'], "text"),
                       GetSQLValueString($_POST['form_welcome_edit_msgTextArea'], "text"),
                       GetSQLValueString($_POST['form_welocme_edit'], "text"));
//echo $updateSQL ;

  $Result1 = mysql_query($updateSQL) or die(mysql_error());
  header("Location: " . "adminpropertysearch.php?MESSAGE=WELCOMEEDIT" );
}


$query_msgRS = "SELECT * FROM text WHERE `KEY` = 'welcome'";
$msgRS = mysql_query($query_msgRS) or die(mysql_error());
$row_msgRS = mysql_fetch_assoc($msgRS);
$totalRows_msgRS = mysql_num_rows($msgRS);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script src="js/nicEdit.js" type="text/javascript"></script>


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


</head>

<body >
<?php include('top.php')?>
<form name="form_welcome_edit"   action="<?php echo $editFormAction; ?>" enctype="application/x-www-form-urlencoded" method="POST">
<input name="form_welocme_edit" type="hidden" value="welcome">
<br />
<span class="label">Heading of the Welcome Message. </span><br />
<input name="form_welcome_edit_heading" style="width:400px" type="text" value="<?php echo $row_msgRS['HEADING']; ?>" maxlength="100" />
<br />
<br />
<span class="label">Enter text to be displayed in the Welcome Message. </span>
<div>
<textarea rows="5" style="width:673px" cols="50" id="form_welcome_edit_msgTextArea" name="form_welcome_edit_msgTextArea"><?php echo $row_msgRS['MESSAGE']; ?></textarea>

</div>
       <br /> 
       <input type="submit" class="button" value="Submit"     />
<a href="adminpropertysearch.php" style="text-decoration:none;"><input type="button" class="button" value="Cancel" /></a>
<input type="hidden" name="MM_update" value="form_welcome_edit">
</form>

<script type="text/javascript">
$(function() {
	// Add markItUp! to your textarea in one line
	// $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
	$('#form_welcome_edit_msgTextArea').markItUp(mySettings);


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


<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($msgRS);
?>
