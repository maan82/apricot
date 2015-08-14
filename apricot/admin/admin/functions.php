<?php 

//Function to get values
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

/**
This function set the style values to match spry like validation .
*/
function textFieldStyle($formHiddenFieldValue,$errArrayVal,$errArray)
{
	if (  isset($errArrayVal) 
		  && (!strcasecmp($errArrayVal , 'Required') 
			  ||   !strcasecmp($errArrayVal, 'Format')
			  ||   !strcasecmp($errArrayVal, 'Max'))) //Check if this field has failed validation.
			  {
				  echo 'background-color: #FF9F9F';
	} else if ($errArray) { // If this field passes validation but other fields have failed.
		 echo 'background-color: #B8F5B1';
	}

}
/**
This function make fields sticky if there is server side validation error .
*/
function stickyValue($formHiddenFieldValue,$fieldValue)
{
		echo htmlentities($fieldValue,ENT_COMPAT,'UTF-8');
}
/**
This function make fields sticky if there is server side validation error .
Test case below
errMessage('Max','cssClass');
errMessage('Format','cssClass');
errMessage('Required','cssClass');

*/

function errMessage($errArrayVal,$cssClass)
{
	if (isset($errArrayVal)){
			  switch($errArrayVal){
				  case 'Required':
		            echo '<br /><span class="'.$cssClass.'">Mandatory.</span>';
					break;
				  case 'Format':
					echo '<br /><span class="'.$cssClass.'">Invalid format.</span>';
				    break;
				  case 'Max':
				    echo '<br /><span class="'.$cssClass.'">Exceeded maximum number of characters.</span>';
				    break;
				  case 'FileFormat':
				    echo '<br /><span class="'.$cssClass.'">Invalid Format.Only jpg/jpeg/png/bmp files allowed.</span>';
				    break;
				  case 'FileMaxLength':
				    echo '<br /><span class="'.$cssClass.'">File should not be larger then 2MB.</span>';
				    break;
				  case 'FileMinLength':
				    echo '<br /><span class="'.$cssClass.'">Mandatory.</span>';
				    break;

				}
	}
}
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
     /* if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }*/
   }
   return $isValid;
}

function isFloat($n){
    return ( $n == strval(floatval($n)) )? true : false;
}

function isNumber($n){
    return ( $n == strval(intval($n)) )? true : false;
}

function getSeqNextVal($con,$db,$seqName){

    $query = "SELECT NEXTVAL FROM ".$seqName;
    $RS = mysql_query($query) or die(mysql_error());
    $row_RS = mysql_fetch_assoc($RS);
	$nextVal = $row_RS['NEXTVAL'];
    $insertSQL = sprintf("UPDATE ".$seqName." SET NEXTVAL =".GetSQLValueString(($nextVal+1), "double")." Where NEXTVAL = ".$nextVal);
    $Result1 = mysql_query($insertSQL) or die(mysql_error());
	return $nextVal;
}
function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }
function fromDBDate($date){
	$dob1=trim($date);
	list($d, $d2) = explode(' ', $dob1);
	$d=trim($d);
	list($y,$m,$d) = explode('-',$d);
	$mk=mktime(0, 0, 0, $m, $d, $y);
	return date("Y-m-d",$mk);
}
function message($key){
		if($key)
		{
		  switch($key){
				case "PROPADD":
					  echo  '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property added/edited successfully.</div>';
				break;
				case "PROPADDPUBSKIP":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property added/edited successfully.But not published to website.</div>';
				break;
				case "PROPSOLD":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property sale flow completed successfully.</div>';
				break;
				case "PROPRENTED":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property rent flow completed successfully.</div>';
				break;
				case "VACATE1";
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property vacated and put on website.</div>';
				break;
				case "VACATE9":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property vacated but not put on website.</div>';
				break;
				case "PROPDELETE":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property deleted successfully.</div>';
				break;
				case "PROPWEBREMOVE":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property removed from website.This property now has DRAFT status</div>';
				break;
				case "PROPPUTONWEB":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Property put on website.</div>';
				break;
				case "PROPUNDOSALE":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Sale Undo Successfull.</div>';
				break;
				case "PROPSALECOMPLETED":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Sale Completed Successfull.</div>';
				break;
				case "USERADD":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />User Add\Edit Successfull.</div>';
				break;
				case "USEREXIST":
					 echo '<div class="actionfail"><img src="images/error.png" width="32" height="32" align="absmiddle" />Username already exist.User Add\Edit Failed.</div>';
				break;
				
				case "WELCOMEEDIT":
					 echo '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Welcome message saved.</div>';

			}
		}
		
}


	
function getBasket(){
	
	
	$sessionID = $_COOKIE['PHPSESSID'];
	
	$query  = "SELECT * FROM shortlist WHERE SESSION_ID = '".$sessionID."'" ;

	$result = mysql_query($query) or die(mysql_error());
	if($result)
	while($row = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$basketText = $basketText."<tr id=\"shopping_cart_items_product".$row['PROPERTY_ID']."\"><td style='width:auto'>".$row['DETAIL_DESCRIPTION']."</td><td style='width:15px'><a href=\"#\"  onclick=\"removeProductFromBasket('".$row['PROPERTY_ID']."');return false;\"><img src=\"images/remove.gif\"/></a></td></tr>";
	}

	return $basketText;
}
function sessionStart($role){
	if (!isset($_SESSION)) {
	  session_start();
	}
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
		// last request was more than 10 minates ago
		session_destroy();   // destroy session data in storage
		session_unset();     // unset $_SESSION variable for the runtime
		echo  "<script>window.location.href = 'adminlogin.php';</script>";
	}
	$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
	 if(strpos($_SESSION['MM_Roles'],$role) === false ){
		 header("Location: ". "adminaccessdenied.php"); 
		 exit;
	 }
	
}

function mainPhone($row){
	if($row['PREF_NO_DESCRIPTION'] == 'Home')
		echo $row['HOME_NO'];
	else if($row['PREF_NO_DESCRIPTION'] == 'Work')
		echo $row['WORK_NO'];
	else
		echo $row['MOB_NO'];
}

function normaliseText($text) {
	return 
		str_replace('n_n_n_n_',"\r\n",	
			str_replace('r_r_r_r_',"\r\n",	
				str_replace('r_n_r_n_r_n_', "\r\n",	
					preg_replace('/[\x00-\x1F\x80-\xFF]/',' ',
						preg_replace('/\n/','r_n_r_n_r_n_',
							preg_replace('/\r/','r_r_r_r_', 
									preg_replace('/\r\n/','n_n_n_n_',$text)
							)
						)
					)
				)
			)
		);	
}

?>