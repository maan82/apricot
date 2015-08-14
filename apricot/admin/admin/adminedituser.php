<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("User Management");
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_login")) {
  $deleteSQL = sprintf("DELETE FROM user WHERE USERNAME=%s",
                       GetSQLValueString($_POST['username_old'], "text"));


  $Result1 = mysql_query($deleteSQL) or die(mysql_error());
  $deleteSQL = sprintf("DELETE FROM user_role WHERE USERNAME_RL=%s",
                       GetSQLValueString($_POST['username_old'], "text"));


  $Result1 = mysql_query($deleteSQL) or die(mysql_error());
	foreach ($_POST['role'] as $rl)
		{
			  $insertSQL = sprintf("INSERT INTO user_role (USERNAME_RL, ROLE_ID_RL) VALUES (%s, %s)",
			   GetSQLValueString($_POST['form_login_username'], "text"),
			   GetSQLValueString($rl, "text"));

			  $Result1 = mysql_query($insertSQL) or die(mysql_error());

		}
	$USER_ID = getSeqNextVal( $mannestateDB, $database_mannestateDB, "sequence_user_id" );
	$insertSQL = sprintf("INSERT INTO user (USER_ID,USERNAME, PASSWORD, LOGIN_TYPE) VALUES (%s, %s, %s, %s)",
						   GetSQLValueString($USER_ID, "int"),
						   GetSQLValueString($_POST['form_login_username'], "text"),
						   GetSQLValueString($_POST['form_login_password'], "text"),
						   GetSQLValueString("ADMIN", "text"));
	

	  $Result1 = mysql_query($insertSQL) or die(mysql_error());
	  
	  if($_SESSION['MM_Username'] == $_POST['username_old']){
		  $_SESSION['RELOGIN'] = "RELOGIN";
		    $updateGoTo = $_SERVER['PHP_SELF']."?doLogout=true";
			header(sprintf("Location: %s", $updateGoTo));
		  
	  } else {
		    $updateGoTo = "adminadduser.php?MESSAGE=USERADD";
			header(sprintf("Location: %s", $updateGoTo));
	  }

} else {

$query_rolesRS = "SELECT * FROM role_master ORDER BY ROLE_ID ASC";
$rolesRS = mysql_query($query_rolesRS) or die(mysql_error());
$row_rolesRS = mysql_fetch_assoc($rolesRS);
$totalRows_rolesRS = mysql_num_rows($rolesRS);
	
	$colname_usrrolesRS = "-1";
	if (isset($_GET['USERNAME'])) {
	  $colname_usrrolesRS = $_GET['USERNAME'];
	  $_POST['form_login_username'] = $_GET['USERNAME'];
	  $_POST['username_old'] = $_GET['USERNAME'];
	}

	$query_usrrolesRS = sprintf("SELECT * FROM user_role_view WHERE USERNAME_RL = %s", GetSQLValueString($colname_usrrolesRS, "text"));
	$usrrolesRS = mysql_query($query_usrrolesRS) or die(mysql_error());
	$row_usrrolesRS = mysql_fetch_assoc($usrrolesRS);
	$totalRows_usrrolesRS = mysql_num_rows($usrrolesRS);

$colname_userRS = $colname_usrrolesRS;
if (isset($_GET['USERNAME'])) {
  $colname_userRS = $_GET['USERNAME'];
}

$query_userRS = sprintf("SELECT USERNAME, PASSWORD FROM `user` WHERE USERNAME = %s", GetSQLValueString($colname_userRS, "text"));
$userRS = mysql_query($query_userRS) or die(mysql_error());
$row_userRS = mysql_fetch_assoc($userRS);
$totalRows_userRS = mysql_num_rows($userRS);

		$roleArr = "_";
	
		if($totalRows_usrrolesRS > 0 ){
			do{
				$roleArr .= $row_usrrolesRS['ROLE_ID_RL'];
			}while ($row_usrrolesRS = mysql_fetch_assoc($usrrolesRS));
		}
	
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link  rel="stylesheet"  href="adminstyle.css" type="text/css" />
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
</head>

<body >
<?php include('top.php')?>
    <div >
     <div>
    <div class="phead">Edit User</div>
    <div>
    <form action="<?php echo $editFormAction; ?>" method="POST" name="form_login" id="form_login">

<input name="username_old" type="hidden" value="<?php echo $_POST['username_old'] ?>" />
       <table align="center" class="form_table" cellpadding="5px">
          <tr valign="baseline">
               <td  align="right" width="40%" class="label">Usernmae * :</td>
              <td><span id="sprytextfield1_form_login_username">
                  <input name="form_login_username" type="text" value="<?php echo $_POST['form_login_username'] ?>"/><br />
                <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
            </tr>
           <tr valign="baseline">
               <td  align="right" width="40%" class="label">Old Password  :</td>
              <td>
              <?php echo $row_userRS['PASSWORD']; ?>
              </td>
           </tr>
<tr>
<td colspan="2" class="label">
If you want to change password then type new password otherwise copy old password from above and paste it below.
</td>
</tr>
          <tr valign="baseline">
               <td  align="right" width="40%" class="label">Password * :</td>
              <td><span id="sprytextfield2_form_login_password">
                  <input id="form_login_password" name="form_login_password" type="password">
                <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
           </tr>
                     <tr valign="baseline">
         <td  align="right" width="40%" class="label">Confirm Password * :</td>
              <td><span id="spryconfirm1">
                <input id="form_login_passwordconfirm" name="form_login_passwordconfirm" type="password" />
                <span class="confirmRequiredMsg">Mandatory.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
           </tr>
           <tr class="label">
           <td  valign="top">Roles * :</td>
           <td>
             
          <?php $i= 1; do {
			  
			  	$selected = "-1";
				if(isset($_POST['role']) && !empty($_POST['role'])){
				foreach ($_POST['role'] as $rl)
				{
					if(strcmp($row_rolesRS['ROLE_ID'],$rl) == 0)
					{
						$selected =  0; 
					}
				}
				} else {
					if(strpos($roleArr,$row_rolesRS['ROLE_ID']) != false){
						$selected =  0; 
					}
				}
			  ?>
            <p>
              <label>
                <input <?php if ($selected == 0) {echo "checked=\"checked\"";} ?> type="checkbox" name="role[]" value="<?php echo $row_rolesRS['ROLE_ID']; ?>"  />
                <?php echo $row_rolesRS['ROLE_DESCRIPTION']; ?></label>
              <br />
              </p>
            <?php } while ($row_rolesRS = mysql_fetch_assoc($rolesRS)); ?>
          
                 
</td>
           </tr>
           

    <tr valign="baseline">
      <td  align="right">&nbsp;</td>
      <td><input class="button"  type="submit"  value="Submit" ></td>
    </tr>

       </table>
       <input type="hidden" name="MM_update" value="form_login" />
    </form>

    </div>
    </div>
   
    </div>
<script type="text/javascript">
<!--

var sprytextfield1_form_login_username = new Spry.Widget.ValidationTextField("sprytextfield1_form_login_username");
var sprytextfield2_form_login_password = new Spry.Widget.ValidationTextField("sprytextfield2_form_login_password");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "form_login_password");
//-->
</script>
<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($rolesRS);

mysql_free_result($rolesRS);

mysql_free_result($usrrolesRS);

mysql_free_result($userRS);
?>