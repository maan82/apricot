<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("View Mortgage Queries");

if(isset($_GET['ID'])){
	if(isset($_GET['action']) && $_GET['action'] == "read") {
		$updateSQL = sprintf("UPDATE mortgage_calculations SET READ_STATUS='Y' WHERE ID_MC=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	} else if (isset($_GET['action']) && $_GET['action'] == "delete") {
		$updateSQL = sprintf("DELETE from mortgage_calculations WHERE ID_MC=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	}
//echo $updateSQL;
    if ($updateSQL != null) 
		$Result1 = mysql_query($updateSQL) or die(mysql_error());
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_calcRS = 100;
$pageNum_calcRS = 0;
if (isset($_GET['pageNum_calcRS'])) {
  $pageNum_calcRS = $_GET['pageNum_calcRS'];
}
$startRow_calcRS = $pageNum_calcRS * $maxRows_calcRS;


$query_calcRS = "SELECT * FROM mortgage_calculations ORDER BY CREATION_DATE_MC DESC";
$query_limit_calcRS = sprintf("%s LIMIT %d, %d", $query_calcRS, $startRow_calcRS, $maxRows_calcRS);
$calcRS = mysql_query($query_limit_calcRS) or die(mysql_error());
$row_calcRS = mysql_fetch_assoc($calcRS);

if (isset($_GET['totalRows_calcRS'])) {
  $totalRows_calcRS = $_GET['totalRows_calcRS'];
} else {
  $all_calcRS = mysql_query($query_calcRS);
  $totalRows_calcRS = mysql_num_rows($all_calcRS);
}
$totalPages_calcRS = ceil($totalRows_calcRS/$maxRows_calcRS)-1;

$queryString_calcRS = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_calcRS") == false && 
        stristr($param, "totalRows_calcRS") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_calcRS = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_calcRS = sprintf("&totalRows_calcRS=%d%s", $totalRows_calcRS, $queryString_calcRS);



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
   <div class="orangehead">Calculations On Mortgage Calculator</div>
      <div class="table_grid"> 
        <div>
          
        </div>
        <div style="float:right;">
        <table border="0" >
          <tr>
            <td><?php if ($pageNum_calcRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, 0, $queryString_calcRS); ?>">First</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_calcRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, max(0, $pageNum_calcRS - 1), $queryString_calcRS); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_calcRS < $totalPages_calcRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, min($totalPages_calcRS, $pageNum_calcRS + 1), $queryString_calcRS); ?>">Next</a>
            <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_calcRS < $totalPages_calcRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, $totalPages_calcRS, $queryString_calcRS); ?>">Last</a>
            <?php } // Show if not last page ?></td>
          </tr>
        </table>
        </div>
<table cellspacing="0" cellpadding="0" border="1px" bordercolor="#666666" >
          <tr>
            <th style="width:80px;" class="greentableheading"><span class="greentableheading">Date</span></th>
            <th style="width:230px;" class="greentableheading"><span class="greentableheading">Email, Contact No</span></th>
            <th style="width:auto;" class="greentableheading"><span class="greentableheading">Details</span></th>
            <th style="width:50px; text-align:center;" class="greentableheading"><span class="greentableheading">Action</span></th>
</tr>
          <?php do { 
		  $style = "";
		  if($row_calcRS['READ_STATUS'] == 'N'){
			  $style = 'style="color:#000;font-weight:bold"';
		  }
		  
		  ?>
  <tr  <?php if(($i%2) == 1){  ?>class="even"<?php } else {?> class="odd"<?php } ++$i;?> >
    <td <?php echo $style; ?>><?php echo fromDBDate($row_calcRS['CREATION_DATE_MC']); ?></td>
    <td <?php echo $style; ?>>
        Email :-<?php echo $row_calcRS['EMAIL_ID_MC']; ?><br />
    Contact No :-<?php echo $row_calcRS['CONTACT_NO_MC']; ?><br />


    </td>
    <td <?php echo $style; ?>>
            Home Price :-<?php echo $row_calcRS['HOME_PRICE_MC']; ?><br />
    Down Payment :-<?php echo $row_calcRS['DOWN_MC']; ?>% = <?php echo ($row_calcRS['HOME_PRICE_MC'] * $row_calcRS['DOWN_MC'])/100; ?><br />
        Length Of Mortgage :-<?php echo $row_calcRS['MORTGAGE_LENGTH_MC']; ?> years<br />
    Interest Rate :-<?php echo $row_calcRS['RATE_MC']; ?>%<br />

    </td>

                
    <td>
        	<?php if($row_calcRS['READ_STATUS'] == "N"){?> <a href="adminmortgagecalculations.php?action=read&ID=<?php echo $row_calcRS['ID_MC']?>"><input type="button"  class="button" value="Mark Read"/></a><?php }?>
    	<br />
    	<a onclick="return confirm ('Are you sure you want to delete this record?')" href="adminmortgagecalculations.php?action=delete&ID=<?php echo $row_calcRS['ID_MC']?>"><input type="button"  class="button" value="Delete"/></a>
	
    </td>
    </tr>
  <?php } while ($row_calcRS = mysql_fetch_assoc($calcRS)); ?>
</table>
        <div style="float:right;">
        <table border="0" >
          <tr>
            <td><?php if ($pageNum_calcRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, 0, $queryString_calcRS); ?>">First</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_calcRS > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, max(0, $pageNum_calcRS - 1), $queryString_calcRS); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_calcRS < $totalPages_calcRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, min($totalPages_calcRS, $pageNum_calcRS + 1), $queryString_calcRS); ?>">Next</a>
            <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_calcRS < $totalPages_calcRS) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_calcRS=%d%s", $currentPage, $totalPages_calcRS, $queryString_calcRS); ?>">Last</a>
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
mysql_free_result($calcRS);
?>