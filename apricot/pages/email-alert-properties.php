<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php require_once('constants.php'); ?>

<?php

$colname_propRS_for = "-1";
if (isset($_GET['lookingfor']) && !empty($_GET['lookingfor'])) {
  $colname_propRS_for = $_GET['lookingfor'];
}


$colname_propRS_price = "-1";
if (isset($_GET['price'])  && !empty($_GET['price'])) {
  $colname_propRS_price = $_GET['price'];
}



$query_propRS = sprintf("SELECT * FROM property_details_website_view WHERE PROPERTY_FOR_ID = %s and PRICE <= %s AND STATUS_ID = 1 ", GetSQLValueString($colname_propRS_for, "int"), GetSQLValueString($colname_propRS_price, "int"), GetSQLValueString($colname_propRS_bedrooms, "int"));

$colname_propRS_bedrooms = "-1";
if (isset($_GET['bedrooms'])  && !empty($_GET['bedrooms'])) {
  $colname_propRS_bedrooms = $_GET['bedrooms'];
	if ($colname_propRS_bedrooms == 'Any') {
		
	} else {
		$query_propRS = sprintf("%s and BEDROOMS >= %s ",$query_propRS,  GetSQLValueString($colname_propRS_bedrooms, "int"));
	}
}

$colname_propRS_type = "-1";
if (isset($_GET['type'])  && !empty($_GET['type'])) {
  $colname_propRS_type = $_GET['type'];
	if ($colname_propRS_type == '0') {
		
	} else {
		$query_propRS = sprintf("%s and PROPERTY_TYPE_ID = %s ",$query_propRS,  GetSQLValueString($colname_propRS_type, "int"));
	}
}

$query_propRS = sprintf(" %s order by PROPERTY_ID desc", $query_propRS);

//echo $query_propRS;
$propRS = mysql_query($query_propRS) or die(mysql_error());
$row_propRS = mysql_fetch_assoc($propRS);
$totalRows_queriesRS = mysql_num_rows($propRS);

  $pathToServerFile	= 'tmp/emailalert_'.$_GET['lookingfor'].'.html';
 // $contents = file_get_contents('http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"))."/adminsendemailalerts.php?lookingfor=1);

if ($totalRows_queriesRS > 0) { 
?>
<html>
	<head></head>
	<body>
		<div>Hi <?php echo $_GET['name']?>,</div>
		
		<div style="padding: 10px">Please see below properties matching your criteria.</div>
			
		<div>
						<img src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'] ?>/aeon/pages/images/new-images/aeon-logo-yellow-on-black.jpg" width="470" align="top" />
</div>
<div>
		<?php echo $ADDRESS_LINE1;?>,<br /> <?php echo $ADDRESS_LINE2;?>, <br />Telephone: <?php echo $PHONE;?>
<div >
Email: <a href="mailto:<?php echo $EMAIL_HOUNSLOW;?>">  <?php echo $EMAIL_HOUNSLOW;?></a>
<br />
For sales: <a href="mailto:<?php echo $EMAIL_SALES;?>">  <?php echo $EMAIL_SALES;?></a>
<br />
For sales: <a href="mailto:<?php echo $EMAIL_LETTINGS;?>">  <?php echo $EMAIL_LETTINGS;?></a>
</div>

</div>
				      <div id="list" style="margin-top: 10px">
                <?php do { 
                $PROP_LINK = 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"))."/property-details.php?PROPERTY_ID=".$row_propRS['PROPERTY_ID'];
                	
                	?>
                <div class="block">
                <div class="list_view_wrapper">
                <div class="top_block">
	    <div style="background-color: #000">
                    <div class="offer_info"><a style="color: #f5ed30;" href="<?php echo $PROP_LINK ?>property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>">
                <span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?></span>     <?php if ($row_propRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propRS['BEDROOMS']." Bed" ; ?> <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?>
                <?php echo $row_propRS['CITY']; ?>, <?php echo $row_propRS['POSTCODE']; ?><br />
          <span> &pound;<?php echo number_format( $row_propRS['PRICE']); ?>   <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></span></a> </div>
                                 
      </div> 
      <div class="prop_actions">
        <div >



<div class="button" style="float: left; text-align: center;margin-right: 4px">
		<a style="font-weight: bold;font-size: 14px;font: Tahoma"  href="<?php include('inc-email-details.php'); ?>" target="_top">
	
	<div style="font-family: Arial; font-size: 14px;padding-top: 6px">Email Details To Friend</div></a>
 
                

	</div>

                </div>
      </div>
                  <div class="clear"></div>
                </div>
                
                <div class="prop_img">
                	
                <a style="text-decoration:none" href="<?php echo $PROP_LINK; ?>"><img  id="slidingProduct<?php echo $row_propRS['PROPERTY_ID']; ?>" class="sliding_product"  src="<?php echo getFullPicUrl($row_propRS['SLIDE_PIC_PATH']); ?>"  alt="" title="" border="0"/></a>
                                  <div class="actions_list" style="padding:0px;text-align:left">
    
  </div>

                </div>

                <div class="prop_brief"><?php echo $row_propRS['BRIEF_DESCRIPTION'] ?></br></div>
              
            <div class="clear"></div>
               </div>

</div>
                <?php } while ($row_propRS = mysql_fetch_assoc($propRS)); ?>
          
          </div>
          
	      	<div style="border: 1px solid;padding: 5px;">
	      		<?php $disable_link = 'http://'.$_SERVER['SERVER_NAME'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"))."/disable-email-alert.php?id=".$_GET['alertid']."&token=".sha1($_GET['alertid'] + $SHA_SEED) ; ?>
	      		To disable this alert please click <a href="<?php echo $disable_link;  ?>"><?php echo $disable_link; ?></a> or copy and paste this url in your browser.
	      	</div>

	</body>
</html>
<?php } ?>