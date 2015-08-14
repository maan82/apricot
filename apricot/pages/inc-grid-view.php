		      <div id="grid">
        <?php $i=1;  do { ?>
			<?php if($i == 2) {?>
             <div class="property_block_grid" style="margin:0px 7px">

          <?php }else {?>
            <div class="property_block_grid" >                     
          <?php }?>

                <div class="offer_box_small" >
                	<div>
                	  <input type="button" class="button"  value="View Details" onclick="window.location.href='property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>'"/>
<div class="button" style="float: left; text-align: center;margin-right: 4px">
		<a style="color:white;font-weight: bold;font-size: 14px;font: Tahoma"  href="<?php include('inc-email-details.php'); ?>" target="_top">
	
	<div style="font-family: Arial; font-size: 14px;padding-top: 6px">Email Details</div></a>
 
                

	</div>

                	</div>
<a href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>">
        <div class="offer_box_small_img" > <img id="slidingProduct<?php echo $row_propRS['PROPERTY_ID']; ?>" src="<?php echo $row_propRS['SLIDE_PIC_PATH']; ?>"  alt="" title="" border="0"/></div>
        <div class="offer_info"><span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?></span> <br />   <?php if ($row_propRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propRS['BEDROOMS']." Bed" ; ?> <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?>
          <br /><?php echo $row_propRS['CITY']; ?><br />
          <span> &pound;<?php echo number_format( $row_propRS['PRICE']); ?> <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></span> </div>
          </a> 
         
          <div>
          <input type="button" class="button" value="Shortlist" onclick="addToBasket(<?php echo $row_propRS['PROPERTY_ID']; ?>,'compare_list')"/>
          
          <input type="button" class="button" value="Contact Us"  onclick="window.location.href='property-details.php?PAGE=AV&PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>&<?php echo $queryString_propRS;?>'"/>
          
          </div>
      </div>

          </div>
                <?php if(($i++ % 3) == 0) {echo "<div class=\"clear\"></div>";$i=1;}?>

          <?php } while ($row_propRS = mysql_fetch_assoc($propRS)); ?>
          
          </div>