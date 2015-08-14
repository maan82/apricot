
<a target="_blank">
              <img border="0" src="<?php echo $row_propRS['THUMB_PIC_PATH']; ?>"/>
</a><br/>
<span class="tablabel">For :- </span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION']; ?><br />
<span class="tablabel">Price :- </span><?php echo $row_propRS['PRICE']; ?><br />
<span class="tablabel">Features :- </span> <?php if ($row_propRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propRS['BEDROOMS']." Bed" ; ?>  <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?><br />
<span class="tablabel">Address :- </span><?php echo $row_propRS['PROP_ADDRESS']; ?><br />
