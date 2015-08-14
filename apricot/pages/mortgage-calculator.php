<?php include("mortgagefunctions.php"); 

    
    // If HTML headers have not already been sent, we'll print some here    
    if (!headers_sent()) {
        print("<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'><HTML>");
        print('<head><title>Mortgage Calculator</title><script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
<link href="css-mortgagecalculator/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" type="text/css" href="css-mortgagecalculator/style_from_real.css" /> <script type="text/javascript" src="http://www.google.com/jsapi"></script><link rel="stylesheet" href="css-mortgagecalculator/style_from_real.css" type="text/css"/><script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script><link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" /> </HEAD><BODY>');
        print("<body bgcolor='#ffffff' style='width:970px'>");
        print("<H2 >Mortgage Calculator</h2>");
        print("<hr>\n\n");
        $print_footer = TRUE;
    } else {
       $print_footer = FALSE;
    }
    
    // Style Sheet
    ?>
    <style type="text/css">
        <!--
            td {
                font-size : 11px; 
                font-family : tahoma, helvetica, arial, lucidia, sans-serif; 
                color : #000000; 
            }
        -->
    </style> 


    <?php require_once('Connections/adestate.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET["MM_insert"])) && ($_GET["MM_insert"] == "information")) {
  $insertSQL = sprintf("INSERT INTO mortgage_calculations (EMAIL_ID_MC, CONTACT_NO_MC, HOME_PRICE_MC, DOWN_MC, MORTGAGE_LENGTH_MC, RATE_MC) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_GET['email'], "text"),
                       GetSQLValueString($_GET['phone'], "text"),
                       GetSQLValueString($_GET['sale_price'], "int"),
                       GetSQLValueString($_GET['down_percent'], "int"),
                       GetSQLValueString($_GET['year_term'], "int"),
                       GetSQLValueString($_GET['annual_interest_percent'], "int"));


  $Result1 = mysql_query($insertSQL) or die(mysql_error());
}
?>
<link rel="stylesheet" href="css-mortgagecalculator/style_from_real.css" type="text/css"/>
<font size="-1" color="#000000">This <b>mortgage calculator</b> can be used to figure out monthly payments of a home mortgage loan,
 based on the home's sale price, the term of the loan desired, buyer's down payment percentage, and the loan's interest rate.
  <br>Please note this is to be purely used for illustration purposes only.Please read terms and conditions of your mortgage provider.If you dont keep up repayments your home may be repossed.<br /></font>

<div id="for_sale" style="width:665;float:left;" >

        	<div class="small_title"><span class="small_title_text">Purchase &amp; Financing Information</span> </div>
            <div id="offer_box_cover">
              <div class="offer_box" style="border:none">
              <form method="get" name="information" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="hidden" name="form_complete" value="1">
<fieldset>
  <legend><span>Property Details Form</span>
  </legend>
              <table cellpadding="2" cellspacing="0" border="0" width="100%" class="form_table_search">
    <tr valign="top">
        <td align="right"><img src="/images/clear.gif" width="225" height="1" border="0" alt=""></td>
        <td align="smalltext" width="100%"><img src="/images/clear.gif" width="250" height="1" border="0" alt=""></td>
    </tr>
    <tr  >
        <td align="left" valign="top" class="label">Your Email Id:<br />
          <span id="sprytextfield1">
          <input type="text"  name="email" value="<?php echo $_GET['email']; ?>" /><br />
          <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span></span><br />
Contact No.:<br />
<input type="text"  name="phone" value="<?php echo $_GET['phone']; ?>" /></td>
        <td align="left" valign="top" class="label">
Purchase Price of Home:
        <br />
        <span id="sprytextfield3">
        <input type="text" size="10" name="sale_price" value="<?php echo $sale_price; ?>" /><br />
        <span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span><br />
Deposit:<br />
<span id="sprytextfield4">
<input type="text" size="5" name="down_percent" value="<?php echo $down_percent; ?>" />%<br />
<span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span><br />
Term of Mortgage:<br />
<span id="sprytextfield5">
<input type="text" size="3" name="year_term" value="<?php echo $year_term; ?>" />years<br />
<span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldMaxCharsMsg">Exceeded maximum number of characters.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
<br />Interest Charged:
<br />
<span id="sprytextfield6">
<input type="text" size="5" name="annual_interest_percent" value="<?php echo $annual_interest_percent; ?>" />%<br />
<span class="textfieldRequiredMsg">Mandatory.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
<br />
Mortgage Type<br />
<label>
<input type="radio" name="calctype"  value="R" <?php if(strcmp($_GET['calctype'],"I") != 0) {echo "checked = 'checked'";}?>/> Repayment</label>
<label>
<input type="radio" name="calctype"  value="I" <?php if(strcmp($_GET['calctype'],"I") == 0) {echo "checked = 'checked'";}?>/>Interest Only</label>
<br /><br />
<input type="checkbox" name="show_progress" value="1" <?php if ($show_progress) { print("checked"); } ?>> 
        Show me the calculations and amortization
        
        </td>
    </tr>
    <tr valign="top" >
        <td>&nbsp;</td>
        <td><input type="submit" value="Calculate"><br>
        <?php if ($form_complete) { print("<a href=\"" . $_SERVER['PHP_SELF'] . "\">Start Over</a><br>"); } ?><br></td>
    </tr>
    </table>
    </fieldset>
    <input type="hidden" name="MM_insert" value="information" />
    </form>

<script>
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email", {maxChars:200});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "real");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "real");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "integer", {maxChars:2});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "real");

</script>


                <div style="clear:both;"></div>
              </div>
            </div>
</div>
   

<?php
    // If the form has already been calculated, the $down_payment
    // and $monthly_payment variables will be figured out, so we
    // can show them in this table
    if ($form_complete && $monthly_payment) {
?>
<div style="clear:both;"></div>
  	<div id="for_sale" style="width:100%;margin-top:5px;">
        	<div class="small_title"><span class="small_title_text">Mortgage Payment Information</span></div>
            <div id="offer_box_cover">
              <div class="offer_box">

              
              <table  cellpadding="2" cellspacing="0" border="0" width="100%">
              <th>
                          Down Payment:

              </th>
              <th>
              Amount Financed:
              </th>
              <th>
              Monthly Payment:
              </th>

        <tr align="center" >
            <td><b><?php echo "&pound;" . number_format($down_payment, "2", ".", ","); ?></b></td>
            <td><b><?php echo "&pound;" . number_format($financing_price, "2", ".", ","); ?></b></td>
            
            <td><b><?php echo "&pound;" . number_format($monthly_payment, "2", ".", ","); ?></b><br><font><?php if($_GET['calctype'] == "I"){
				echo "(Interest Only)";
			} else echo "(Principal &amp; Interest )"?></font></td>

        </tr>
</table>        

                <div style="clear:both;"></div>
              </div>
            </div>
</div>
        
<?php    
    }
?>


<br>
<?php
    // This prints the calculation progress and 
    // the instructions of HOW everything is figured
    // out
    if ($form_complete && $show_progress) {

        // Set some base variables
        $principal     = $financing_price;
        $current_month = 1;
        $current_year  = getYear();
        // This basically, re-figures out the monthly payment, again.
        $power = -($month_term);
        $denom = pow((1 + $monthly_interest_rate), $power);
        
		if($_GET['calctype'] == "R"){
			$monthly_payment = $principal * ($monthly_interest_rate / (1 - $denom));
		}
        
        //print("<br><br><a name=\"amortization\"></a>Amortization For Monthly Payment: <b>&pound;" . number_format($monthly_payment, "2", ".", ",") . "</b> over " . $year_term . " years<br>\n");
        //print("<table cellpadding=\"5\" cellspacing=\"0\" bgcolor=\"#eeeeee\" border=\"1\" width=\"100%\">\n");
        
        // This LEGEND will get reprinted every 12 months
        $legend  = "\t<tr valign=\"top\" bgcolor=\"#cccccc\">\n";
        $legend .= "\t\t<td align=\"right\"><b>Month</b></td>\n";
        $legend .= "\t\t<td align=\"right\"><b>Interest Paid</b></td>\n";
        $legend .= "\t\t<td align=\"right\"><b>Principal Paid</b></td>\n";
        $legend .= "\t\t<td align=\"right\"><b>Remaing Balance</b></td>\n";
        $legend .= "\t</tr>\n";
        
        //echo $legend;

	  $script = "var data = new google.visualization.DataTable();";
      
      $script .= "data.addColumn('date', 'Date');";
      $script .= "data.addColumn('number', 'Interest');";
	  $script .= "data.addColumn('string', 'Title');";
	  $script .= "data.addColumn('string', 'Description');";
      $script .= "data.addColumn('number', 'Principle');";
	  $script .= "data.addColumn('string', 'Title2');";
	  $script .= "data.addColumn('string', 'Description2');";
      $script .= "data.addColumn('number', 'Total');";
	  $script .= "data.addColumn('string', 'Title3');";
	  $script .= "data.addColumn('string', 'Description3');";
 	  $script .= "data.addRows($month_term );\n";
	$i = 0;
	  $month = getNextMonth();
	  $month = $month + 1 -1;
                
        // Loop through and get the current month's payments for 
        // the length of the loan 
        while ($current_month <= $month_term) {  

			if($_GET['calctype'] == "R"){
				$interest_paid     = $principal * $monthly_interest_rate;
				$principal_paid    = $monthly_payment - $interest_paid;
				$remaining_balance = $principal - $principal_paid;
			} else {
				$interest_paid     = $principal * $monthly_interest_rate;
				$principal_paid    = 0;
				$remaining_balance = $principal ;
				
			}

		
			
            
            $this_year_interest_paid  = $this_year_interest_paid + $interest_paid;
            $this_year_principal_paid = $this_year_principal_paid + $principal_paid;
			
			$day = lastday($month+1,$current_year);
			//   --------------------->>>>   month in chart API start from 0 
			$script .= "data.setValue($i, 0, new Date ($current_year,$month,$day));\n";
            $script .= "data.setValue($i, 1, $interest_paid);\n";

            $script .= "data.setValue($i, 4, $principal_paid);\n";
			$script .= "data.setValue($i, 7, $monthly_payment);\n";
    		if(!(($month+1) % 12) ) 
			{ 
				$tt = "<td colspan=\"4\"><span class=\"view_property_bar\" style=\"border:none;\"><a href=\"#\" onClick=\"getChart(".$current_year.");return false;\"><span>View ".$current_year. " Details</span> </a></span></td>";
				$script .= "data.setValue($i, 8, '$tt');\n";
				$msg = "<table>";
                $total_spent_this_year = $this_year_interest_paid + $this_year_principal_paid;
                $msg .= "<tr valign=\"top\" bgcolor=\"#ffffcc\">";
                $msg .= "<td>&nbsp;</td>";
                $msg .= "<td colspan=\"3\">";
                $msg .= "&pound;" . number_format($this_year_interest_paid, "2", ".", ",") . " will go towards INTEREST<br>";
                $msg .= "&pound;" . number_format($this_year_principal_paid, "2", ".", ",") . " will go towards PRINCIPAL<br>";

                $msg .= "You will spend &pound;" . number_format($total_spent_this_year, "2", ".", ",") . " on your house in year " . $current_year . "<br>";
                $msg .= "</td>";
                $msg .= "</tr>";
    
                $msg .= "<tr valign=\"top\" bgcolor=\"#ffffff\">";
                $msg .= "<td colspan=\"4\"><span class=\"view_property_bar\" style=\"border:none;\"><a href=\"#\" onClick=\"getChart(".$current_year.");return false;\"><span>View ".$current_year. " Details</span> </a></span></td>";
                $msg .= "</tr></table>";

				$script .= "data.setValue($i, 9, '');";


			} 

			
			
            
   
            if(($month+1) % 12 ) 
			{ 
			$show_legend = FALSE ; 
			$month++;
			} 
			else 
			{ $show_legend = TRUE;$month = 0;}
    
            if ($show_legend) {
                $current_year++;
                $this_year_interest_paid  = 0;
                $this_year_principal_paid = 0;
                
            }
    
            $principal = $remaining_balance;
            $current_month++;
			if(!($current_month <= $month_term) && $month > 0)
				{
					$tt = "<td colspan=\"4\"><span class=\"view_property_bar\" style=\"border:none;\"><a href=\"#\" onClick=\"getChart(".$current_year.");return false;\"><span>View ".$current_year. " Details</span> </a></span></td>";
					$script .= "data.setValue($i, 8, '$tt');\n";
					$script .= "data.setValue($i, 9, '');\n";
				}
				$i++;

        }
		?>
        

  <div id="TabbedPanels1" class="TabbedPanels">
    <ul class="TabbedPanelsTabGroup">
      <li class="TabbedPanelsTab" tabindex="0" >  <span style="color:#1D67A2">Charts</span> </li>
      <li class="TabbedPanelsTab" tabindex="0"><span style="color:#1D67A2">Amortization Table</span></li>
    </ul>
    <div class="TabbedPanelsContentGroup">
      <div class="TabbedPanelsContent">
        <table width="920">
        <tr>
        <td width="650">
        <div id="visualization" style="width: 650px; height: 400px;" style="float:left"></div>
        </td>
        <td align="left" width="270">
            
            <div >
            <div class="view_property_bar" style="text-align:left;border:none;padding-bottom:5px;"><a href="#" onClick="getSummeryChart();return false;"><span>All Years Summery</span></a></div>
            <div>
            <iframe id="chartFrame" src="" width="270" height="380" scrolling="no" style="border:none">
            
            </iframe>
            </div>
            </div>
        </td>
        </tr>
        </table>
      </div>
      <div class="TabbedPanelsContent" >
      <div style="height:450px;overflow:auto">
      <?php
    // This prints the calculation progress and 
    // the instructions of HOW everything is figured
    // out
    if ($form_complete && $show_progress ) {
        $step = 1;
?>
        <br><br>
        <table cellpadding="5" cellspacing="0" border="1" width="100%">
            
            <tr valign="top">
                <td><b><?php echo $step++; ?></b></td>
                <td>
                    The <b>interest rate</b> = The annual interest percentage divided by 100<br><br>
                    <?php echo $annual_interest_rate; ?> = <?php echo $annual_interest_percent; ?>% / 100
                </td>
            </tr>
            <tr valign="top" bgcolor="#cccccc">
                <td colspan="2">
                    The <b>monthly factor</b> = The result of the following formula:
                </td>
            </tr>
            <tr valign="top">
                <td><b><?php echo $step++; ?></b></td>
                <td>
                    The <b>monthly interest rate</b> = The annual interest rate divided by 12 (for the 12 months in a year)<br><br>
                    <?php echo $monthly_interest_rate; ?> = <?php echo $annual_interest_rate; ?> / 12
                </td>
            </tr>
            <tr valign="top">
                <td><b><?php echo $step++; ?></b></td>
                <td>
                    The <b>month term</b> of the loan in months = The number of years you've taken the loan out for times 12<br><br>
                    <?php echo $month_term; ?> Months = <?php echo $year_term; ?> Years X 12
                </td>
            </tr>
            <?php if($_GET['calctype'] == "R"){ ?>
            <tr valign="top">
                <td><b><?php echo $step++; ?></b></td>
                <td>
                    The montly payment is figured out using the following formula:<br>
                    Monthly Payment = <?php echo number_format($financing_price, "2", "", ""); ?> * 
                    (<?php echo number_format($monthly_interest_rate, "4", "", ""); ?> / 
                    (1 - ((1 + <?php echo number_format($monthly_interest_rate, "4", "", ""); ?>)
                    <sup>-(<?php echo $month_term; ?>)</sup>)))
                    <br><br>
                    The <a href="#amortization">amortization</a> breaks down how much of your monthly payment goes towards the bank's interest,
                     and how much goes into paying off the principal of your loan.
                </td>
            </tr>
            <?php } ?>
        </table>
        <br>
<?php
        // Set some base variables
        $principal     = $financing_price;
        $current_month = 1;
        $current_year  = 1;
        // This basically, re-figures out the monthly payment, again.
        $power = -($month_term);
        $denom = pow((1 + $monthly_interest_rate), $power);
		if($_GET['calctype'] == "R"){
			$monthly_payment = $principal * ($monthly_interest_rate / (1 - $denom));
		}

        
        
        print("<br><br><a name=\"amortization\"></a>Amortization For Monthly Payment: <b>&pound;" . number_format($monthly_payment, "2", ".", ",") . "</b> over " . $year_term . " years<br>\n");
        print("<table cellpadding=\"5\" cellspacing=\"0\" bgcolor=\"#eeeeee\" border=\"1\" width=\"100%\">\n");
        
        // This LEGEND will get reprinted every 12 months
        $legend  = "\t<tr valign=\"top\" bgcolor=\"#cccccc\">\n";
        $legend .= "\t\t<td align=\"right\"><b>Month</b></td>\n";
        $legend .= "\t\t<td align=\"right\"><b>Interest Paid</b></td>\n";
        $legend .= "\t\t<td align=\"right\"><b>Principal Paid</b></td>\n";
        $legend .= "\t\t<td align=\"right\"><b>Remaing Balance</b></td>\n";
        $legend .= "\t</tr>\n";
        
        echo $legend;
                
        // Loop through and get the current month's payments for 
        // the length of the loan 
        while ($current_month <= $month_term) {        
		
			if($_GET['calctype'] == "R"){
				$interest_paid     = $principal * $monthly_interest_rate;
				$principal_paid    = $monthly_payment - $interest_paid;
				$remaining_balance = $principal - $principal_paid;
				
				$this_year_interest_paid  = $this_year_interest_paid + $interest_paid;
				$this_year_principal_paid = $this_year_principal_paid + $principal_paid;
			} else {
				$interest_paid     = $principal * $monthly_interest_rate;
				$principal_paid    = 0;
				$remaining_balance = $principal ;
				$this_year_interest_paid  = $this_year_interest_paid + $interest_paid;
				$this_year_principal_paid = $this_year_principal_paid + $principal_paid;
				
			}


            
            print("\t<tr valign=\"top\" bgcolor=\"#eeeeee\">\n");
            print("\t\t<td align=\"right\">" . $current_month . "</td>\n");
            print("\t\t<td align=\"right\">&pound;" . number_format($interest_paid, "2", ".", ",") . "</td>\n");
            print("\t\t<td align=\"right\">&pound;" . number_format($principal_paid, "2", ".", ",") . "</td>\n");
            print("\t\t<td align=\"right\">&pound;" . number_format($remaining_balance, "2", ".", ",") . "</td>\n");
            print("\t</tr>\n");
    
            ($current_month % 12) ? $show_legend = FALSE : $show_legend = TRUE;
    
            if ($show_legend) {
                print("\t<tr valign=\"top\" bgcolor=\"#ffffcc\">\n");
                print("\t\t<td colspan=\"4\"><b>Totals for year " . $current_year . "</td>\n");
                print("\t</tr>\n");
                
                $total_spent_this_year = $this_year_interest_paid + $this_year_principal_paid;
                print("\t<tr valign=\"top\" bgcolor=\"#ffffcc\">\n");
                print("\t\t<td>&nbsp;</td>\n");
                print("\t\t<td colspan=\"3\">\n");
                print("\t\t\tYou will spend &pound;" . number_format($total_spent_this_year, "2", ".", ",") . " on your house in year " . $current_year . "<br>\n");
                print("\t\t\t&pound;" . number_format($this_year_interest_paid, "2", ".", ",") . " will go towards INTEREST<br>\n");
                print("\t\t\t&pound;" . number_format($this_year_principal_paid, "2", ".", ",") . " will go towards PRINCIPAL<br>\n");
                print("\t\t</td>\n");
                print("\t</tr>\n");
    
                print("\t<tr valign=\"top\" bgcolor=\"#ffffff\">\n");
                print("\t\t<td colspan=\"4\">&nbsp;<br><br></td>\n");
                print("\t</tr>\n");
                
                $current_year++;
                $this_year_interest_paid  = 0;
                $this_year_principal_paid = 0;
                
                if (($current_month + 6) < $month_term) {
                    echo $legend;
                }
            }
    
            $principal = $remaining_balance;
            $current_month++;
        }









print("</table>\n");
    }
?>


      
      </div>
      </div>
    </div>
  </div>
  <script>
   document.all.TabbedPanels1.scrollIntoView(true);
  </script>
<?php
		
	}
?>
<!-- END BODY -->


<?php
/*
    ///// mortgage_calculator.php /////
    Copyright (c) 2002 David Tufts <http://dave.imarc.net> 
    All rights reserved.
    
    Redistribution and use in source and binary forms, with or without 
    modification, are permitted provided that the following conditions 
    are met:
    
    *    Redistributions of source code must retain the above copyright 
     notice, this list of conditions and the following disclaimer.
    *    Redistributions in binary form must reproduce the above 
     copyright notice, this list of conditions and the following 
     disclaimer in the documentation and/or other materials 
     provided with the distribution.
    *    Neither the name of David Tufts nor the names of its 
     contributors may be used to endorse or promote products 
     derived from this software without specific prior 
     written permission.
    
    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND 
    CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, 
    INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF 
    MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE 
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS 
    BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
    EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED 
    TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
    DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON 
    ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, 
    OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY 
    OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
    POSSIBILITY OF SUCH DAMAGE.
*/
?>  
<script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
</script>
<script type="text/javascript">
            google.load('visualization', '1', {packages: ['annotatedtimeline']});
        
            function drawVisualization() {
                <?php echo $script; ?>
            
              var annotatedtimeline = new google.visualization.AnnotatedTimeLine(
                  document.getElementById('visualization'));
              annotatedtimeline.draw(data, {'displayAnnotations': true,'annotationsWidth':20,'fill':20,'legendPosition':'newRow','thickness':2,'allowHtml': true});
			  getSummeryChart();
            }
            
        function getChart(year){
            document.getElementById('chartFrame').src = "mortgage-pie-chart.php?<?php echo $_SERVER['QUERY_STRING']; ?>&year="+year;
        }
        function getSummeryChart(){
            document.getElementById('chartFrame').src = "mortgage-pie-chart.php?<?php echo $_SERVER['QUERY_STRING']; ?>&summery=true";
        }
        
            google.setOnLoadCallback(drawVisualization);
          </script>
