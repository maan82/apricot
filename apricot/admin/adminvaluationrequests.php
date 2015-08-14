<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("View Valuation Requests");
if(isset($_GET['ID'])){
	if(isset($_GET['action']) && $_GET['action'] == "read") {
		$updateSQL = sprintf("UPDATE property_valuation_request SET READ_STATUS='Y' WHERE ID=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	} else if (isset($_GET['action']) && $_GET['action'] == "delete") {
		$updateSQL = sprintf("DELETE from property_valuation_request WHERE ID=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	}
//echo $updateSQL;
    if ($updateSQL != null) 
		$Result1 = mysql_query($updateSQL) or die(mysql_error());
}


$currentPage = $_SERVER["PHP_SELF"];

$maxRows_queriesRS = 100;
$pageNum_queriesRS = 0;
if (isset($_GET['pageNum_queriesRS'])) {
  $pageNum_queriesRS = $_GET['pageNum_queriesRS'];
}
$startRow_queriesRS = $pageNum_queriesRS * $maxRows_queriesRS;


$query_queriesRS = "SELECT * FROM property_valuation_request_view ORDER BY CREATION_DATE DESC";
$query_limit_queriesRS = sprintf("%s LIMIT %d, %d", $query_queriesRS, $startRow_queriesRS, $maxRows_queriesRS);
$queriesRS = mysql_query($query_limit_queriesRS) or die(mysql_error());
$row_queriesRS = mysql_fetch_assoc($queriesRS);

if (isset($_GET['totalRows_queriesRS'])) {
  $totalRows_queriesRS = $_GET['totalRows_queriesRS'];
} else {
  $all_queriesRS = mysql_query($query_queriesRS);
  $totalRows_queriesRS = mysql_num_rows($all_queriesRS);
}
$totalPages_queriesRS = ceil($totalRows_queriesRS/$maxRows_queriesRS)-1;

$queryString_queriesRS = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_queriesRS") == false && 
        stristr($param, "totalRows_queriesRS") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_queriesRS = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_queriesRS = sprintf("&totalRows_queriesRS=%d%s", $totalRows_queriesRS, $queryString_queriesRS);
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
   
   <div class="orangehead">Property Valuation Requests</div>
      <div class="table_grid"> 
        <div>
          
        </div>
        <div style="float:right;">
        <table border="0" >
          <tr>
            <td><?php if ($pageNum_queriesRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, 0, $queryString_queriesRS); ?>">First</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_queriesRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, max(0, $pageNum_queriesRS - 1), $queryString_queriesRS); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_queriesRS < $totalPages_queriesRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, min($totalPages_queriesRS, $pageNum_queriesRS + 1), $queryString_queriesRS); ?>">Next</a>
            <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_queriesRS < $totalPages_queriesRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, $totalPages_queriesRS, $queryString_queriesRS); ?>">Last</a>
            <?php } // Show if not last page ?></td>
          </tr>
        </table>
        </div>
<table cellspacing="0" cellpadding="0" border="1px" bordercolor="#666666" >
          <tr>
            <th style="width:80px;" class="greentableheading"><span class="greentableheading">Date</span></th>
            <th style="width:130px;" class="greentableheading"><span class="greentableheading">Property Details</span></th>
            <th style="width:auto;" class="greentableheading"><span class="greentableheading">Message</span></th>
            <th style="width:180px; text-align:center;" class="greentableheading"><span class="greentableheading">Contact</span></th>
            <th style="width:50px; text-align:center;" class="greentableheading"><span class="greentableheading">Action</span></th>
</tr>
          <?php do { 
		  $style = "";
		  if($row_queriesRS['READ_STATUS'] == 'N'){
			  $style = 'style="color:#000;font-weight:bold"';
		  }
		  
		  ?>
  <tr  <?php if(($i%2) == 1){  ?>class="even"<?php } else {?> class="odd"<?php } ++$i;?> >
    <td <?php echo $style; ?>><?php echo fromDBDate($row_queriesRS['CREATION_DATE']); ?></td>
    <td <?php echo $style; ?>>
    
    Property For :- <?php echo $row_queriesRS['FOR_SHORT_DESCRIPTION']; ?><br  />
    Property Type :- <?php echo $row_queriesRS['TYPE_SHORT_DESCRIPTION']; ?><br  />
    Beds:- <?php echo $row_queriesRS['BEDROOMS']; ?><br  />
    Bath:- <?php echo $row_queriesRS['BATHROOMS']; ?><br  />
    Living Room:- <?php echo $row_queriesRS['DRAWING_ROOMS']; ?><br  />
    </td>
    <td <?php echo $style; ?>>
      <?php echo $row_queriesRS['MESSAGE']; ?>
    </td>

    <td <?php echo $style; ?>>

    
    Title :-<?php echo $row_queriesRS['TITLE_DESCRIPTION']; ?><br />
    First Name :-<?php echo $row_queriesRS['FIRST_NAME']; ?><br />
    Surname :-<?php echo $row_queriesRS['SURNAME']; ?><br />
    Email :- <?php echo $row_queriesRS['EMAIL_ID']; ?><br />
    Prefered Call Time :-     <?php echo $row_queriesRS['TIME']; ?><br />
    Prefered Call No :- <?php echo $row_queriesRS['PREF_NO_DESCRIPTION']; ?><br />
    Home No :- <?php echo $row_queriesRS['HOME_NO']; ?><br />
    Work No :- <?php echo $row_queriesRS['WORK_NO']; ?><br />
    Mobile No  :- <?php echo $row_queriesRS['MOB_NO']; ?><br />
    
Address :- <?php echo $row_queriesRS['ADDRESS']; ?><br />
Postcode :- <?php echo $row_queriesRS['POSTCODE']; ?><br />
    </td>            
    <td>

    	<?php if($row_queriesRS['READ_STATUS'] == "N"){?> <a href="adminvaluationrequests.php?action=read&ID=<?php echo $row_queriesRS['ID']?>"><input type="button"  class="button" value="Mark Read"/></a><?php }?>
    	<br />
    	<a onclick="return confirm ('Are you sure you want to delete this record?')" href="adminvaluationrequests.php?action=delete&ID=<?php echo $row_queriesRS['ID']?>"><input type="button"  class="button" value="Delete"/></a>
    
    </td>
    </tr>
  <?php } while ($row_queriesRS = mysql_fetch_assoc($queriesRS)); ?>
</table>
        <div style="float:right;">
        <table border="0" >
          <tr>
            <td><?php if ($pageNum_queriesRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, 0, $queryString_queriesRS); ?>">First</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_queriesRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, max(0, $pageNum_queriesRS - 1), $queryString_queriesRS); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_queriesRS < $totalPages_queriesRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, min($totalPages_queriesRS, $pageNum_queriesRS + 1), $queryString_queriesRS); ?>">Next</a>
            <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_queriesRS < $totalPages_queriesRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_queriesRS=%d%s", $currentPage, $totalPages_queriesRS, $queryString_queriesRS); ?>">Last</a>
            <?php } // Show if not last page ?></td>
          </tr>
        </table>
        </div>

      </div>      

    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($queriesRS);
?>
