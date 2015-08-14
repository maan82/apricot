<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("Add or Edit Property");


if(isset($_GET['form_searchresults_proptype']) && !empty($_GET['form_searchresults_proptype'])){
	$_SESSION['form_searchresults_proptype'] = $_GET['form_searchresults_proptype'] ;
} else  if($_SESSION['form_searchresults_proptype'] && !empty($_SESSION['form_searchresults_proptype'])){
	$_GET['form_searchresults_proptype'] = $_SESSION['form_searchresults_proptype'];
}
if(isset($_GET['form_searchresults_propfor']) && !empty($_GET['form_searchresults_propfor'])) {
	$_SESSION['form_searchresults_propfor']	= $_GET['form_searchresults_propfor'];
} else  if($_SESSION['form_searchresults_propfor'] && !empty($_SESSION['form_searchresults_propfor'])){
	$_GET['form_searchresults_propfor'] = $_SESSION['form_searchresults_propfor'];
}
if(isset($_GET['form_searchresults_propstatus']) && !empty($_GET['form_searchresults_propstatus'])) {
	$_SESSION['form_searchresults_propstatus'] = $_GET['form_searchresults_propstatus'];
} else  if($_SESSION['form_searchresults_propstatus'] && !empty($_SESSION['form_searchresults_propstatus'])){
	$_GET['form_searchresults_propstatus'] = $_SESSION['form_searchresults_propstatus'];
}
if(isset($_GET['form_searchresults_sortby']) && !empty($_GET['form_searchresults_sortby'])) {
	$_SESSION['form_searchresults_sortby'] = $_GET['form_searchresults_sortby'];
} else  if($_SESSION['form_searchresults_sortby'] && !empty($_SESSION['form_searchresults_sortby'])){
	$_GET['form_searchresults_sortby'] = $_SESSION['form_searchresults_sortby'];
}
if(isset($_GET['maxRows_Search_Res_RS']) && !empty($_GET['maxRows_Search_Res_RS'])){
	$_SESSION['maxRows_Search_Res_RS'] = $_GET['maxRows_Search_Res_RS'];
} else  if($_SESSION['maxRows_Search_Res_RS'] && !empty($_SESSION['maxRows_Search_Res_RS'])){
	$_GET['maxRows_Search_Res_RS'] = $_SESSION['maxRows_Search_Res_RS'];
}


if ((isset($_GET["PROPDELETE"])) && ($_GET["PROPDELETE"] == "PROPDELETE")) {
  $updateSQL = sprintf("UPDATE property_details set STATUS_ID=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString(2, "int"),
					   GetSQLValueString($_GET['PROPERTY_ID'], "int"));
//echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  if($Result2){
		$url = "adminpropertysearch.php?MESSAGE=PROPDELETE";
	  header(sprintf("Location: %s", $url));
  }
}
if ((isset($_GET["PROPDELETE"])) && ($_GET["PROPDELETE"] == "PROPDELETEPERMANENT")) {
  $updateSQL = sprintf("DELETE from pictures WHERE PROPERTY_ID=%s",
                       GetSQLValueString($_GET['PROPERTY_ID'], "int"));

  $Result2 = mysql_query($updateSQL) or die(mysql_error());

  $updateSQL = sprintf("DELETE from property_details WHERE PROPERTY_ID=%s",
                       GetSQLValueString($_GET['PROPERTY_ID'], "int"));
//echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  if($Result2){
		$url = "adminpropertysearch.php?MESSAGE=PROPDELETE";
	  header(sprintf("Location: %s", $url));
  }
}
if ((isset($_GET["PROPWEBREMOVE"])) && ($_GET["PROPWEBREMOVE"] == "PROPWEBREMOVE")) {
  $updateSQL = sprintf("UPDATE property_details set STATUS_ID=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString(9, "int"),
					   GetSQLValueString($_GET['PROPERTY_ID'], "int"));
//echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  if($Result2){
		$url = "adminpropertysearch.php?MESSAGE=PROPWEBREMOVE";
	  header(sprintf("Location: %s", $url));
  }
}
if ((isset($_GET["PROPPUTONWEB"])) && ($_GET["PROPPUTONWEB"] == "PROPPUTONWEB")) {
  $updateSQL = sprintf("UPDATE property_details set STATUS_ID=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString(1, "int"),
					   GetSQLValueString($_GET['PROPERTY_ID'], "int"));
//echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  if($Result2){
		$url = "adminpropertysearch.php?MESSAGE=PROPPUTONWEB";
	  header(sprintf("Location: %s", $url));
  }
}

if ((isset($_GET["PROPRESALE"])) && ($_GET["PROPRESALE"] == "PROPRESALE")) {
  $updateSQL = sprintf("UPDATE property_details set STATUS_ID=%s WHERE PROPERTY_ID=%s",
                       GetSQLValueString(1, "int"),
					   GetSQLValueString($_GET['PROPERTY_ID'], "int"));
//echo $updateSQL;

  $Result2 = mysql_query($updateSQL) or die(mysql_error());
  if($Result2){
		$url = "adminpropertysearch.php?MESSAGE=PROPPUTONWEB";
	  header(sprintf("Location: %s", $url));
  }
}


$currentPage = $_SERVER["PHP_SELF"];

$maxRows_propRS = 25;
if (isset($_GET['maxRows_Search_Res_RS'])) {
  $maxRows_propRS = $_GET['maxRows_Search_Res_RS'];
}
$pageNum_propRS = 0;
if (isset($_GET['pageNum_propRS'])) {
  $pageNum_propRS = $_GET['pageNum_propRS'];
}
$startRow_propRS = $pageNum_propRS * $maxRows_propRS;



$query_proptypeRS = "SELECT PROPERTY_TYPE_ID, SHORT_DESCRIPTION FROM property_type_master";
$proptypeRS = mysql_query($query_proptypeRS) or die(mysql_error());
$row_proptypeRS = mysql_fetch_assoc($proptypeRS);
$totalRows_proptypeRS = mysql_num_rows($proptypeRS);


$query_propForRS = "SELECT * FROM property_for_master";
$propForRS = mysql_query($query_propForRS) or die(mysql_error());
$row_propForRS = mysql_fetch_assoc($propForRS);
$totalRows_propForRS = mysql_num_rows($propForRS);

$colname_propforRS = $row_propForRS['PROPERTY_FOR_ID'];
if (!empty($_GET['form_searchresults_propfor'])) {
	$colname_propforRS = $_GET['form_searchresults_propfor'];
} else {
	$_GET['form_searchresults_propfor'] = $colname_propforRS;
}

if($colname_propforRS == "1"){
	$colname_propstatusRS = "1,2,3,4,6,7,8,9";
} else {
	$colname_propstatusRS = "1,2,5,9";
}



$query_propstatusRS = sprintf("SELECT * FROM property_status_master WHERE STATUS_ID IN( %s) ORDER BY DETAIL_DESCRIPTION ASC", GetSQLValueString($colname_propstatusRS, ""));
$propstatusRS = mysql_query($query_propstatusRS) or die(mysql_error());
$row_propstatusRS = mysql_fetch_assoc($propstatusRS);
$totalRows_propstatusRS = mysql_num_rows($propstatusRS);


$colname_propRS = "";
if (isset($_GET['form_searchresults_proptype'])) {

	foreach ($_GET['form_searchresults_proptype'] as $proptype)
	{
		$colname_propRS.= $proptype.","	;
	}
	$ln = strlen($colname_propRS);
	$colname_propRS = substr_replace($colname_propRS,"",($ln-1));
} else {
			
			$typeStr = "";
			if ($totalRows_proptypeRS > 0) { // Show if recordset not empty 
			  do { 
					$typeStr .= $row_proptypeRS['PROPERTY_TYPE_ID'].","; 
				 } while ($row_proptypeRS = mysql_fetch_assoc($proptypeRS)); 
			   } // Show if recordset not empty 
			$ln = strlen($typeStr);
			$colname_propRS = substr_replace($typeStr,"",($ln-1));
			  $rows = mysql_num_rows($proptypeRS);
		  if($rows > 0) {
			  mysql_data_seek($proptypeRS, 0);
			  $row_proptypeRS = mysql_fetch_assoc($proptypeRS);
		  }
		  $selectall = "-1";
}




$orderBy_propRS = "PROPERTY_ID ASC";
if (!empty($_GET['form_searchresults_sortby'])) {
  $orderBy_propRS = $_GET['form_searchresults_sortby'];
}
$colname_propstatusRS = $row_propstatusRS['STATUS_ID'];
if(!empty($_GET['form_searchresults_propstatus'])){
	$colname_propstatusRS = $_GET['form_searchresults_propstatus'];
} else {
	$_GET['form_searchresults_status'] = $colname_propstatusRS;
}

if($colname_propstatusRS == 1 && $colname_propforRS == 1)
	$colname_propstatusRS = "1,3";


	
$query_propRS = sprintf("SELECT * FROM user_property_owner_receiver_amount_view WHERE STATUS_ID IN(%s) and PROPERTY_TYPE_ID IN( %s) and PROPERTY_FOR_ID IN(%s)", $colname_propstatusRS, $colname_propRS,$colname_propforRS);


if (!empty($_GET['form_searchresults_landlord'])) {
  $query_propRS = sprintf("  %s and (FIRSTNAME like(%s) or SURNAME like(%s)) ", $query_propRS, GetSQLValueString("%".$_GET['form_searchresults_landlord']."%", "text"), GetSQLValueString("%".$_GET['form_searchresults_landlord']."%", "text"));
}

if (!empty($_GET['form_searchresults_tenant']) ) { 
  $query_propRS = sprintf(" %s and (FIRSTNAME_RCVR like(%s) or SURNAME_RCVR like(%s)) ", $query_propRS, GetSQLValueString("%".$_GET['form_searchresults_tenant']."%", "text"), GetSQLValueString("%".$_GET['form_searchresults_tenant']."%", "text"));

}


if (!empty($_GET['form_searchresults_address'])) {

   $query_propRS = sprintf(" %s and PROP_ADDRESS like(%s)", $query_propRS, GetSQLValueString("%".$_GET['form_searchresults_address']."%", "text"));
}
$query_propRS = sprintf("%s ORDER BY %s", $query_propRS, GetSQLValueString($orderBy_propRS, ""));
$query_limit_propRS = sprintf("%s LIMIT %d, %d", $query_propRS, $startRow_propRS, $maxRows_propRS);
//echo $query_limit_propRS;

$_GET['property_id'] = trim($_GET['property_id']);
if(!empty($_GET['property_id'])){
	$query_limit_propRS = sprintf("SELECT * FROM user_property_owner_receiver_amount_view WHERE PROPERTY_ID = %s", GetSQLValueString($_GET['property_id'], "int"));
//	echo $query_limit_propRS;

}

$propRS = mysql_query($query_limit_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);


//$totalRows_propRS = mysql_num_rows($propRS);

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
<link  rel="stylesheet"  href="adminstyle.css" type="text/css" />

<script type="text/javascript" src="SpryAssets/SpryData.js"></script>


<!--end of Spry-->
<script type="text/javascript">
function submitForm(formID){
	document.getElementById(formID).submit();
}
function updateForm(obj){
	if(obj.value == 1 || obj.value == 2 || obj.value == 9){
		document.getElementById('form_searchresults_tenant_id').disabled = true;
	} else {
		document.getElementById('form_searchresults_tenant_id').disabled = false;
	}
}
// JavaScript Document
function getStatus(obj)
{
	var propFor = obj.value;
	var postDt = "PROPERTY_FOR_ID="+propFor;
	var req = Spry.Utils.loadURL("POST", "status.php", false, successCallbackGetStatus,  { postData: postDt, headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" }});

}
function successCallbackGetStatus(req){

   document.getElementById('form_searchresults_propstatusDiv').innerHTML = req.xhRequest.responseText;

}
</script>


</head>

<body >
<?php include('top.php')?>
<div>
<div class="orangehead">Properties Management</div>

    <?php message($_GET['MESSAGE']);?>

      <div class="table_grid"> 
        <div>
          <form class="form" id="form_searchresults_ID" name="form_searchresults" method="get" action="<?php printf("%s?pageNum_Test_RS=%d%s", $currentPage, 0, $queryString_propRS); ?>">
          <fieldset><legend>Property Filter</legend>
    <div>
    <div>
    <label class="label">Find By Property ID<br />
	    <input type="text" name="property_id" value="<?php echo $_GET['property_id'] ?>"/>
    </label>
    </div>
    <div class="orangetext" style="padding:5px">
    If you know property ID then specify it in above box OR Specify your search criteria using following options.
    </div>
<div >

<div style="float:left">            <label class="label"> Property Type<br />  <select name="form_searchresults_proptype[]" style="height:100px" multiple="multiple" id="form_searchresults_proptype_ID" >
              <?php
do {  
	$selected = "-1";
	
	foreach ($_GET['form_searchresults_proptype'] as $proptype)
	{
		if(strcmp($row_proptypeRS['PROPERTY_TYPE_ID'],$proptype) == 0)
		{
			$selected =  0; 
		}
		
	}

?>
              <option value="<?php echo $row_proptypeRS['PROPERTY_TYPE_ID']?>"<?php if ($selected == 0 || $selectall == "-1") {echo "selected=\"selected\"";} ?>><?php echo $row_proptypeRS['SHORT_DESCRIPTION'] ;
			  echo $tmp;
			  ?></option>
              <?php
} while ($row_proptypeRS = mysql_fetch_assoc($proptypeRS));
  $rows = mysql_num_rows($proptypeRS);
  if($rows > 0) {
      mysql_data_seek($proptypeRS, 0);
	  $row_proptypeRS = mysql_fetch_assoc($proptypeRS);
  }
?>
            </select></label><br />
            Use CTRL+Click to select/deslect multiple options.
            </div>
            <div style="float:left;margin-left:25px">
            
               <label class="label"> Property For         <br />  
            	<select name="form_searchresults_propfor" id="form_searchresults_propfor_id" style="width:130px" onchange="getStatus(this)">
            	  <?php
do {  
?>
            	  <option value="<?php echo $row_propForRS['PROPERTY_FOR_ID']?>"<?php if (!(strcmp($row_propForRS['PROPERTY_FOR_ID'], $_GET['form_searchresults_propfor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_propForRS['SHORT_DESCRIPTION']?></option>
            	  <?php
} while ($row_propForRS = mysql_fetch_assoc($propForRS));
  $rows = mysql_num_rows($propForRS);
  if($rows > 0) {
      mysql_data_seek($propForRS, 0);
	  $row_propForRS = mysql_fetch_assoc($propForRS);
  }
?>
                </select>
                </label>
            </div>
            <div style="float:left;margin-left:20px">
            
               <label class="label"> Property Status        <br />  
               <div id="form_searchresults_propstatusDiv">
            	<select name="form_searchresults_propstatus" id="form_searchresults_propstatus_Id" style="width:320px" onchange="updateForm(this)">
            	  <?php
do {  
?>
            	  <option value="<?php echo $row_propstatusRS['STATUS_ID']?>"<?php if (!(strcmp($row_propstatusRS['STATUS_ID'], $_GET['form_searchresults_propstatus']))) {echo "selected=\"selected\"";} ?>><?php echo $row_propstatusRS['DETAIL_DESCRIPTION']?></option>
            	  <?php
} while ($row_propstatusRS = mysql_fetch_assoc($propstatusRS));
  $rows = mysql_num_rows($propstatusRS);
  if($rows > 0) {
      mysql_data_seek($propstatusRS, 0);
	  $row_propstatusRS = mysql_fetch_assoc($propstatusRS);
  }
?>
                </select>
                </div>
                </label>
            </div>



<div style="float:left;margin-left:25px">              <label class="label"> Sort By<br /> <select name="form_searchresults_sortby" id="form_searchresults_sortby_ID" >
              <option value="PROPERTY_ID  ASC"  <?php if (!(strcmp("PROPERTY_ID ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>ID Ascending</option>

              <option value="PROPERTY_ID DESC"  <?php if (!(strcmp("PROPERTY_ID DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>ID Descending</option>

              <option value="PRICE  ASC"  <?php if (!(strcmp("PRICE ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Price Ascending</option>

              <option value="PRICE DESC"  <?php if (!(strcmp("PRICE DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Price Descending</option>

              <option value="BEDROOMS ASC"  <?php if (!(strcmp("BEDROOMS ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Bedrooms Ascending</option>

              <option value="BEDROOMS DESC"  <?php if (!(strcmp("BEDROOMS DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Bedrooms Descending</option>

              <option value="CREATION_DATE ASC"  <?php if (!(strcmp("CREATION_DATE ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Added On Ascending</option>
              <option value="CREATION_DATE DESC"  <?php if (!(strcmp("CREATION_DATE DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Added On Descending</option>
              <option value="UPDATION_DATE ASC"  <?php if (!(strcmp("UPDATION_DATE ASC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Updated On Ascending</option>
              <option value="UPDATION_DATE DESC"  <?php if (!(strcmp("UPDATION_DATE DESC", $_GET['form_searchresults_sortby']))) {echo "selected=\"selected\"";} ?>>Date Updated On Descending</option>

            </select></label></div>
                     

<div style="float:left;margin-left:25px">            <label class="label">Show<br />
              <select name="maxRows_Search_Res_RS" id="form_searchresults_recperpage_ID" >
                <option value="25"  <?php if (!(strcmp(25, $_GET['maxRows_Search_Res_RS']))) {echo "selected=\"selected\"";} ?>>25 Properties Per Page</option>
                <option value="50"  <?php if (!(strcmp(50, $_GET['maxRows_Search_Res_RS']))) {echo "selected=\"selected\"";} ?>>50 Properties Per Page</option>
              </select>
            </label></div>
            <div style="clear:both"></div>
<div style="float:right;margin-left:25px;padding-top:10px;clear:both">               <input type="submit" value="Submit" class="button"/>           </div>
            




           
</div>
     
            </div>
            </fieldset>
    </form>
        </div>
        <?php if($totalRows_propRS > 0) {?>
        <div style="clear:both">

            <div style="float:left" class="label">
            Total <?php echo $totalRows_propRS ?> Properties Found.<br />
                    Displaying <?php echo ($startRow_propRS + 1) ?> to <?php echo min($startRow_propRS + $maxRows_propRS, $totalRows_propRS) ?> 

            </div>

        <div class="pagination">
<table border="0" class="label">
          <tr>
            <td><?php if ($pageNum_propRS > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, 0, $queryString_propRS); ?>">First</a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_propRS > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, max(0, $pageNum_propRS - 1), $queryString_propRS); ?>">Previous</a>
              <?php } // Show if not first page ?></td>
            <td><?php if ($pageNum_propRS < $totalPages_propRS) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, min($totalPages_propRS, $pageNum_propRS + 1), $queryString_propRS); ?>">Next</a>
              <?php } // Show if not last page ?></td>
            <td><?php if ($pageNum_propRS < $totalPages_propRS) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_propRS=%d%s", $currentPage, $totalPages_propRS, $queryString_propRS); ?>">Last</a>
            <?php } // Show if not last page ?></td>
      </tr>
    </table>
    </div>
    <div style="clear:both">&nbsp;</div>
    </div>
<table cellspacing="0" cellpadding="0" border="1px" bordercolor="#666666" >
          <tr>
            <th style="width:20px;" class="greentableheading"><span class="greentableheading">ID</span></th>
            <th style="width:100px;" class="greentableheading"><span class="greentableheading">Picture</span></th>
            <th style="width:auto;" class="greentableheading"><span class="greentableheading">Property  Details</span></th>

            <th style="width:80px; text-align:center;" class="greentableheading"><span class="greentableheading">Actions</span></th>
</tr>
          <?php $i = 0;
					 ?>
          <?php do { ?>
            <tr  <?php if(($i%2) == 1){  ?>class="even"<?php } else {?> class="odd"<?php } ++$i;?>>
              <td valign="top"><?php echo $row_propRS['PROPERTY_ID']; ?></td>
              <td style="text-align:left" valign="top">
<a href="../pages/property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>" >
              <img border="0" src="<?php echo $row_propRS['THUMB_PIC_PATH']; ?>"/>
</a><br/>

              </td>
              <td valign="top">
              <span class="tablabel">For :- </span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION']; ?><br />
<span class="tablabel">Price :- </span><?php echo $row_propRS['PRICE']; ?><br />
<span class="tablabel">Features :- </span><?php echo $row_propRS['BEDROOMS']; ?> Bed <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?><br />
<span class="tablabel">Address :- </span><?php echo $row_propRS['PROP_ADDRESS']; ?><br />


			  </td>
              <td valign="top">
              <?php include("adminpropertyactions.php")?>
              
          </tr>
            <?php } while ($row_propRS = mysql_fetch_assoc($propRS)); ?>
    </table>
            <?php } else {?>
            
            <div class="phead">No Result Found</div>
            <?php } ?>
      </div>      
</div>

<?php include('bottom.php')?>
</body>
</html>