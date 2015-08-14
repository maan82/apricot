<?php 
require_once('Connections/adestate.php');
$query_Prop_Type_RS = "SELECT * FROM property_type_master";
$Prop_Type_RS = mysql_query($query_Prop_Type_RS) or die(mysql_error());
$row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
$totalRows_Prop_Type_RS = mysql_num_rows($Prop_Type_RS);

?>

 
<div class="block  block_column block_column_center">
 <div class="heading">
 	 Property Search<img src="images/home-search-icon.png" height="20" style="float:right"/>
 </div>           
<form action="searchresults.php" method="get" name="form_quicksearch" enctype="application/x-www-form-urlencoded">
  
  <table align="center" class="form label" cellpadding="0" cellspacing="0" style="margin:0px;width:100%">
                    <tr valign="baseline">
                      <td   class="label" style="font-family:Arial, Helvetica, sans-serif">
                        <label class="label">
                          <input type="radio" name="lookingfor" value="1" onclick="popPrice(this,'priceDiv')" id="lookingfor_buy" <?php if(strcmp($_GET['lookingfor'],"2")!= 0) echo "checked=\"checked\"";?> />
                          For Sale</label>
                          <br />
                        <label class="label">
                          <input type="radio" name="lookingfor" value="2" onclick="popPrice(this,'priceDiv')" id="lookingfor_rent" <?php if(strcmp($_GET['lookingfor'],"2")== 0) echo "checked=\"checked\"";?> />
                          To Rent</label>
                      </td>
                    </tr>
                    <tr valign="baseline">
                      <td   class="label" >Property Type :<br />
                      <div id="propertytypeDiv">
                        <select name="propertytype" id="propertytype_id">
                          <?php $proptypejoin="";
do {  
?>
                          <option value="<?php echo $row_Prop_Type_RS['PROPERTY_TYPE_ID']?>" <?php if (!(strcmp($row_Prop_Type_RS['PROPERTY_TYPE_ID'], $_GET['propertytype']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Prop_Type_RS['SHORT_DESCRIPTION']?></option>
                          <?php
  		$proptypejoin.= $row_Prop_Type_RS['PROPERTY_TYPE_ID'].","	;

} while ($row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS));
  $rows = mysql_num_rows($Prop_Type_RS);
  if($rows > 0) {
      mysql_data_seek($Prop_Type_RS, 0);
	  $row_Prop_Type_RS = mysql_fetch_assoc($Prop_Type_RS);
  }
  	$ln = strlen($proptypejoin);
	$proptypejoin = substr_replace($proptypejoin,"",($ln-1));

?>
                          <option value="<?php echo "-1" ?>" <?php if (!(strcmp("-1", $_GET['propertytype']))) {echo "selected=\"selected\"";} ?>>Any</option>
                        </select>
                      </div></td>
                    </tr>
                    <tr valign="baseline">
                      <td  class="label">Price :<br />
                      <div id="priceDiv">
                        <select name="price" id="price_id">
                          <?php if($_GET['lookingfor']  != '2'){?>
                          <option value="50000"<?php if (!(strcmp("50000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;50000</option>
                          <option value="100000"<?php if (!(strcmp("100000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;100000</option>
                          <option value="150000"<?php if (!(strcmp("150000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;150000</option>
                          <option value="200000"<?php if (!(strcmp("200000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;200000</option>
                          <option value="250000"<?php if (!(strcmp("250000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;250000</option>
                          <option value="300000"<?php if (!(strcmp("300000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;300000</option>
                          <option value="350000"<?php if (!(strcmp("350000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;350000</option>
                          <option value="400000"<?php if (!(strcmp("400000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;400000</option>
                          <option value="450000"<?php if (!(strcmp("450000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;450000</option>
                          <option value="500000"<?php if (!(strcmp("500000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;500000</option>
                          <option value="550000"<?php if (!(strcmp("550000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;550000</option>
                          <option value="600000"<?php if (!(strcmp("600000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;600000</option>
                          <option value="650000"<?php if (!(strcmp("650000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;650000</option>
                          <option value="700000"<?php if (!(strcmp("700000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;700000</option>
                          <option value="750000"<?php if (!(strcmp("750000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;750000</option>
                          <option value="800000"<?php if (!(strcmp("800000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;800000</option>
                          <option value="850000"<?php if (!(strcmp("850000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;850000</option>
                          <option value="900000"<?php if (!(strcmp("900000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;900000</option>
                          <option value="950000"<?php if (!(strcmp("950000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;950000</option>
                          <option value="100000000"<?php if (!(strcmp("100000000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Any Price</option>
                          <?php }else if($_GET['lookingfor']  == '2'){?>
                          <option value="300"<?php if (!(strcmp("300", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;300</option>
                          <option value="400"<?php if (!(strcmp("400", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;400</option>
                          <option value="500"<?php if (!(strcmp("500", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;500</option>
                          <option value="600"<?php if (!(strcmp("600", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;600</option>
                          <option value="700"<?php if (!(strcmp("700", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;700</option>
                          <option value="800"<?php if (!(strcmp("800", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;800</option>
                          <option value="900"<?php if (!(strcmp("900", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;900</option>
                          <option value="1000"<?php if (!(strcmp("1000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1000</option>
                          <option value="1100"<?php if (!(strcmp("1100", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1100</option>
                          <option value="1200"<?php if (!(strcmp("1200", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1200</option>
                          <option value="1300"<?php if (!(strcmp("1300", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1300</option>
                          <option value="1400"<?php if (!(strcmp("1400", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1400</option>
                          <option value="1500"<?php if (!(strcmp("1500", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1500</option>
                          <option value="1600"<?php if (!(strcmp("1600", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1600</option>
                          <option value="1700"<?php if (!(strcmp("1700", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1700</option>
                          <option value="1800"<?php if (!(strcmp("1800", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1800</option>
                          <option value="1900"<?php if (!(strcmp("1900", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;1900</option>
                          <option value="2000"<?php if (!(strcmp("2000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Upto &pound;2000</option>
                          <option value="100000000"<?php if (!(strcmp("100000000", $_GET['price']))) {echo "selected=\"selected\"";} ?>><?php echo $row_Price_RS['PRICE']?>Any Price</option>
                          <?php }?>
                        </select>
                      </div></td>
                    </tr>
                    <tr valign="baseline">
                      <td  class="label">Bedrooms:<br />
                      <select name="beds">
                        <option value="-1" <?php if ((strcmp("-1", $_GET['beds'])== 0)) {echo "selected=\"selected\"";} ?>>Any</option>

                        <?php
for($i=1;$i<5;$i++){
?>
                        <option value="<?php echo $i?>"<?php if ((strcmp($i, $_GET['beds'])==0)) {echo "selected=\"selected\"";} ?>><?php echo $i?></option>
                        <?php
}
?>
                      </select></td>
                    </tr>
                    <tr valign="baseline">
                      <td   class="label">Location  :<br />
                      <input type="text" name="location"  /></td>
                    </tr>
                    <tr valign="baseline">
                      <td  align="right" style="padding-top:20px">
                      <input id="viewasID" name="viewas" type="hidden" value="<?php echo($_GET['viewas'])?>" />
                      <input class="button"  type="submit"  value="List Find">
                      <input class="button"  type="submit"  value="Map Find"  onclick="document.getElementById('viewasID').value='M'">
                      </td>
                    </tr>
                  </table>

              </form>
          
 </div>         