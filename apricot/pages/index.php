<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php');?>
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

$maxRows_propRS = 20;
$query_propRS = "SELECT * FROM property_details_website_view ORDER BY CREATION_DATE DESC LIMIT 0,".$maxRows_propRS;
$propRS = mysql_query($query_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);
$totalRows_propRS = mysql_num_rows($propRS);

mysql_select_db($database_adestate, $adestate);
$query_welcomeRS = "SELECT * FROM text WHERE `KEY` = 'welcome'";
$welcomeRS = mysql_query($query_welcomeRS, $adestate) or die(mysql_error());
$row_welcomeRS = mysql_fetch_assoc($welcomeRS);
$totalRows_welcomeRS = mysql_num_rows($welcomeRS);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<!-- include jQuery library -->
<script src="http://cdn.jquerytools.org/1.2.5/full/jquery.tools.min.js"></script>

<!-- include Cycle plugin -->
<script type="text/javascript" src="js/jquery.cycle.all.js"></script>


<script type="text/javascript">
$(document).ready(function() {
    $('.featuredpropid').cycle({
fx:      'fade', 
timeout: 5000
	});
});
</script>

<link href="bg_blocks.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-21571795-5']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>

<body>

<?php include('header.php'); ?>


<div id="maincontent">



<div>


<div class="block block_column block_column_left" style="margin-top:10px">
	<div style="text-align:justify">
<div class="heading">
Services We Provide<img src="images/services.png" height="20"  align="absmiddle" style="float:right" />
</div>

    <a href="contact-us.php?message=true"><h1 class="servicesbutton">To Buy/Sell/Rent property</h1></a>
    <a href="contact-us.php?message=true"><h1  class="servicesbutton">Mortgages</h1></a>
    <a href="contact-us.php?message=true"><h1  class="servicesbutton">Solicitor/Surveyor</h1></a>
    <a href="contact-us.php?message=true"><h1  class="servicesbutton">Gas/Electric/EPC Certificate</h1></a>
    <a href="contact-us.php?message=true"><h1  class="servicesbutton">Builder/Plumber</h1></a>

<div style="clear:both">&nbsp;</div>
</div>

</div>

        <div >
         <?php include('inc-search.php'); ?>
          </div>
          
<div class="block block_column block_column_right">
    <div class="heading">Featured Properties <img src="images/star-red.png" height="20"  align="absmiddle" style="float:right" /></div>
              <div class="box_title"></div>
              <div  class="featuredpropid" style="text-align:center;clear:both">
                <?php 
$query_ishot_propRS = "SELECT * FROM property_details_website_view where IS_HOT = 'Y' ORDER BY CREATION_DATE DESC LIMIT 0,10";
$propIsHotRS = mysql_query($query_ishot_propRS) or die(mysql_error());
$row_propIsHotRS = mysql_fetch_assoc($propIsHotRS);
$totalRows_propIsHotRS = mysql_num_rows($propIsHotRS);
                      do { ?>
                  <div style="text-align:center">
                  <div style="font-weight:bold">
                      <?php echo $row_propIsHotRS['FOR_SHORT_DESCRIPTION'];  ?>
                      <div class="offer_info">   <?php if ($row_propIsHotRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propIsHotRS['BEDROOMS']." Bed" ; ?> <?php echo $row_propIsHotRS['TYPE_SHORT_DESCRIPTION']; ?>
          , <?php echo $row_propIsHotRS['CITY']; ?>, 
          <span> &pound;<?php echo number_format( $row_propIsHotRS['PRICE']); ?> <?php if($row_propIsHotRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></span> 
          </div>
                  </div> 
                    <div style="height:150px" ><img style="width: 100%" src="<?php echo $row_propIsHotRS['FULL_PIC_PATH']; ?>"  /></div>
                            
          
      

          
                    <div><a href="property-details.php?PROPERTY_ID=<?php echo $row_propIsHotRS['PROPERTY_ID']; ?>" class="more"><span class="button more"> ...more</span></a></div>
                    <div style="width:240px;height:1px"></div>
                    </div>
                  <?php } while ($row_propIsHotRS = mysql_fetch_assoc($propIsHotRS)); ?>
              </div>

 
</div>



<div class="block" style="clear: both">
<div class="heading">Latest Properties Virtual Tour <img src="images/Virtual-Tour-Icon1.png" height="30"  align="absmiddle" style="float:right" /></div>
     <?php $_REQUEST['STRAT_AUTOPLAY'] = true;?>
    
<?php include('inc-map-view.php');?>

</div>



<div class="block" style="clear: both">
    <div style="text-align:justify">
<div class="heading">
<?php echo $row_welcomeRS['HEADING']; ?><img src="images/welcome.png" height="30"  align="absmiddle" style="float:right" />
</div>
<p>
<?php echo $row_welcomeRS['MESSAGE']; ?><br />
</p>
<div style="clear:both">&nbsp;</div>
</div>

</div>


</div>


</div>


<?php include('footer.php'); ?>

<script type="text/javascript">
<!--
swfobject.registerObject("FlashID");
//-->
</script>
</body>
</html>
<?php
mysql_free_result($propIsHotRS);
mysql_free_result($propRS);

mysql_free_result($welcomeRS);
?>
