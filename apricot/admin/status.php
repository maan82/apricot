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

if(1 == $_POST['PROPERTY_FOR_ID']){
	$colname_propstatusRS = "1,2,3,4,6,7,8,9";
} else {
	$colname_propstatusRS = "1,2,5,9";
}


$query_propstatusRS = sprintf("SELECT * FROM property_status_master WHERE STATUS_ID IN(%s) ORDER BY DETAIL_DESCRIPTION ASC", GetSQLValueString($colname_propstatusRS, ""));
//echo $query_propstatusRS;
$propstatusRS = mysql_query($query_propstatusRS) or die(mysql_error());
$row_propstatusRS = mysql_fetch_assoc($propstatusRS);
$totalRows_propstatusRS = mysql_num_rows($propstatusRS);
?>
<select name="form_searchresults_propstatus" id="form_searchresults_propstatus_Id" style="width:320px" onchange="updateForm(this)">
<?php do { ?>

    <option value="<?php echo $row_propstatusRS['STATUS_ID']; ?>"><?php echo $row_propstatusRS['DETAIL_DESCRIPTION']; ?> </option>

  <?php } while ($row_propstatusRS = mysql_fetch_assoc($propstatusRS)); ?>
  </select>
<?php
mysql_free_result($propstatusRS);
?>
