<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("View Viewing Requests");

// *** Validate request to login to this site.

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['form_login_username'])) {
  $loginUsername=$_POST['form_login_username'];
  $password=$_POST['form_login_password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "adminpropertysearch.php";
  $MM_redirectLoginFailed = "adminlogin.php";
  $MM_redirecttoReferrer = true;

  
  $LoginRS__query=sprintf("SELECT USERNAME, PASSWORD FROM `user` WHERE USERNAME=%s AND PASSWORD=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />

</head>

<body >
<?php include('top.php')?>
    <div >
   
   <div>
    <div class="phead">Admin Login</div>
    <div>
    <div><img src="images/error.png" width="32" height="32" align="absmiddle" /> <span class="label" style="color:#CC3333">Incorrect username or password.</span></div>
    <form action="<?php echo $loginFormAction; ?>" method="POST" name="form_login" id="form_login">

<?php echo $_GET['loginerror']; ?>
       <table align="center" class="form_table" cellpadding="5px">
          <tr valign="baseline">
               <td  align="right" width="200" class="label">Usernmae * :</td>
                <td><span id="sprytextfield1_form_login_username">
                  <input name="form_login_username" type="text" /><br />
                <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
            </tr>
          <tr valign="baseline">
               <td  align="right"  class="label">Password * :</td>
                <td><span id="sprytextfield2_form_login_password">
                  <input name="form_login_password" type="password">
                <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
           </tr>
    <tr valign="baseline">
      <td  align="right">&nbsp;</td>
      <td><input class="button"  type="submit"  value="Login" ></td>
    </tr>

       </table>
      
</form>
    </div>
    </div>
    </div>
<script type="text/javascript">
<!--

var sprytextfield1_form_login_username = new Spry.Widget.ValidationTextField("sprytextfield1_form_login_username");
var sprytextfield2_form_login_password = new Spry.Widget.ValidationTextField("sprytextfield2_form_login_password");
//-->
</script>
<?php include('bottom.php')?>
</body>
</html>