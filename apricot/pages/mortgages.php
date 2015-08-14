<?php 
if(empty($_POST['form_contactform_subject']))
$_POST['form_contactform_subject'] = "Mortgage Query";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php'); ?>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<link href="bg_blocks.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

<script src="SpryAssets/SpryValidationTextarea.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />


</head>

<body >
<?php include('header.php'); ?>

<div id="maincontent" >
<div id="column1" >
<div style="margin-top:10px">

<div class="block">
<div class="text_head">Mortgages</div>

 <div class="text_para">
   <p>Once you have found the home of your choice, the next thing to consider is the finance. We work closely with independent financial advisors who provide independent mortgage advice and can guide you to achieve ideal mortgage, and make the whole process of arranging finance a lot easier.</p>
   <p>As they are independent, they go through all possible mortgage options available to arrange the best achievable deal for you. Using excellent contacts with a large number of lenders, they help you go through this financial process whether you are first time buyer, remortgaging, moving home, buying-to let, arranging commercial finance, looking for shared ownership mortgage or have bad credit. They can offer you the expert and wide-ranging independent quotations.</p>
   <ul>
     <li>Why Use Our Independent Financial Services?</li>
     <li>They provide an extensive search of the whole market.</li>
     <li>With extensive experience, our advisors can help you find the right mortgage very quickly.</li>
     <li>They provide expert and free financial advice.</li>
     <li>They will do all the paperwork for you and also offer written quotations upon request.</li>
     <li>They deal with credit problems, bank refusal, debt consolidation, secure loan and commercial loan other than the basic mortgage or remortgage.</li>
     <li>They can offer interest rates including fixed, discounted and tracker.</li>
   </ul>
   <p>Contact us for Free Independent Mortgage Advise.</p>
 </div>
</div>
</div>


</div>
<div id="column2" >

    <div class="block">
<div class="heading">Resources For Buyers</div>
    <a href="mortgage-calculator.php" target="_blank" onclick="return showUrl(this.href)" class="largebutton"> Mortgage Calculator</a>
   <a href="buy_reasons.php" class="largebutton">  Why Buy Through Aeon</a>
   <a href="step-by-step-guides.php#buyingguide" class="largebutton">  Guide For Buyers</a>
   <a href="emailalerts.php" class="largebutton">  Register For Property Alerts</a>


  </div>
       
                <div>
 <?php include('inc-mortgage-advice.php');?>
    </div>

               <div>
           <?php include('inc-shortlist.php'); ?>
          </div>
     

</div>

</div>

<?php include('footer.php'); ?>



</body>
</html>