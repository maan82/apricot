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
		            echo '<span class="'.$cssClass.'">Mandatory.</span>';
					break;
				  case 'Format':
					echo '<span class="'.$cssClass.'">Invalid format.</span>';
				    break;
				  case 'Max':
				    echo '<span class="'.$cssClass.'">Exceeded maximum number of characters.</span>';
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
function getSeqNextVal($con,$db,$seqName){

    $query = "SELECT NEXTVAL FROM ".$seqName;
    $RS = mysql_query($query) or die(mysql_error());
    $row_RS = mysql_fetch_assoc($RS);
	$nextVal = $row_RS['NEXTVAL'];
    $insertSQL = sprintf("UPDATE ".$seqName." SET NEXTVAL =".GetSQLValueString(($nextVal+1), "double")." Where NEXTVAL = ".$nextVal);
    $Result1 = mysql_query($insertSQL) or die(mysql_error());
	return $nextVal;
}
?>
