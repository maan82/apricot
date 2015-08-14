<?php require_once('constants.php');?>
<div class="block">



<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?php echo $MAP_API_KEY?>" type="text/javascript"></script>
<script src="http://gmaps-utility-library.googlecode.com/svn/trunk/markermanager/release/src/markermanager.js" type="text/javascript">
</script>
<script src="gmap.js" type="text/javascript"></script>
<script type="text/javascript">
window.onload = initialize;
  // Call this function when the page has been loaded
function initialize() {
	mapLoadAndMark(props,"statusSpan_ID");
	<?php if ($_REQUEST['STRAT_AUTOPLAY'] == true) {?>
	visitAll(props);
	<?php }?>
}
function toggle(){
	visitAllPlayPause("mapplaypauseId",props);
}



var presentPropCounter = 0;
var props = new Array();
          <?php $counter = 0; if ($totalRows_propRS > 0) { // Show if recordset not empty ?>
            <?php do { ?>
props[<?php echo $counter++ ?>] = {
	id : "<?php echo $row_propRS['PROPERTY_ID']; ?>",
	title : "<?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?>, <?php echo $row_propRS['CITY']; ?>, <?php echo number_format( $row_propRS['PRICE']) ; ?> <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?>",
	price_from : "<?php echo $row_propRS['PRICE']; ?>",
	photo : '/real/test.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>',
	photo_h : 146,
	lat : <?php echo $row_propRS['LATITUDE']; ?>,
	lon : <?php echo $row_propRS['LONGITUDE']; ?>,
	desc : '<div id="markerdivid663343"><div class="offer_box"><div style="width:96px;padding:4px;float:left;"><a href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>"><img id="slidingProduct<?php echo $row_propRS['PROPERTY_ID']; ?>" class="sliding_product"  src="<?php echo $row_propRS['THUMB_PIC_PATH']; ?>" alt=""  border="0"  title=""/></a><div class="actions_list" style="padding:0px;text-align:left"><ul><li style="padding:0px"><a href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&PAGE=PICS&<?php echo $queryString_propRS;?>" ><span class="button"><?php echo $row_propRS['PICS_COUNT']; ?> Pictures</span></a></li></ul></div></div><div class="details_list_small" style="color: black;"><span style="font-weight:bold"><?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?></span> <ul><li><span>Price:</span>&pound;<?php echo number_format( $row_propRS['PRICE']); ?>  <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></li><li><span>City:</span><?php echo $row_propRS['CITY']; ?>  </li><li><span>Bedrooms:</span><?php echo $row_propRS['BEDROOMS']; ?>  </li></ul><div class="more" style="clear:both" ><span class="button"><a href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>">...more</a></span></div></div><div style="clear:both;"></div></div><?php if ($_REQUEST['STRAT_AUTOPLAY'] != true) {?><input name="Next Property" type="button" onclick="visitNextProperty(props);" style="background-image:url(images/view_next.png);width:104px;height:29px;background-color:#FFF;border:none;cursor:pointer" value="" class="button"/><?php }?><input type="button" class="button" style="background-image:url(images/contact-us-bt.png);width:104px;height:29px;background-color:#FFF;border:none;cursor:pointer" value="" onclick="window.location.href=\'property-details.php?PAGE=AV&PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>\'"/><input style="background-image:url(images/shortlist.png);width:104px;height:29px;background-color:#FFF;border:none;cursor:pointer" type="button" class="button" value="" onclick="addToBasket(<?php echo $row_propRS['PROPERTY_ID']; ?>,\'compare_list\')"/></div>'
}
  <?php } while ($row_propRS = mysql_fetch_assoc($propRS)); ?>
            <?php } // Show if recordset not empty ?>


</script>


     <div class="label" style="padding:10px 0px">
       Total <?php echo $totalRows_propRS ?> Properties Found.<br />
       Page number <?php echo $pageNum_propRS+1  ?>.Property number  <?php echo ($startRow_propRS + 1) ?> to <?php echo min($startRow_propRS + $maxRows_propRS, $totalRows_propRS) ?> ploted on map. <br />
 Showing <span id="statusSpan_ID"></span>  &nbsp;properties in map view. 
     	<?php if ($_REQUEST['STRAT_AUTOPLAY'] == true) {?>
        
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>

  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <style type="text/css">
    #slider { margin: 10px; }
  </style>
  <script>
  $(function() {
    $( "#slider" ).slider();
  });
   </script>
<div>
        <div style="width:180px;float:right">
        
        <div style="float:left">Fast</div><div style="float:right"> Slow</div>
        <div style="text-align:center">Tour Speed</div>
        <div id="slider" style="clear:both"></div>
        
        </div>
        <div class="button" style="float: right;margin-right: 10px"  onclick="toggle()" >
  <img src="images/pause.png" alt="Pause" width="28"  align="absmiddle" id="mapplaypauseId"   x/>  <span id="mapplaypauseIdtxt" style="font-weight:bold">Pause</span>
  </div>
  </div>
	<?php }?>
</div>
     <div style="clear:both"></div>
			    <div id="map" style="width: 100%; height: 500px"></div>



     	<?php if ($_REQUEST['STRAT_AUTOPLAY'] != true) {?>

<div style="margin-top:10px">
	<!-- include the Tools -->


	 

	<!-- standalone page styling (can be removed) -->
	<link rel="stylesheet" type="text/css" href="galleryfiles/standalone.css"/>	


	<link rel="stylesheet" type="text/css" href="galleryfiles/scrollable-horizontal.css" />
	<link rel="stylesheet" type="text/css" href="galleryfiles/scrollable-buttons.css" />

	<style>
	

	/* styling for the image wrapper  */
	#image_wrap {
		/* dimensions */
		width:650px;
		margin-bottom:20px;
		padding:7px 0;

		/* centered */
		text-align:center;

		/* some "skinning" */
		background-color:#efefef;
		border:2px solid #fff;
		outline:1px solid #ddd;
		-moz-ouline-radius:4px;
	}
	</style>
<!-- "previous page" action -->
<a class="prev browse left"></a>

<!-- root element for scrollable -->
<div class="scrollable" style="height:250px" id="scroller">   
    
   
   <!-- root element for the items -->
   <div class="items">
   
<?php

mysql_data_seek ( $propRS , 0);
				$row_propRS = mysql_fetch_assoc($propRS);
$i=1;$j =0 ;  do { ?>

<?php if($i==1)  echo "<div>"; ?>
      
<div style="width:125px;float:left;margin:0px">
       
         <div> <img id="scrollerImageId<?php echo $row_propRS['PROPERTY_ID']; ?>" style="cursor:pointer"  src="<?php echo $row_propRS['THUMB_PIC_PATH']; ?>" onclick="gotoProperty(this,<?php echo $row_propRS['LATITUDE'];  ?>,<?php echo $row_propRS['LONGITUDE'];  ?>,<?php echo $j ?>,props[<?php echo $j ?>].desc)" /></div>
          <div style="padding-left:20px">
        
        <div style="width:100px" >
                <span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?></span><br />     <?php if ($row_propRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propRS['BEDROOMS']." Bed" ; ?> <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?>
                <?php echo $row_propRS['CITY']; ?><br />
          <span> &pound;<?php echo number_format( $row_propRS['PRICE']); ?>   <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></span> <br />
          <a class="button" href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>">..More</a>
          </div>
        </div>
      </div>   
<?php if($i==4)  {echo "</div>"; $i = 1;} else $i++;  $j++;?>
  
  <?php } while ($row_propRS = mysql_fetch_assoc($propRS)); if($i!=1)  {echo "</div>"; $i = 1;}?>


      
   </div>
   
</div>

<!-- "next page" action -->
<a class="next browse right"></a>
<div style="margin: 5px 0;text-align: center;clear: both">
<button class="button" style="clear: both" onclick="toggleScrollerPlay('scrollerplaypauseId')" >
  <img src="images/pause.png" alt="Pause" width="28"  align="absmiddle" id="scrollerplaypauseId"   />  <span id="scrollerplaypauseIdtxt" style="font-weight:bold">Pause</span>
  </button>
</div>  
  
 

<script>
var scrollPlayStatus = "";
function toggleScrollerPlay(imgId){
	if(scrollPlayStatus == "P"){
		document.getElementById(imgId).src = "images/pause.png";
		document.getElementById(imgId+"txt").innerHTML = "Pause"
		scrollPlayStatus = "";
		api.play();
	}	else	{
		document.getElementById(imgId).src = "images/play.png";
		document.getElementById(imgId+"txt").innerHTML = "Play"
		scrollPlayStatus = "P";
		api.pause();
	}
	
}

$(function() {

//var root = $(".scrollable").scrollable({circular: true}).autoscroll({ autoplay: true,interval: 8000 });
//window.api = root.data("scrollable");

// initialize scrollable together with the autoscroll plugin
var root = $("#scroller").scrollable({circular: true}).autoscroll({ autoplay: true,interval: 8000 });
 
// provide scrollable API for the action buttons
window.api = root.data("scrollable");


 
});
$(".items img").filter(":first").addClass("active");
</script>



</div>
     	<?php }?>

</div>