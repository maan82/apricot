<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("View Contact Us Queries");
$colname_queryRS = "-1";
if (isset($_GET['ID'])) {
  $colname_queryRS = $_GET['ID'];
}

$query_queryRS = sprintf("SELECT * FROM query WHERE ID = %s", GetSQLValueString($colname_queryRS, "int"));
$queryRS = mysql_query($query_queryRS) or die(mysql_error());
$row_queryRS = mysql_fetch_assoc($queryRS);
$totalRows_queryRS = mysql_num_rows($queryRS);

if($row_queryRS['READ_STATUS'] == 'N'){
	$y= "Y";
	$updateSQL = sprintf("UPDATE query SET READ_STATUS='Y' WHERE ID=%s",						
						   GetSQLValueString($_GET['ID'], "int"));
	$Result1 = mysql_query($updateSQL) or die(mysql_error());
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">


</head>

<body >
<?php include('top.php')?>
    <div >
<table class="formcenter">
              <tr>
                <td class="label">Name :*<br />

                  <input name="form_contactform_name" id="form_contactform_name" type="text" maxlength="50" style="" value="<?php echo $row_queryRS['NAME']; ?>"  readonly="readonly"/>            


</td>
              </tr>
              <tr>
                <td class="label">
                  Phone No. :*<br  />

                  <input name="form_contactform_phone" id="form_contactform_phone" type="text" maxlength="50"  style="" value="<?php echo $row_queryRS['PHONE']; ?>" readonly="readonly"/>            
</td>
              </tr>
              <tr>
                <td class="label">
                  EMail :*<br />

                  <input name="form_contactform_email" id="form_contactform_email" type="text" maxlength="100"  style="width:500px" value="<?php echo $row_queryRS['EMAIL_ID']; ?>" readonly="readonly"/>            
</td>
              </tr>
              <tr>
                <td class="label">
                  Query :*<br />

                  <textarea id="form_contactform_query" name="form_contactform_query" style="width:500px"  rows="5" readonly="readonly" ><?php echo $row_queryRS['QUERY_TEXT']; ?></textarea>

</td>
              </tr>
              
              </table>
              <br />
              <div style="float:right"> <a href="adminqueries.php">  Back To Queries</a></div>
              <div style="clear:both">&nbsp;</div>
<br />

   
    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($queryRS);
?>
