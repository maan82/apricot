<!--
  copyright (c) 2009 Google inc.

  You are free to copy and use this sample.
  License can be found here: http://code.google.com/apis/ajaxsearch/faq/#license
-->

<?php
   include("mortgagefunctions.php");
    // This prints the calculation progress and 
    // the instructions of HOW everything is figured
    // out
//	echo $form_complete; 
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
		
		  $i = 0;
		  $month = getNextMonth();
		  $month = $month + 1 -1;
		  $year = 0;

		
			if(isset($_REQUEST['summery']))
			{
				$total_interest_paid  = 0;
				$total_principal_paid = 0;

				$scriptPieChart = "var data = new google.visualization.DataTable();";
				$scriptPieChart .= "data.addColumn('string', 'Payment Type');";
		        $scriptPieChart .= "data.addColumn('number', 'Amount');";
				$scriptPieChart .= "data.addRows(2);";
				
				$scriptTable = "var data = new google.visualization.DataTable();";
		        $scriptTable .= "data.addColumn('number', 'Year');";
				$scriptTable .= "data.addColumn('number', 'Interest');";
				$scriptTable .= " data.addColumn('number', 'Principal');";
				$scriptTable .= "  data.addColumn('number', 'Balance');";
				$scriptTable .= "data.addRows($year_term+1);";
				while ($current_month <= $month_term) 
				{  
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

					if(($month+1) % 12 ) 
					{ 
						$show_legend = FALSE ; 
						$month++;
					} 
					else 
					{ 
						
						$show_legend = TRUE;
						$month = 0;
					}
			
					if ($show_legend) {
						$scriptTable .= "data.setCell($year, 0, $current_year);";
						$scriptTable .= "data.setCell($year, 1, $this_year_interest_paid);";
						$scriptTable .= "data.setCell($year, 2, $this_year_principal_paid);";
						$scriptTable .= "data.setCell($year, 3, $remaining_balance);";
						$year++;
						$current_year++;
						$total_interest_paid  += $this_year_interest_paid;
						$total_principal_paid += $this_year_principal_paid;
						$this_year_interest_paid  = 0;
						$this_year_principal_paid = 0;
					}
					$principal = $remaining_balance;
					$current_month++;
					
					if(!($current_month <= $month_term))
					{
						$scriptTable .= "data.setCell($year, 0, $current_year);";
						$scriptTable .= "data.setCell($year, 1, $this_year_interest_paid);";
						$scriptTable .= "data.setCell($year, 2, $this_year_principal_paid);";
						$scriptTable .= "data.setCell($year, 3, $remaining_balance);";
						$total_interest_paid  += $this_year_interest_paid;
						$total_principal_paid += $this_year_principal_paid;
						
						$scriptPieChart .= "data.setValue(0, 0, 'Principal');";
						$scriptPieChart .= "data.setValue(0, 1, $total_principal_paid);";
						$scriptPieChart .= "data.setValue(1, 0, 'Interest');";
						$scriptPieChart .= "data.setValue(1, 1, $total_interest_paid);";
					}
				}//end of while loop
			}//end of if
			else
			{
				$total_interest_paid  = 0;
				$total_principal_paid = 0;
				$j = 0;
				$mn = 0;

				$scriptPieChart = "var data = new google.visualization.DataTable();";
				$scriptPieChart .= "data.addColumn('string', 'Payment Type');";
		        $scriptPieChart .= "data.addColumn('number', 'Amount');";
				$scriptPieChart .= "data.addRows(2);";
				
				$scriptTable = "var data = new google.visualization.DataTable();";
		        $scriptTable .= "data.addColumn('number', 'Month');";
				$scriptTable .= "data.addColumn('number', 'Interest');";
				$scriptTable .= " data.addColumn('number', 'Principal');";
				$scriptTable .= "  data.addColumn('number', 'Balance');";
				//$scriptTable .= "data.addRows(12);";
				

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
				
					
					if($_REQUEST['year'] == $current_year)
					{
						$scriptTableDT .= "data.setCell($mn, 0, $month+1);";
						$scriptTableDT .= "data.setCell($mn, 1, $interest_paid);";
						$scriptTableDT .= "data.setCell($mn, 2, $principal_paid);";
						$scriptTableDT .= "data.setCell($mn, 3, $remaining_balance);";
						$mn++;
						$interest_for_year =  $this_year_interest_paid ;
						$principal_for_year =  $this_year_principal_paid ;
					}
					
					if(($month+1) % 12 ) 
					{ 
						$show_legend = FALSE ; 
						$month++;
					} 
					else 
					{ 
						
						$show_legend = TRUE;
						$month = 0;
					}
			
					if ($show_legend ) {
						$year++;
						$current_year++;
						$total_interest_paid  += $this_year_interest_paid;
						$total_principal_paid += $this_year_principal_paid;
						$this_year_interest_paid  = 0;
						$this_year_principal_paid = 0;
					}
					$principal = $remaining_balance;
					$current_month++;

					
				}//end of while
				$scriptTable .= "data.addRows($mn);";
				$scriptTable .= $scriptTableDT;
				$scriptPieChart .= "data.setValue(0, 0, 'Principal');";
				$scriptPieChart .= "data.setValue(0, 1, $principal_for_year);";
				$scriptPieChart .= "data.setValue(1, 0, 'Interest');";
				$scriptPieChart .= "data.setValue(1, 1, $interest_for_year);";

			}
	}//end of main if
		?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
      Google Visualization API Sample
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
  <script type="text/javascript">
	  google.load('visualization', '1', {packages: ['table', 'piechart', 'barchart', 'columnchart']});

  	  var pieChartVisualization;
      function drawPieChart() {
        // Create and populate the data table.
      	<?php echo $scriptPieChart ?>
        // Create and draw the visualization.
        pieChartVisualization = new google.visualization.PieChart(document.getElementById('pieChartDIV'));
		
		var formatter = new google.visualization.NumberFormat();
		formatter.format(data, 0); // Apply formatter to second column
		formatter.format(data, 1); // Apply formatter to second column

		
        pieChartVisualization.draw(data, {is3D:true, legend:'label'});
	    // Add our over/out handlers.
		google.visualization.events.addListener(pieChartVisualization, 'onmouseover', pieMouseOver);
		google.visualization.events.addListener(pieChartVisualization, 'onmouseout', pieMouseOut);

      }
   	  function pieMouseOver(e) {
		pieChartVisualization.setSelection([e]);
	  }
	  
	  function pieMouseOut(e) {
		pieChartVisualization.setSelection([{'row': null, 'column': null}]);
	  }

	  
  	  var barChartVisualization;

	 function drawBarChart() {
		<?php echo $scriptPieChart ?>
        var options = {};
      
        // 'bhg' is a horizontal grouped bar chart in the Google Chart API.
        // The grouping is irrelevant here since there is only one numeric column.
        options.cht = 'bhg';
      
        // Add a data range.
        //var min = 0;
        //var max = 700;
        //options.chds = min + ',' + max;
      
        // Now add data point labels at the end of each bar.
      
        // Add meters suffix to the labels.
        //var meters = 'N** m';
      
        // Draw labels in pink.
        var color = 'ff3399';
      
        // Google Chart API needs to know which column to draw the labels on.
        // Here we have one labels column and one data column.
        // The Chart API doesn't see the label column.  From its point of view,
        // the data column is column 0.
        var index = 0;
      
        // -1 tells Google Chart API to draw a label on all bars.
        var allbars = -1;
      
        // 10 pixels font size for the labels.
        var fontSize = 10;
       
        // Priority is not so important here, but Google Chart API requires it.
        var priority = 0;
      
        options.chm = [meters, color, index, allbars, fontSize, priority].join(',');
      
        // Create and draw the visualization.
        new google.visualization.ImageChart(document.getElementById('barChartDIV')).
          draw(data, options);  
      }
   	  function barMouseOver(e) {
		barChartVisualization.setSelection([e]);
	  }
	  
	  function barMouseOut(e) {
		barChartVisualization.setSelection([{'row': null, 'column': null}]);
	  }
	  
    function drawTable() {
		<?php echo $scriptTable ?>
    
      // Create and draw the visualization.
      tableVisualization = new google.visualization.Table(document.getElementById('tableDIV'));
  		var formatter = new google.visualization.NumberFormat();
		formatter.format(data, 1); // Apply formatter to second column
		formatter.format(data, 2); // Apply formatter to second column
		formatter.format(data, 3); // Apply formatter to second column

      tableVisualization.draw(data, {'sort':'disable'});
    }
    


		function drawAll(){
			drawPieChart();
			//drawBarChart();
			drawTable();
		}
      google.setOnLoadCallback(drawAll);
    </script>
  <link href="css-mortgagecalculator/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
  </head>
  <body style="font-family: Arial;border: 0 none;">
  
    
  <div id="TabbedPanels1" class="TabbedPanels">
      <ul class="TabbedPanelsTabGroup">
        <li class="TabbedPanelsTab" tabindex="0">Pie Chart</li>
        <li class="TabbedPanelsTab" tabindex="0">Bar Chart</li>
        <li class="TabbedPanelsTab" tabindex="0">Table</li>
      </ul>
      <div class="TabbedPanelsContentGroup">
        <div class="TabbedPanelsContent">
          <div id="pieChartDIV" style="width: 245px; height: 200px;"></div>
        </div>
        <div class="TabbedPanelsContent">
        <?php 
		if(isset($_REQUEST['summery']))
			{
				if($total_principal_paid > $total_interest_paid)
					$max = $total_principal_paid;
				else
					$max = $total_interest_paid;
		?>
         <img src="http://chart.apis.google.com/chart?chxt=x&amp;chxr=0,0,<?php echo intval($max); ?>&amp;chds=0,<?php echo intval($max); ?>&amp;chdl=Principal <?php echo number_format($total_principal_paid, 2, '.', ''); ?>|Interest <?php echo number_format($total_interest_paid, 2, '.', ''); ?>&amp;cht=bhs&amp;chd=t:<?php echo number_format($total_principal_paid, 2, '.', ''); ?>,<?php echo number_format($total_interest_paid, 2, '.', ''); ?>&amp;chco=76A4FB|16149B&amp;chdlp=bv&amp;chs=200x125" alt="" />
        <?php } else { 
						if($principal_for_year > $interest_for_year)
							$max = $principal_for_year;
						else
							$max = $interest_for_year;

		?>
             <img src="http://chart.apis.google.com/chart?chxt=x&amp;chxr=0,0,<?php echo intval($max); ?>&amp;chds=0,<?php echo intval($max); ?>&amp;chdl=Principal <?php echo number_format($principal_for_year, 2, '.', ''); ?>|Interest <?php echo number_format($interest_for_year, 2, '.', ''); ?>&amp;cht=bhs&amp;chd=t:<?php echo number_format($principal_for_year, 2, '.', ''); ?>,<?php echo number_format($interest_for_year, 2, '.', ''); ?>&amp;chco=76A4FB|16149B&amp;chdlp=bv&amp;chs=200x125" alt="" />
        <?php }?>		

        

        </div>
        <div class="TabbedPanelsContent"><div id="tableDIV" style="width: 245px; height: 300px;"></div></div>
    </div>
      </div>
  </div>
  <script type="text/javascript">
<!--
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
//-->
    </script>
</body>
</html>
