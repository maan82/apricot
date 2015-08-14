<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
if (!isset($_SESSION)) {
	  session_start();
	}
$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['form_login_username'])) {
  $loginUsername=$_POST['form_login_username'];
  $password=$_POST['form_login_password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "adminpropertysearch.php";
  $MM_redirectLoginFailed = "adminloginfail.php";
  $MM_redirecttoReferrer = true;

  
  $LoginRS__query=sprintf("SELECT USERNAME, PASSWORD FROM `user` WHERE USERNAME=%s AND PASSWORD=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query) or die(mysql_error());
  $row_LoginRS = mysql_fetch_assoc($LoginRS );
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    $colname_roleRS = $row_LoginRS['USERNAME'];

	$query_roleRS = sprintf("SELECT * FROM user_role_view WHERE USERNAME_RL = %s", GetSQLValueString($colname_roleRS, "text"));
	$roleRS = mysql_query($query_roleRS) or die(mysql_error());
	$row_roleRS = mysql_fetch_assoc($roleRS);
	$totalRows_roleRS = mysql_num_rows($roleRS);
	$roleArr = "_";

	if($totalRows_roleRS > 0 ){
		do{
			$roleArr .= $row_roleRS['ROLE_DESCRIPTION'];
		}while ($row_roleRS = mysql_fetch_assoc($roleRS));
	}
	$_SESSION['MM_Roles'] = $roleArr;	      
   // header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
   // header("Location: ". $MM_redirectLoginFailed );
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
<meta content="width=device-width" name="viewport">
<link href="small-device.css" media="only screen and (max-device-width: 480px)" type="text/css" rel="stylesheet">
</head>

<body class="admin_login_main">
<?php include('top.php')?>

   <div>
    <div class="phead">Admin Login</div>
    <div>
    		 <?php if($_SESSION['RELOGIN'] == "RELOGIN") {echo  '<div class="actionsuccess"><img src="images/ok.png" width="32" height="32" align="absmiddle" />Your account setting has been changed.Please login again .</div>' ; unset($_SESSION['RELOGIN']);}?>
    <?php if(isset($_SESSION['MM_Username'])){ ?>
    <div style="height:300px" class="label">Loged in as <?php echo $_SESSION['MM_Username'];  ?></div>

        <?php }else{?>
    <form action="<?php echo $loginFormAction; ?>" method="POST" name="form_login" id="form_login">


       <table align="center" class="form_table" cellpadding="5px" >
          <tr valign="baseline">
               <td  align="right" width="100" class="label">Usernmae * :</td>
              <td><span id="sprytextfield1_form_login_username"><span id="sprytextfield1">
                <input name="form_login_username" type="text" />
                <span class="textfieldRequiredMsg">A value is required.</span></span><br />
                <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
            </tr>
          <tr valign="baseline">
               <td  align="right" width="100" class="label">Password * :</td>
              <td><span id="sprytextfield2_form_login_password"><span id="sprytextfield2">
                <input name="form_login_password" type="password" />
                <span class="textfieldRequiredMsg">A value is required.</span></span><span class="textfieldRequiredMsg">Mandatory.</span></span></td>
           </tr>
    <tr valign="baseline">
      <td  align="right">&nbsp;</td>
      <td><input class="button"  type="submit"  value="Login" >
      
      </td>
    </tr>

       </table>
      
</form>



    <?php }?>
    </div>
    </div>
<?php include('bottom.php')?>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
//-->
</script>
</body>
</html>