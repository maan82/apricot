<?php
require_once ('constants.php');
if (!isset($_SESSION)) {
	  session_start();
	}


?>


<div class="header" >
	
	
	<div style="width: 980px;margin: 0px auto;">
	    <div style="width: 500px;float: left">
		<a href="index.php"> <img src="images/new-images/apricot-property-logo.png" style="width: 500px;float: left"/>
		    <div style="float: right; margin-top: -35px; font-style: italic;font-size: 28px"><span style="color: black">Property</span> Services <span style="color: black;">Ltd.</span></div> </a>
		</div>
		
		<div class="address" style="float:right;text-align: right;">
		   
		    
<?php if(strpos($_SESSION['MM_Roles'],"Add or Edit Property") === false ){ //Do nothing?>

<?php }else{?>
    
			<div class="button" style="text-align: center;float: right;padding-top: 3px">
				<a style="color:white;font-weight: bold;font-size: 14px;font: Tahoma" href="../admin/adminlogin.php">Admin Home</a>

			</div>
			<div style="clear: both"></div>

<?php }?>	
	
			
			<div>
				Telephone: <?php echo $PHONE; ?>
				
			</div>
			<div>
				Email: <a href="mailto:<?php echo $EMAIL;?>"> <?php echo $EMAIL; ?>
				</a>
			</div>
			<div class="punchline">
			    Working with Honesty and Transparency
			</div>
		</div>
		<div style="clear: both;"></div>
	</div>
</div>
<div style="clear: both;border-bottom: 3px solid #db6518;"></div>

<div class="menu_wrapper">
	<div class="menu_div">

		<div class="menu">
			<ul >
				<li >
					<a href="index.php" >Home </a>
				</li>
				<li >
					<a href="buy.php" >For Sale </a>
				</li>
				<li >
					<a href="rent.php" >For Rent</a>
				</li>
				<li >
					<a href="valuation.php" >Valuation </a>
				</li>
                <li >
                    <a href="construction.php" >Building/Construction/Plumbing </a>
                </li>

				<li style="border:none">
					<a href="contact-us.php" >Contact Us </a>
				</li>

			</ul>

		</div>
	</div>

</div>
<div style="width: 100%;background-color: #01e20c; height: 5px;"></div>

