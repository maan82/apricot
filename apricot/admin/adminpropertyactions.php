<script>
function gotoUrl(url){
		window.location.href = url;
}

</script>

	             <input type="button" class="buttonsmall" value="Admin Home" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />

<input type="button" style="width:90px" class="buttonsmall" value="Edit Details" onclick="gotoUrl('../admin/admineditproperty.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/> <br />
<input type="button" class="buttonsmall" style="width:90px" value="Edit Map" onclick="gotoUrl('../admin/adminaddpropertymap.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&POSTCODE=<?php echo $row_propRS['POSTCODE']; ?>')"/><br />
<input style="width:90px" type="button" class="buttonsmall" value="Edit Images" onclick="gotoUrl('../admin/adminaddpropertyimages.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />


<?php if($row_propRS['STATUS_ID'] == 1){?>
    <?php if($row_propRS['PROPERTY_FOR_ID'] == 1){?>
<input type="button" class="buttonsmall" value="Mark As Sold" style="width:90px" onclick="gotoUrl('../admin/adminaddpropertysalewebinfo.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/>            <br />
    <?php } else if($row_propRS['PROPERTY_FOR_ID'] == 2){?>
<input type="button" class="buttonsmall" value="Put On Rent" style="width:90px" onclick="gotoUrl('../admin/adminaddpropertyrentwebinfo.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/>            <br />
    <?php }?>
    <input type="button" class="buttonsmall" value="Web Remove" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&PROPWEBREMOVE=PROPWEBREMOVE')"/><br />
<input type="button" class="buttonsmall" value="Delete" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&PROPDELETE=PROPDELETE')"/><br />
<?php }else  if($row_propRS['STATUS_ID'] == 2){?>
<input type="button" class="buttonsmall" value="Put On Web" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&PROPPUTONWEB=PROPPUTONWEB')"/><br />
<input type="button" class="buttonsmall" value="Delete Fully" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&PROPDELETE=PROPDELETEPERMANENT')"/><br />
<?php }else  if($row_propRS['STATUS_ID'] == 3){?>
<input type="button" class="buttonsmall" value="Undo Sale" style="width:90px" onclick="gotoUrl('../admin/adminundosale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
     <input type="button" class="buttonsmall" value="Sale Completed" style="width:100px" onclick="gotoUrl('../admin/admincompletesale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
<?php }else  if($row_propRS['STATUS_ID'] == 4){?>
<input type="button" class="buttonsmall" value="ReSale" style="width:90px" onclick="gotoUrl('../admin/adminresale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
<input type="button" class="buttonsmall" value="For Letting" style="width:90px" onclick="gotoUrl('../admin/adminputsoldforletting.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
<?php }else  if($row_propRS['STATUS_ID'] == 5){?>
<input type="button" class="buttonsmall" value="Vacate" style="width:90px" onclick="gotoUrl('../admin/adminvacateproperty.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
<?php }else  if($row_propRS['STATUS_ID'] == 6){?>
<input type="button" class="buttonsmall" value="Undo Sale" style="width:90px" onclick="gotoUrl('../admin/adminundosale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
<input type="button" class="buttonsmall" value="Sale Completed" style="width:100px" onclick="gotoUrl('../admin/admincompletesale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />

<?php }else  if($row_propRS['STATUS_ID'] == 7){?>
<input type="button" class="buttonsmall" value="Undo Sale" style="width:90px" onclick="gotoUrl('../admin/adminundosale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
 <input type="button" class="buttonsmall" value="Sale Completed" style="width:100px" onclick="gotoUrl('../admin/admincompletesale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />

<?php }else  if($row_propRS['STATUS_ID'] == 8){?>
<input type="button" class="buttonsmall" value="Undo Sale" style="width:90px" onclick="gotoUrl('../admin/adminundosale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
     
<input type="button" class="buttonsmall" value="Undo Sale" style="width:90px" onclick="gotoUrl('../admin/adminundosale.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>')"/><br />
<?php }else  if($row_propRS['STATUS_ID'] == 9){?>
	      <input type="button" class="buttonsmall" value="Put On Web" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&PROPPUTONWEB=PROPPUTONWEB')"/><br />
<input type="button" class="buttonsmall" value="Delete" style="width:90px" onclick="gotoUrl('../admin/adminpropertysearch.php?PROPERTY_ID=<?php echo $row_propRS['PROPERTY_ID'];?>&PROPDELETE=PROPDELETE')"/><br />
<?php }?>

