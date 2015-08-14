		      <div id="list">
                <?php do { ?>
                <div class="block">
                <div class="list_view_wrapper">
                <div class="top_block">
                	                <div class="prop_desc">
                                     
                <?php include('inc-property-desc-list-view.php'); ?>
      </div>
                <?php if($row_propRS['PROPERTY_FOR_ID'] <= 2) { ?>
      
      <div class="prop_actions">
        <div >

       
                <input type="button" class="button"  value="View Details" onclick="window.location.href='property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>'"/>
                <input type="button" class="button" value="Contact Us"  onclick="window.location.href='property-details.php?PAGE=AV&PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>'"/>

<div class="button" style="float: left; text-align: center;margin-right: 4px">
		<a style="color:white;font-weight: bold;font-size: 14px;font: Tahoma"  href="<?php include('inc-email-details.php'); ?>" target="_top">
	
	<div style="font-family: Arial; font-size: 14px;padding-top: 6px">Email Details</div></a>
 
                

	</div>

                </div>
      </div>
                      <?php }; ?>

                  <div class="clear"></div>
                </div>
                
                <div class="prop_img">
                <a style="text-decoration:none" href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>"><img  id="slidingProduct<?php echo $row_propRS['PROPERTY_ID']; ?>" class="sliding_product"  src="<?php echo $row_propRS['SLIDE_PIC_PATH']; ?>"  alt="" title="" border="0"/></a>
                                  <div class="actions_list" style="padding:0px;text-align:left">
    <a class="button" style="padding: 3px" href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&PAGE=PICS&<?php echo $queryString_propRS;?>" ><span style="font-weight:bold"><?php echo $row_propRS['PICS_COUNT']; ?> </span><span>Pictures</span></a>
  </div>

                </div>

                <div class="prop_brief"><?php echo $row_propRS['BRIEF_DESCRIPTION'] ?></div>
              
            <div class="clear"></div>
                </div>

</div>
                <?php } while ($row_propRS = mysql_fetch_assoc($propRS)); ?>
          
          </div>