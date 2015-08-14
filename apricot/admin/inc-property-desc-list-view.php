                <div class="offer_info"><a style="color: #000" href="property-details.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID']; ?>">
                <span><?php echo $row_propRS['FOR_SHORT_DESCRIPTION'];  ?></span>     <?php if ($row_propRS['PARENT_TYPE_ID'] == 'Residential')echo $row_propRS['BEDROOMS']." Bed" ; ?> <?php echo $row_propRS['TYPE_SHORT_DESCRIPTION']; ?>
                <?php echo $row_propRS['CITY']; ?>, <?php echo $row_propRS['POSTCODE']; ?><br />
          <span> &pound;<?php echo number_format( $row_propRS['PRICE']); ?>   <?php if($row_propRS['PROPERTY_FOR_ID'] == 2) echo "PCM" ;?></span></a> </div>
