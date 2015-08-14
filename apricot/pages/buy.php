<?php require_once('constants.php'); ?>
<?php require_once('functions.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_propRS = 18;
if (isset($_GET['maxRows_Search_Res_RS']) && $_GET['maxRows_Search_Res_RS'] != "") {
  $maxRows_propRS = $_GET['maxRows_Search_Res_RS'];
}

/*if(isset($_GET['viewas']) &&  strcmp($_GET['viewas'],"M")== 0){ 
	$maxRows_propRS = 999999999;
}*/

$pageNum_propRS = 0;
if (isset($_GET['pageNum_propRS'])) {
  $pageNum_propRS = $_GET['pageNum_propRS'];
}
$startRow_propRS = $pageNum_propRS * $maxRows_propRS;

$colname_propRS_for = "1";

$orderBy_propRS = "ABS(PRICE) ASC";
if (isset($_GET['form_searchresults_sortby']) && $_GET['form_searchresults_sortby'] != "") {
  $orderBy_propRS = $_GET['form_searchresults_sortby'];
}


$query_propRS = sprintf("SELECT * FROM property_details_website_view where PROPERTY_FOR_ID = %s ORDER BY %s",$colname_propRS_for,$orderBy_propRS);
$query_limit_propRS = sprintf("%s LIMIT %d, %d", $query_propRS, $startRow_propRS, $maxRows_propRS);
$propRS = mysql_query($query_limit_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);

if (isset($_GET['totalRows_propRS'])) {
  $totalRows_propRS = $_GET['totalRows_propRS'];
} else {
  $all_propRS = mysql_query($query_propRS);
  $totalRows_propRS = mysql_num_rows($all_propRS);
}
$totalPages_propRS = ceil($totalRows_propRS/$maxRows_propRS)-1;

$queryString_propRS = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_propRS") == false && 
        stristr($param, "totalRows_propRS") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_propRS = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_propRS = sprintf("&totalRows_propRS=%d%s", $totalRows_propRS, $queryString_propRS);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<link href="bg_blocks.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
	<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js"></script>
</head>

<body >
<?php include('header.php'); ?>

<div id="maincontent" >
<div  >

<div class="block">
		<div>

    <div style="border-bottom:1px #999 dashed;">
  <form class="form" id="form_searchresults_ID" name="form_searchresults" method="GET" action="" enctype="application/x-www-form-urlencoded">
<input name="lookingfor" type="hidden" value="<?php echo($_GET['lookingfor'])?>" />
<input name="propertytype" type="hidden" value="<?php echo($_GET['propertytype'])?>" />
<input name="price" type="hidden" value="<?php echo($_GET['price'])?>" />
<input name="beds" type="hidden" value="<?php echo($_GET['beds'])?>" />
  <div style="float:left;">              <label class="label"> Sort By<br /> <select name="form_searchresults_sortby" id="form_searchresults_sortby_ID" onchange="submitForm('form_searchresults_ID')">
              <option value="ABS(PRICE)  ASC"  <?php if (!(strcmp("ABS(PRICE)  ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Price Ascending</option>

              <option value="ABS(PRICE) DESC"  <?php if (!(strcmp("ABS(PRICE) DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Price Descending</option>
              <option value="ABS(BEDROOMS) ASC"  <?php if (!(strcmp("ABS(BEDROOMS) ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Beds Ascending</option>

              <option value="ABS(BEDROOMS) DESC"  <?php if (!(strcmp("ABS(BEDROOMS) DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Beds Descending</option>

              <option value="CREATION_DATE ASC"  <?php if (!(strcmp("CREATION_DATE ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Added On Ascending</option>
              <option value="CREATION_DATE DESC"  <?php if (!(strcmp("CREATION_DATE DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Added On Descending</option>
              <option value="UPDATION_DATE ASC"  <?php if (!(strcmp("UPDATION_DATE ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Updated On Ascending</option>
              <option value="UPDATION_DATE DESC"  <?php if (!(strcmp("UPDATION_DATE DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Updated On Descending</option>

            </select></label></div>
        
				  <div style="float:left;margin-left:10px">            <label class="label">Show<br />
              <select name="maxRows_Search_Res_RS" id="form_searchresults_recperpage_ID" onchange="submitForm('form_searchresults_ID')">
                <option value="18"  <?php if (!(strcmp(18, $_GET['maxRows_Search_Res_RS']))) {echo "selected=\"selected\"";} ?>>18 Properties Per Page</option>
                <option value="27"  <?php if (!(strcmp(27, $_GET['maxRows_Search_Res_RS']))) {echo "selected=\"selected\"";} ?>>27 Properties Per Page</option>
                <option value="36"  <?php if (!(strcmp(36, $_GET['maxRows_Search_Res_RS']))) {echo "selected=\"selected\"";} ?>>36 Properties Per Page</option>
              </select>
            </label></div>
        
            <div style="float:left;width:255px;margin-left:10px;padding-top:15px" class="label">
            


  <label class="label">
    <input type="radio" name="viewas" value="L"  checked="checked" onclick="submitForm('form_searchresults_ID')"/>
    List View</label>

  <label class="label">
    <input type="radio" name="viewas" value="M"  <?php if(strcmp($_GET['viewas'],"M")== 0) echo "checked=\"checked\"";?> onclick="submitForm('form_searchresults_ID')"/>
    Map View</label>



</div>


    </form>
         <div class="clear" style="height:5px;clear:both"></div>
    </div>
        
            <div style="float:left" class="label">
            Total <?php echo $totalRows_propRS ?> Properties Found.<br />
                    Displaying <?php echo ($startRow_propRS + 1) ?> to <?php echo min($startRow_propRS + $maxRows_propRS, $totalRows_propRS) ?> 

            </div>
    <div class="pagination">
      <table border="0">
        <tr>
          <td><?php if ($pageNum_propRS > 0) { // Show if not first page ?>
              <a class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, 0, $queryString_propRS); ?>">First</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_propRS > 0) { // Show if not first page ?>
              <a  class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, max(0, $pageNum_propRS - 1), $queryString_propRS); ?>">Previous</a>
              <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_propRS < $totalPages_propRS) { // Show if not last page ?>
              <a  class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, min($totalPages_propRS, $pageNum_propRS + 1), $queryString_propRS); ?>">Next</a>
              <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_propRS < $totalPages_propRS) { // Show if not last page ?>
              <a  class="button" href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, $totalPages_propRS, $queryString_propRS); ?>">Last</a>
              <?php } // Show if not last page ?></td>
        </tr>
      </table>
    </div>
       


        </div>
       <div style="clear:both;"></div>
               </div>
        <div>
        <div id="properties">
      <?php if ($totalRows_propRS > 0) { // Show if recordset not empty ?>
	      <?php 
			  if(isset($_GET['viewas']) &&  $_GET['viewas'] == "G") {
				  include('inc-grid-view.php');
			  } else if(isset($_GET['viewas']) &&  $_GET['viewas'] == "M"){
				  include('inc-map-view.php');
			  } else  { 
          		  include('inc-list-view.php');
			  }
			// End of grid view?>  
			<div class="block">

		<?php
    include('inc-pagination.php');
  ?>
</div>
        <?php } // Show if recordset not empty ?>
    </div>


</div>
</div>




</div>


<?php include('footer.php'); ?>

</body>
</html>
<?php
mysql_free_result($propRS);
?>