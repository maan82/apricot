<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("View Viewing Requests");
$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['ID'])) {
  $colname_DetailRS1 = $_GET['ID'];
}

$query_DetailRS1 = sprintf("SELECT * FROM property_view_request_view WHERE ID = %s ORDER BY CREATION_DATE_PVR DESC", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;

if($row_DetailRS1['READ_STATUS'] == 'N'){
	$y= "Y";
	$updateSQL = sprintf("UPDATE property_view_request SET READ_STATUS='Y' WHERE ID=%s",						
						   GetSQLValueString($_GET['ID'], "int"));
//echo $updateSQL;

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
     <div class="actions_list">
  <ul><li>
    <a href="#" style="width:180px;float:right" onclick="history.go(-1);return false;"><span>Back </span></a>
  </li>
  </ul>
  </div>

  <div class="orangehead">Viewer Details</div>
<table border="1" align="center">
  <tr>
    <td>TITLE</td>
    <td><?php echo $row_DetailRS1['TITLE']; ?></td>
  </tr>
  <tr>
    <td>FIRST NAME</td>
    <td><?php echo $row_DetailRS1['FIRST_NAME']; ?></td>
  </tr>
  <tr>
    <td>SURNAME</td>
    <td><?php echo $row_DetailRS1['SURNAME']; ?></td>
  </tr>
  <tr>
    <td>VIEWER POSTCODE</td>
    <td><?php echo $row_DetailRS1['POSTCODE_PVR']; ?></td>
  </tr>
  <tr>
    <td>ADDRESS</td>
    <td><?php echo $row_DetailRS1['ADDRESS']; ?></td>
  </tr>
  <tr>
    <td>EMAIL ID</td>
    <td><?php echo $row_DetailRS1['EMAIL_ID']; ?></td>
  </tr>
  <tr>
    <td>HOME NO</td>
    <td><?php echo $row_DetailRS1['HOME_NO']; ?></td>
  </tr>
  <tr>
    <td>WORK NO</td>
    <td><?php echo $row_DetailRS1['WORK_NO']; ?></td>
  </tr>
  <tr>
    <td>MOB NO</td>
    <td><?php echo $row_DetailRS1['MOB_NO']; ?></td>
  </tr>
  <tr>
    <td>PREFERED CALL TIME</td>
    <td><?php echo $row_DetailRS1['TIME']; ?></td>
  </tr>
  <tr>
    <td>PREFERED CALL NO</td>
    <td><?php echo $row_DetailRS1['PREF_NO_DESCRIPTION']; ?></td>
  </tr>
  <tr>
    <td>MESSAGE</td>
    <td><?php echo $row_DetailRS1['MESSAGE']; ?></td>
  </tr>
  <tr>
    <td>Date Of Query</td>
    <td><?php echo $row_DetailRS1['CREATION_DATE_PVR']; ?></td>
  </tr>
  </table>
  <div class="orangehead">Requested Proprty Details</div>
  <table border="1" align="center">
  <tr>
    <td>PROPERTY ID</td>
    <td><?php echo $row_DetailRS1['PROPERTY_ID']; ?></td>
  </tr>
    <tr>
    <td>PROPERTY IMAGE</td>
    <td><img src="<?php echo $row_DetailRS1['SLIDE_PIC_PATH']; ?>"/> </td>
  </tr>
  <tr>
    <td>PROPERTY FOR</td>
    <td><?php echo $row_DetailRS1['FOR_SHORT_DESCRIPTION']; ?></td>
  </tr>
  <tr>
    <td>PROPERTY TYPE</td>
    <td><?php echo $row_DetailRS1['TYPE_SHORT_DESCRIPTION']; ?></td>
  </tr>

  <tr>
    <td>PRICE</td>
    <td><?php echo $row_DetailRS1['PRICE']; ?></td>
  </tr>
  <tr>
    <td>BEDROOMS</td>
    <td><?php echo $row_DetailRS1['BEDROOMS']; ?></td>
  </tr>
  <tr>
    <td>BATHROOMS</td>
    <td><?php echo $row_DetailRS1['BATHROOMS']; ?></td>
  </tr>
  <tr>
    <td>KITCHENS</td>
    <td><?php echo $row_DetailRS1['KITCHENS']; ?></td>
  </tr>
  <tr>
    <td>DRAWING ROOMS</td>
    <td><?php echo $row_DetailRS1['DRAWING_ROOMS']; ?></td>
  </tr>
  <tr>
    <td>DINING ROOMS</td>
    <td><?php echo $row_DetailRS1['DINING_ROOMS']; ?></td>
  </tr>
  <tr>
    <td>PARKING</td>
    <td><?php echo $row_DetailRS1['PARKING']; ?></td>
  </tr>
  <tr>
    <td>LAWN</td>
    <td><?php echo $row_DetailRS1['LAWN']; ?></td>
  </tr>
  <tr>
    <td>AREA</td>
    <td><?php echo $row_DetailRS1['AREA']; ?></td>
  </tr>
  <tr>
    <td>CITY</td>
    <td><?php echo $row_DetailRS1['CITY']; ?></td>
  </tr>
  <tr>
    <td>STATE</td>
    <td><?php echo $row_DetailRS1['STATE']; ?></td>
  </tr>
  <tr>
    <td>COUNTRY</td>
    <td><?php echo $row_DetailRS1['COUNTRY']; ?></td>
  </tr>
  <tr>
    <td>POSTCODE</td>
    <td><?php echo $row_DetailRS1['POSTCODE']; ?></td>
  </tr>
  <tr>
    <td>FLOORS</td>
    <td><?php echo $row_DetailRS1['FLOORS']; ?></td>
  </tr>
  <tr>
    <td>BRIEF DESCRIPTION</td>
    <td><?php echo $row_DetailRS1['BRIEF_DESCRIPTION']; ?></td>
  </tr>
  <tr>
    <td>DETAIL DESCRIPTION</td>
    <td><?php echo $row_DetailRS1['DETAIL_DESCRIPTION']; ?></td>
  </tr>
  <tr>
    <td>CREATION DATE</td>
    <td><?php echo $row_DetailRS1['CREATION_DATE']; ?></td>
  </tr>
  <tr>
    <td>UPDATION DATE</td>
    <td><?php echo $row_DetailRS1['UPDATION_DATE']; ?></td>
  </tr>
  <tr>
    <td>PICTURES COUNT</td>
    <td><?php echo $row_DetailRS1['PICS_COUNT']; ?></td>
  </tr>
</table>

  <div class="actions_list">
  <ul><li>
    <a href="#" style="width:180px;float:right" onclick="history.go(-1);return false;"><span>Back </span></a>
  </li>
  </ul>
  </div>

   
    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($DetailRS1);
?>
