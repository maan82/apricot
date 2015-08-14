<?php 
function lastday($month = '', $year = '') {
   if (empty($month)) {
      $month = date('m');
   }
   if (empty($year)) {
      $year = date('Y');
   }
   $result = strtotime("{$year}-{$month}-01");
   $result = strtotime('-1 second', strtotime('+1 month', $result));
   return date('d', $result);
   
}
function getNextMonth(){
	$year = date('Y');
	$month = date('m');
	$result = strtotime("{$year}-{$month}-01");
	//$result = strtotime('+1 month', $result);
	return date('m', $result);
}
function getYear(){
	$year = date('Y');
	$month = date('m');
	$result = strtotime("{$year}-{$month}-01");
	$result = strtotime('+1 month', $result);
	return date('Y', $result);
}
    /*
        PHP Mortgage Calculator
        version: 1.1
        last update: Jan 1, 2003
        ----------------------------------------------------
        The PHP Mortgage Calculator tries to figure out a home 
        owners mortgage payments, and the breakdown of each monthly
        payment.
        
        The calculator accepts:
            Price (cost of home in US Dollars)
            Percentage of Down Payment
            Length of Mortgage
            Annual Interest Rate
        
        Based on the four items that the user enters, we can figure
        out the down payment (in US Dollars), the ammount that the 
        buyer needs to finance, and the monthly finance payment. 
        The calculator can also break down the monthly payments 
        so we know how much goes towards the mortgage's interest, 
        the mortgage's principal, the loan's Private Mortgage Insurance 
        (if less that 20% was used as a down payment), and an rough 
        estimate of the property's residential tax
        
        [ See below for LICENSE ]
    */
    
    /* --------------------------------------------------- *
     * Set Form DEFAULT values
     * --------------------------------------------------- */
    $default_sale_price              = "150000";
    $default_annual_interest_percent = 7.0;
    $default_year_term               = 30;
    $default_down_percent            = 10;
    $default_show_progress           = TRUE;
    /* --------------------------------------------------- */
    


    /* --------------------------------------------------- *
     * Initialize Variables
     * --------------------------------------------------- */
    $sale_price                      = 0;
    $annual_interest_percent         = 0;
    $year_term                       = 0;
    $down_percent                    = 0;
    $this_year_interest_paid         = 0;
    $this_year_principal_paid        = 0;
    $form_complete                   = false;
    $show_progress                   = false;
    $monthly_payment                 = false;
    $show_progress                   = false;
    $error                           = false;
    /* --------------------------------------------------- */


    /* --------------------------------------------------- *
     * Set the USER INPUT values
     * --------------------------------------------------- */
    if (isset($_REQUEST['form_complete'])) {
        $sale_price                      = $_REQUEST['sale_price'];
        $annual_interest_percent         = $_REQUEST['annual_interest_percent'];
        $year_term                       = $_REQUEST['year_term'];
        $down_percent                    = $_REQUEST['down_percent'];
        $show_progress                   = (isset($_REQUEST['show_progress'])) ? $_REQUEST['show_progress'] : false;
        $form_complete                   = $_REQUEST['form_complete'];
    }
    /* --------------------------------------------------- */
    /* --------------------------------------------------- */
    // This function does the actual mortgage calculations
    // by plotting a PVIFA (Present Value Interest Factor of Annuity)
    // table...
    function get_interest_factor($year_term, $monthly_interest_rate) {
        global $base_rate;
        
        $factor      = 0;
        $base_rate   = 1 + $monthly_interest_rate;
        $denominator = $base_rate;
        for ($i=0; $i < ($year_term * 12); $i++) {
            $factor += (1 / $denominator);
            $denominator *= $base_rate;
        }
        return $factor;
    }        
    /* --------------------------------------------------- */
    // If the form is complete, we'll start the math
    if ($form_complete) {
        // We'll set all the numeric values to JUST
        // numbers - this will delete any dollars signs,
        // commas, spaces, and letters, without invalidating
        // the value of the number
        $sale_price              = ereg_replace( "[^0-9.]", "", $sale_price);
        $annual_interest_percent = eregi_replace("[^0-9.]", "", $annual_interest_percent);
        $year_term               = eregi_replace("[^0-9.]", "", $year_term);
        $down_percent            = eregi_replace("[^0-9.]", "", $down_percent);
        
        if (((float) $year_term <= 0) || ((float) $sale_price <= 0) || ((float) $annual_interest_percent <= 0)) {
            $error = "You must enter a <b>Sale Price of Home</b>, <b>Length of Motgage</b> <i>and</i> <b>Annual Interest Rate</b>";
        }
        
        if (!$error) {
            $month_term              = $year_term * 12;
            $down_payment            = $sale_price * ($down_percent / 100);
            $annual_interest_rate    = $annual_interest_percent / 100;
            $monthly_interest_rate   = $annual_interest_rate / 12;
            $financing_price         = $sale_price - $down_payment;
            $monthly_factor          = get_interest_factor($year_term, $monthly_interest_rate);
            $monthly_payment         = $financing_price / $monthly_factor;
			if($_GET['calctype'] == "I"){
				$monthly_payment =  $monthly_interest_rate * $financing_price;
			}
        }
    } else {
        if (!$sale_price)              { $sale_price              = $default_sale_price;              }
        if (!$annual_interest_percent) { $annual_interest_percent = $default_annual_interest_percent; }
        if (!$year_term)               { $year_term               = $default_year_term;               }
        if (!$down_percent)            { $down_percent            = $default_down_percent;            }
        if (!$show_progress)           { $show_progress           = $default_show_progress;           }
    }
    
    if ($error) {
        print("<font color=\"red\">" . $error . "</font><br><br>\n");
        $form_complete   = false;
    }
//echo "form_complete = ".$_REQUEST['form_complete'];
?>
