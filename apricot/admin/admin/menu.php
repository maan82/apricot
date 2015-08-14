<?php
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['MM_Roles'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['MM_Roles']);
	
  $logoutGoTo = "adminlogin.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
echo "<script>window.location.href = \"adminlogin.php\";</script>";
    exit;
  }
}

?>
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


$query_contactRS = "SELECT COUNT(*) as CONTACT_COUNT FROM query WHERE READ_STATUS = 'N' ";
$contactRS = mysql_query($query_contactRS) or die(mysql_error());
$row_contactRS = mysql_fetch_assoc($contactRS);
$totalRows_contactRS = mysql_num_rows($contactRS);


$query_viewingRS = "SELECT COUNT(*) as VIEWING_COUNT FROM property_view_request where READ_STATUS = 'N'";
$viewingRS = mysql_query($query_viewingRS) or die(mysql_error());
$row_viewingRS = mysql_fetch_assoc($viewingRS);
$totalRows_viewingRS = mysql_num_rows($viewingRS);


$query_valuationRS = "SELECT COUNT(*) as VALUATION_COUNT FROM property_valuation_request where READ_STATUS = 'N'";
$valuationRS = mysql_query($query_valuationRS) or die(mysql_error());
$row_valuationRS = mysql_fetch_assoc($valuationRS);
$totalRows_valuationRS = mysql_num_rows($valuationRS);


$query_mortageRS = "SELECT COUNT(*) as MORTGAGE_COUNT FROM mortgage_advice where READ_STATUS  = 'N' ";
$mortageRS = mysql_query($query_mortageRS) or die(mysql_error());
$row_mortageRS = mysql_fetch_assoc($mortageRS);
$totalRows_mortageRS = mysql_num_rows($mortageRS);


$query_mortcalcsRS = "SELECT COUNT(*) as MC_COUNT FROM mortgage_calculations WHERE READ_STATUS = 'N'";
$mortcalcsRS = mysql_query($query_mortcalcsRS) or die(mysql_error());
$row_mortcalcsRS = mysql_fetch_assoc($mortcalcsRS);
$totalRows_mortcalcsRS = mysql_num_rows($mortcalcsRS);

$query_emailRS = "SELECT COUNT(*) as EMAIL_COUNT FROM email_alerts WHERE READ_STATUS = 'N'";
$emailRS = mysql_query($query_emailRS) or die(mysql_error());
$row_emailRS = mysql_fetch_assoc($emailRS);
$totalRows_emailRS = mysql_num_rows($emailRS);

$TOTAL_COUNT =  0;
if(strpos($_SESSION['MM_Roles'],"View Contact Us Queries") != false){
$TOTAL_COUNT += intval($row_contactRS['CONTACT_COUNT']) ;
$TOTAL_COUNT += intval($row_emailRS['EMAIL_COUNT']) ;
}
if(strpos($_SESSION['MM_Roles'],"View Viewing Requests") != false){
$TOTAL_COUNT +=  intval($row_viewingRS['VIEWING_COUNT']) ;
}
if(strpos($_SESSION['MM_Roles'],"View Valuation Requests") != false){
$TOTAL_COUNT +=  intval($row_valuationRS['VALUATION_COUNT']) ;
}
if(strpos($_SESSION['MM_Roles'],"View Mortgage Queries") != false){
$TOTAL_COUNT +=  intval($row_mortageRS['MORTGAGE_COUNT'] + $row_mortcalcsRS['MC_COUNT']);
}

?>
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />

<ul id="MenuBar1" class="MenuBarHorizontal label">
<?php   if(strpos($_SESSION['MM_Roles'],"Add or Edit Property") != false ){?>

<li><a href="#" title="Rent Management & View Rented Propertie" class="MenuBarItemSubmenu">Poperties</a>
  <ul>
    <li><a href="adminpropertysearch.php">Search</a></li>
    <li><a href="adminaddproperty.php">Add New Property</a></li>
  </ul>
</li>
<?php }?>
<?php   if(strpos($_SESSION['MM_Roles'],"Welcome Message Edit") != false){?>

  <li style="width:180px"><a href="adminwelcomeedit.php">Edit Welcome Message</a>
    
  </li>
  <?php }?>
<?php   if(strpos($_SESSION['MM_Roles'],"View Contact Us Queries") != false 
							|| strpos($_SESSION['MM_Roles'],"View Viewing Requests") != false
							|| strpos($_SESSION['MM_Roles'],"View Valuation Requests") != false
							|| strpos($_SESSION['MM_Roles'],"View Mortgage Queries") != false){?>
<li style="width:160px"><a href="#" class="MenuBarItemSubmenu" onclick="return false;">Queries/Requests
    <?php if($TOTAL_COUNT> 0 ) echo "<span style='color:red'>".$TOTAL_COUNT."</span>"?>
    </a>
    <ul style="width:160px">
      <?php   if(strpos($_SESSION['MM_Roles'],"View Contact Us Queries") != false ){?>
      <li style="width:160px"><a href="adminqueries.php">Contact Us Queries
        <?php if($row_contactRS['CONTACT_COUNT']> 0 ) echo "<span style='color:red'>".$row_contactRS['CONTACT_COUNT']."</span>"?>
      </a></li>
      <?php }?>
      <?php   if(strpos($_SESSION['MM_Roles'],"View Viewing Requests") != false ){?>
      <li style="width:160px"><a href="adminviewingrequests.php">Viewing Requests
        <?php if($row_viewingRS['VIEWING_COUNT']> 0 ) echo "<span style='color:red'>".$row_viewingRS['VIEWING_COUNT']."</span>"?>
      </a></li>
      <?php }?>
      <?php   if(strpos($_SESSION['MM_Roles'],"View Valuation Requests") != false ){?>
      <li style="width:160px"><a href="adminvaluationrequests.php">Valuation Requests
        <?php if($row_valuationRS['VALUATION_COUNT']> 0 ) echo "<span style='color:red'>".$row_valuationRS['VALUATION_COUNT']."</span>"?>
      </a></li>
      <?php }?>
      <?php   if(strpos($_SESSION['MM_Roles'],"View Mortgage Queries") != false ){?>
      <li style="width:160px"><a href="adminmortgagequeries.php">Mortgage Queries <?php if($row_mortageRS['MORTGAGE_COUNT']> 0 ) echo "<span style='color:red'>".$row_mortageRS['MORTGAGE_COUNT']."</span>"?></a></li>
      <li style="width:160px"><a href="adminmortgagecalculations.php">Mortgage Calculations <?php if($row_mortcalcsRS['MC_COUNT']> 0 ) echo "<span style='color:red'>".$row_mortcalcsRS['MC_COUNT']."</span>"?></a></li>

      <?php }?>
      <?php   if(strpos($_SESSION['MM_Roles'],"View Contact Us Queries") != false){?>
      <li style="width:160px"><a href="adminemailalerts.php">Email Alerts Register <?php if($row_emailRS['EMAIL_COUNT']> 0 ) echo "<span style='color:red'>".$row_emailRS['EMAIL_COUNT']."</span>"?></a></li>

      <?php }?>
    </ul>
  </li>
  <?php   }?>
<?php   if(strpos($_SESSION['MM_Roles'],"User Management") != false ){?>
<li style="width:140px"><a href="adminadduser.php">User Management </a></li>
<?php   }?>
<?php   if(!empty($_SESSION['MM_Username'])){?>
<li style="width:120px"><a href="<?php echo $logoutAction ?>">Logout <?php echo $_SESSION['MM_Username'];  ?></a></li>
<?php   }?>
</ul>
<div style="clear:both"></div>

<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
<?php
mysql_free_result($contactRS);

mysql_free_result($viewingRS);

mysql_free_result($valuationRS);

mysql_free_result($mortageRS);

mysql_free_result($mortcalcsRS);
?>
