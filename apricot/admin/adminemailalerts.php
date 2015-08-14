<?php require_once('Connections/adestate.php'); ?>
<?php require_once('class.Email.php'); ?>
<?php require_once('functions.php'); ?>
<?php require_once('../pages/constants.php'); ?>

<?php
sessionStart("View Contact Us Queries");
if(isset($_GET['ID'])){
	if(isset($_GET['action']) && $_GET['action'] == "read") {
		$updateSQL = sprintf("UPDATE email_alerts SET READ_STATUS='Y' WHERE EMAIL_ALERT_ID=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	} else if (isset($_GET['action']) && $_GET['action'] == "delete") {
		$updateSQL = sprintf("DELETE from email_alerts WHERE EMAIL_ALERT_ID=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	} else if (isset($_GET['action']) && $_GET['action'] == "disable") {
		$updateSQL = sprintf("UPDATE email_alerts SET ENABLED='0' WHERE EMAIL_ALERT_ID=%s",						
							   GetSQLValueString($_GET['ID'], "int"));
	} else if (isset($_GET['action']) && $_GET['action'] == "enable") {
		$updateSQL = sprintf("UPDATE email_alerts SET ENABLED='1' WHERE EMAIL_ALERT_ID=%s",						
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


$query_queriesRS = "SELECT * FROM email_alerts_view where 1=1 ";




if (!empty($_GET['form_emailalerts_name'])) {
  $query_queriesRS = sprintf("  %s and NAME like(%s) ", $query_queriesRS, GetSQLValueString("%".$_GET['form_emailalerts_name']."%", "text"), GetSQLValueString("%".$_GET['form_searchresults_landlord']."%", "text"));
}

if (!empty($_GET['form_emailalerts_number'])) {
  $query_queriesRS = sprintf("  %s and CONTACT_NO like(%s) ", $query_queriesRS, GetSQLValueString("%".$_GET['form_emailalerts_number']."%", "text"), GetSQLValueString("%".$_GET['form_searchresults_landlord']."%", "text"));
}

if (!empty($_GET['form_emailalerts_email'])) {
  $query_queriesRS = sprintf("  %s and EMAIL_ID like(%s) ", $query_queriesRS, GetSQLValueString("%".$_GET['form_emailalerts_email']."%", "text"), GetSQLValueString("%".$_GET['form_searchresults_landlord']."%", "text"));
}

$colname_propRS_for = "-1";
if (isset($_GET['form_emailalerts_lookingfor']) && !empty($_GET['form_emailalerts_lookingfor'])) {
  $colname_propRS_for = $_GET['form_emailalerts_lookingfor'];
  $query_queriesRS = sprintf("%s and  PROPERTY_FOR_ID IN(%s) ",$query_queriesRS, GetSQLValueString($colname_propRS_for, "int"));
  	
}

$colname_propRS_type = "-1";
if (isset($_GET['form_emailalerts_propertytype'])  && !empty($_GET['form_emailalerts_propertytype'])) {
  $colname_propRS_type = $_GET['form_emailalerts_propertytype'];
  $query_queriesRS = sprintf("%s and  PROPERTY_TYPE_ID IN(%s) ",$query_queriesRS, GetSQLValueString($colname_propRS_type, ""));
}
$colname_propRS_price = "-1";
if (isset($_GET['form_emailalerts_price'])  && !empty($_GET['form_emailalerts_price'])) {
  $colname_propRS_price = $_GET['form_emailalerts_price'];
    $query_queriesRS = sprintf("%s and MAX_PRICE <= %s ",$query_queriesRS, GetSQLValueString($colname_propRS_price, "double"));
}
$colname_propRS_beds = "-1";
if (isset($_GET['form_emailalerts_beds']) && !empty($_GET['form_emailalerts_beds'])) {
  $colname_propRS_beds = $_GET['form_emailalerts_beds'];
  $query_queriesRS = sprintf("%s  and BEDROOMS >= %s  ",$query_queriesRS, GetSQLValueString($colname_propRS_beds, "int"));
}


$query_limit_queriesRS = sprintf("%s  ORDER BY CREATION_DATE DESC LIMIT %d, %d", $query_queriesRS, $startRow_queriesRS, $maxRows_queriesRS);
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

$query_Prop_Type_RS = "SELECT * FROM property_type_master";
$Prop_Type_RS = mysql_query($query_Prop_Type_RS) or die(mysql_error());
$row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
$totalRows_Prop_Type_RS = mysql_num_rows($Prop_Type_RS);

if (isset($_GET['sendemailalerts']) && isset($_GET['selectedid'])) {
  $email_alerts_query_queriesRS = "SELECT * FROM email_alerts_view where (enabled=1 or enabled is NULL) ";
  if (isset($_GET['selectedid'])) {
	$email_alerts_query_queriesRS = sprintf("%s  AND (  ", $email_alerts_query_queriesRS);
  	foreach ($_GET['selectedid'] as $value) {
  		$email_alerts_query_queriesRS = sprintf("%s   EMAIL_ALERT_ID = %s OR ", $email_alerts_query_queriesRS, GetSQLValueString($value, "int") );
	}
	$email_alerts_query_queriesRS = sprintf("%s  EMAIL_ALERT_ID = -1) ", $email_alerts_query_queriesRS);

  }	
  $email_alerts_query_queriesRS = sprintf("%s ORDER BY EMAIL_ALERT_ID desc LIMIT 50", $email_alerts_query_queriesRS);
  //echo $email_alerts_query_queriesRS;
  $email_alerts_queriesRS = mysql_query($email_alerts_query_queriesRS) or die(mysql_error());
  while($rows_array[]=mysql_fetch_array($email_alerts_queriesRS, MYSQL_ASSOC));
  $totalRows_email_alerts_queriesRS = 0;
  $no_match = 0;
  $Subject = 'Property Details';
  foreach ( $rows_array as $row) {
  	  if (!empty($row['EMAIL_ID'])) { 
		  $URL = 'http://'.$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/aeon/pages/email-alert-properties.php?alertid=".$row['EMAIL_ALERT_ID']."&lookingfor=".$row['PROPERTY_FOR_ID']."&price=".$row['MAX_PRICE']."&bedrooms=".$row['BEDROOMS']."&type=".$row['PROPERTY_TYPE_ID']."&name=".$row['NAME'];
	      echo $URL;
	      $contents = file_get_contents($URL);
	      if (strpos($contents, 'img') !== FALSE) {
	  		  $message = new Email($row['EMAIL_ID'], $EMAIL, $Subject, $CustomHeaders);
			  $message->SetHtmlContent($contents);
			  $message->Send();
		  	  ++$totalRows_email_alerts_queriesRS;
		  } else {
		  	++$no_match;
		  }
	  }
  }   
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">
<link  rel="stylesheet"  href="adminstyle.css" type="text/css" />
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script src="../pages/js/site.js" type="text/javascript"></script>

<style>
.pntr{cursor:pointer}
</style>


</head>

<body >
<?php include('top.php');?>

      <div class="table_grid">
      		
        <div>
        	<form id="form_emailalerts_ID" name="form_emailalerts" action="adminemailalerts.php" method="GET">
        	<fieldset>
        		<legend>Contact Search</legend>
        	<label class="label">Name</label>  <input type="text" name="form_emailalerts_name" value="<?php echo($_GET['form_emailalerts_name'])?>">
        	<label class="label">Email</label>  <input type="text" name="form_emailalerts_email" value="<?php echo($_GET['form_emailalerts_email'])?>">
        	<label class="label">Number</label>  <input type="text" name="form_emailalerts_number" value="<?php echo($_GET['form_emailalerts_number'])?>">
			<br />
			<br />
                        <label class="label">
                          <input type="radio" name="form_emailalerts_lookingfor" value="1" onclick="popPrice(this,'priceDiv')" id="lookingfor_buy" <?php if(strcmp($_GET['form_emailalerts_lookingfor'],"2")!= 0) echo "checked=\"checked\"";?> />
                          For Sale</label>
                        <label class="label">
                          <input type="radio" name="form_emailalerts_lookingfor" value="2" onclick="popPrice(this,'priceDiv')" id="lookingfor_rent" <?php if(strcmp($_GET['form_emailalerts_lookingfor'],"2")== 0) echo "checked=\"checked\"";?> />
                          To Rent</label>
            <br />              
			<br />

	                    <label  class="label">Property Type </label>

                        <select name="form_emailalerts_propertytype" id="propertytype_id">
                          <?php $proptypejoin="";
do {  
?>
                          <option value="<?php echo $row_Prop_Type_RS['PROPERTY_TYPE_ID']?>" <?php if ( !(strcmp($row_Prop_Type_RS['PROPERTY_TYPE_ID'], $_GET['form_emailalerts_propertytype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Prop_Type_RS['SHORT_DESCRIPTION']?></option>
                          <?php
  		$proptypejoin.= $row_Prop_Type_RS['PROPERTY_TYPE_ID'].","	;

} while ($row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS));
  $rows = mysql_num_rows($Prop_Type_RS);
  if($rows > 0) {
      mysql_data_seek($Prop_Type_RS, 0);
	  $row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
  }
  $proptypejoin.= "0".","	;
  	$ln = strlen($proptypejoin);
	$proptypejoin = substr_replace($proptypejoin,"",($ln-1));

?>
                          <option value="<?php echo $proptypejoin ?>" <?php if (!(strcmp($proptypejoin, $_GET['form_emailalerts_propertytype']))  || !isset($_GET['form_emailalerts_propertytype'])) {echo "selected=\"selected\"";} ?>>Any</option>
                        </select>
                      	
                        <label  class="label">Price </label>
<span id="priceDiv">
                        <select name="form_emailalerts_price" id="price_id">
                          <?php if($_GET['lookingfor']  != '2'){?>
                          <option value="50000"<?php if (!(strcmp("50000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;50000</option>
                          <option value="100000"<?php if (!(strcmp("100000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;100000</option>
                          <option value="150000"<?php if (!(strcmp("150000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;150000</option>
                          <option value="200000"<?php if (!(strcmp("200000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;200000</option>
                          <option value="250000"<?php if (!(strcmp("250000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;250000</option>
                          <option value="300000"<?php if (!(strcmp("300000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;300000</option>
                          <option value="350000"<?php if (!(strcmp("350000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;350000</option>
                          <option value="400000"<?php if (!(strcmp("400000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;400000</option>
                          <option value="450000"<?php if (!(strcmp("450000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;450000</option>
                          <option value="500000"<?php if (!(strcmp("500000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;500000</option>
                          <option value="550000"<?php if (!(strcmp("550000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;550000</option>
                          <option value="600000"<?php if (!(strcmp("600000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;600000</option>
                          <option value="650000"<?php if (!(strcmp("650000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;650000</option>
                          <option value="700000"<?php if (!(strcmp("700000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;700000</option>
                          <option value="750000"<?php if (!(strcmp("750000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;750000</option>
                          <option value="800000"<?php if (!(strcmp("800000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;800000</option>
                          <option value="850000"<?php if (!(strcmp("850000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;850000</option>
                          <option value="900000"<?php if (!(strcmp("900000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;900000</option>
                          <option value="950000"<?php if (!(strcmp("950000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;950000</option>
                          <option value="9999999999999"<?php if (!(strcmp("9999999999999", $_GET['form_emailalerts_price']))  || !isset($_GET['form_emailalerts_price'])) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Any Price</option>
                          <?php }else if($_GET['lookingfor']  == '2'){?>
                          <option value="300"<?php if (!(strcmp("300", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;300</option>
                          <option value="400"<?php if (!(strcmp("400", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;400</option>
                          <option value="500"<?php if (!(strcmp("500", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;500</option>
                          <option value="600"<?php if (!(strcmp("600", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;600</option>
                          <option value="700"<?php if (!(strcmp("700", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;700</option>
                          <option value="800"<?php if (!(strcmp("800", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;800</option>
                          <option value="900"<?php if (!(strcmp("900", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;900</option>
                          <option value="1000"<?php if (!(strcmp("1000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1000</option>
                          <option value="1100"<?php if (!(strcmp("1100", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1100</option>
                          <option value="1200"<?php if (!(strcmp("1200", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1200</option>
                          <option value="1300"<?php if (!(strcmp("1300", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1300</option>
                          <option value="1400"<?php if (!(strcmp("1400", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1400</option>
                          <option value="1500"<?php if (!(strcmp("1500", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1500</option>
                          <option value="1600"<?php if (!(strcmp("1600", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1600</option>
                          <option value="1700"<?php if (!(strcmp("1700", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1700</option>
                          <option value="1800"<?php if (!(strcmp("1800", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1800</option>
                          <option value="1900"<?php if (!(strcmp("1900", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1900</option>
                          <option value="2000"<?php if (!(strcmp("2000", $_GET['form_emailalerts_price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;2000</option>
                          <option value="9999999999999"<?php if (!(strcmp("9999999999999", $_GET['form_emailalerts_price']))  || !isset($_GET['form_emailalerts_price'])) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Any Price</option>
                          <?php }?>
                        </select>
</span>
                      <label  class="label">Bedrooms:</label>
                      <select name="form_emailalerts_beds">
                        <option value="-1" <?php if ((strcmp("-1", $_GET['form_emailalerts_beds'])== 0)) {echo "selected=\"selected\"";} ?>>Any</option>
                        <option value="0" <?php if ((strcmp("0", $_GET['form_emailalerts_beds'])== 0)) {echo "selected=\"selected\"";} ?>>Studio</option>
                        <?php
for($i=1;$i<5;$i++){
?>
                        <option value="<?php echo $i?>"<?php if ((strcmp($i, $_GET['form_emailalerts_beds'])==0)) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                        <?php
}
?>
                      </select>

        	<input type="submit" value="Submit" class="button"/>
        	<input type="reset" value="Reset" class="button"/>

        	</fieldset>
        	</form>
          
        </div>

        <div>
        <?php if ($totalRows_queriesRS > 0) { // Show if not first page ?>
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
        <form id="form_emailalerts_select_ID" name="form_emailalerts_select" action="adminemailalerts.php" method="GET" >
        <input type="hidden" name="sendemailalerts" value="true"/>
        <?php if ($no_match > 0) { ?>
      	<div  class="label">
      		<img src="images/info.png" alt="Information" width="32" height="32" align="absmiddle"> No property matched for <?php echo $no_match; ?> registered users criteria. 
      	</div> 
      		
      	<?php } ?>
      	
      	<?php if ($totalRows_email_alerts_queriesRS > 0) { 
			
      		?>
      	<div  class="label">
      		<img src="images/info.png" alt="Information" width="32" height="32" align="absmiddle"> Sent <?php echo $totalRows_email_alerts_queriesRS; ?> alerts. 
      	</div> 
      	<?php } else { ?>
      		<div style="float: right;">
        		<input type="submit" value="Send Alert To Selected" class="buttonextralage"/>
        	</div>
	
      	<?php }?>
      		<div style="float: right;">
        		<a href="adminemailalerts.php"><input type="button" value="View All Registered" class="buttonextralage"/></a>
        	</div>

      		<div style="clear: both"></div>

  
  <table cellspacing="0" cellpadding="0" border="1px" bordercolor="#666666" >
          <tr>
            <th style="width:80px;" class="greentableheading"><span class="greentableheading">Date</span></th>
            <th style="width:40px;" class="greentableheading"><span class="greentableheading">Enabled</span></th>
            <th style="width:100px;" class="greentableheading"><span class="greentableheading">Looking for</span></th>
            <th style="width:140px; text-align:center;" class="greentableheading"><span class="greentableheading">Contact</span></th>
             <th style="width:50px; text-align:center;" class="greentableheading"><span class="greentableheading">Action</span></th>
          	<th style="width:40px;" class="greentableheading"><span class="greentableheading">Select All <input type="checkbox" name="selectall" onclick="toggle(this, 'selectedid[]')"></span></th>

</tr>
          <?php do { 
		  $style = "";
		  if($row_queriesRS['READ_STATUS'] == 'N'){
			  $style = 'style="color:#000;font-weight:bold"';
		  }
		  
		  ?>
  <tr  <?php if(($i%2) == 1){  ?>class="even"<?php } else {?> class="odd"<?php } ++$i;?> >
    <td <?php echo $style; ?>><?php echo fromDBDate($row_queriesRS['CREATION_DATE']); ?></td>
    <td <?php echo $style; ?>><?php echo $row_queriesRS['ENABLED_TEXT']; ?></td>

    <td <?php echo $style; ?>>
	    Property For :-  <?php echo $row_queriesRS['PROPERTY_FOR_DESCRIPTION']; ?><br /> 	
	    Property Type :- <?php echo $row_queriesRS['PROPERTY_TYPE_DESCRIPTION']; ?><br />
		Min Price :- <?php echo $row_queriesRS['MIN_PRICE']; ?><br />
	    Max Price :-  <?php echo $row_queriesRS['MAX_PRICE']; ?><br /> 	
	    Bedrooms :- <?php echo $row_queriesRS['BEDROOMS']; ?><br />
    	
    </td>
    <td <?php echo $style; ?>>
    Name :-  <?php echo $row_queriesRS['NAME']; ?><br /> 	
    Email :- <?php echo $row_queriesRS['EMAIL_ID']; ?><br />
	Phone :- <?php echo $row_queriesRS['CONTACT_NO']; ?>

    </td> 
    <td>
    	<?php if($row_queriesRS['READ_STATUS'] == "N"){?> <a href="adminemailalerts.php?action=read&ID=<?php echo $row_queriesRS['EMAIL_ALERT_ID']?>"><input type="button"  class="button" value="Mark Read"/></a><br /> <?php }?>
    	<?php if($row_queriesRS['ENABLED'] != "0"){?> 
    		<a href="adminemailalerts.php?action=disable&ID=<?php echo $row_queriesRS['EMAIL_ALERT_ID']?>">
    			<input type="button"  class="button" value="Disable Alert"/>
    		</a><br />
    		<a href="adminemailalerts.php?sendemailalerts=true&selectall=on&selectedid[]=<?php echo $row_queriesRS['EMAIL_ALERT_ID']?>">
    			<input type="button"  class="button" value="Send Alert"/>
    		</a><br />
    	<?php }?>
    	<?php if($row_queriesRS['ENABLED'] == "0"){?> <a href="adminemailalerts.php?action=enable&ID=<?php echo $row_queriesRS['EMAIL_ALERT_ID']?>"><input type="button"  class="button" value="Enable Alert"/></a><br /><?php }?>
    	

    	<a onclick="return confirm ('Are you sure you want to delete this record?')" href="adminemailalerts.php?action=delete&ID=<?php echo $row_queriesRS['EMAIL_ALERT_ID']?>"><input type="button"  class="button" value="Delete"/></a>

    </td>           
    <td <?php echo $style; ?>>
    	<?php if($row_queriesRS['ENABLED'] != "0"){?> 
	    	<input type="checkbox" name="selectedid[]" value="<?php echo $row_queriesRS['EMAIL_ALERT_ID']?>">
    	<?php }?>

    </td>

    </tr>
  <?php } while ($row_queriesRS = mysql_fetch_assoc($queriesRS)); ?>
</table>

        <?php if ($no_match > 0) { ?>
      	<div  class="label">
      		<img src="images/info.png" alt="Information" width="32" height="32" align="absmiddle"> No property matched for <?php echo $no_match; ?> registered users criteria. 
      	</div> 
      		
      	<?php } ?>
      	
      	<?php if ($totalRows_email_alerts_queriesRS > 0) { 
			
      		?>
      	<div  class="label">
      		<img src="images/info.png" alt="Information" width="32" height="32" align="absmiddle"> Sent <?php echo $totalRows_email_alerts_queriesRS; ?> alerts. 
      	</div> 
      	<?php } else { ?>
      		<div style="float: right;">
        		<input type="submit" value="Send Alert To Selected" class="buttonextralage"/>
        	</div>
	
      	<?php }?>
      		<div style="float: right;">
        		<a href="adminemailalerts.php"><input type="button" value="View All Registered" class="buttonextralage"/></a>
        	</div>

      		<div style="clear: both"></div>

</form>
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
<?php } else {?>
<?php include('admin-no-result-found.php')?>
	
<?php }?>
	
      </div> 
<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($queriesRS);
?>
