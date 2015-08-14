<div class="block">
	<div class="heading">Shortlisted Properties<img src="images/shortlist-ic.png" height="20" align="absmiddle" style="float:right" /></div>
          
          <div >
<!--start of fly to basket-->
<script type="text/javascript" src="js_fly_to_basket/ajax.js"></script>
<script src="js_fly_to_basket/fly-to-basket.js" type="text/javascript"></script>
<!--end of fly to basket-->

                    	<div class="box_title" ><span></span> </div>
                               <?php $basket = getBasket();?>
                                <div style="padding:0px 10px">
                                	<div id="compare_list" >
                                        
                    
                                    
                                    <div id="shopping_cart">
                                <table id="shopping_cart_items" cellpadding="0" cellspacing="0" width="100%">
                                    <!-- Here, you can output existing basket items from your database 
                                    One row for each item. The id of the TR tag should be shopping_cart_items_product + productId,
                                    example: shopping_cart_items_product1 for the id 1 -->
                                     <?php if ($basket != '') echo $basket;  ?>
                                    
                                </table>
                                
                                <div id="shopping_cart_totalprice">
                               
                                </div>
                            </div>
                     </div>
			                    </div>
                    	
          </div>
          </div>
