<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php require_once('class.Email.php'); ?>

<?php
sessionStart("View Contact Us Queries");


  $query_queriesRS = "SELECT * FROM email_alerts_view where enabled=1 or enabled is NULL ";
  $queriesRS = mysql_query($query_queriesRS) or die(mysql_error());
  $row_queriesRS = mysql_fetch_assoc($queriesRS);
  $totalRows_queriesRS = mysql_num_rows($queriesRS);
  

  $Subject = 'Property Details';
  $CustomHeaders= '';
  if ($totalRows_queriesRS > 0) {
//	  do {
	  	$URL = 'http://'.$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']."/aeon/pages/email-alert-properties.php?lookingfor=".$row_queriesRS['PROPERTY_FOR_ID']."&price=".$row_queriesRS['MAX_PRICE'];
        
        $contents = file_get_contents($URL);

		if (strpos($contents, 'img') !== FALSE) { 

		    $message = new Email("rsmaan4u8@gmail.com", $EMAIL, $Subject, $CustomHeaders);
		  
		    $message->SetHtmlContent($text);
		
		    $message->Send();
		}
	//  } while ($row_queriesRS = mysql_fetch_assoc($queriesRS));  
  }
?>
