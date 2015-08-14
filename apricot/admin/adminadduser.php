<?php require_once('Connections/adestate.php'); ?>
<?php require_once('functions.php'); ?>
<?php
sessionStart("User Management");

if ((isset($_GET['DELETE'])) && ($_GET['DELETE'] == "DELETE") && (isset($_GET['USERNAME'])) && ($_GET['USERNAME'] != "")) {
  $deleteSQL = sprintf("DELETE FROM user WHERE USERNAME=%s",
                       GetSQLValueString($_GET['USERNAME'], "text"));


  $Result1 = mysql_query($deleteSQL) or die(mysql_error());
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_login")) {
$colname_userRS = $_POST['form_login_username'];

$query_userRS = sprintf("SELECT * FROM `user` WHERE USERNAME = %s ", GetSQLValueString($colname_userRS, "text"));
$userRS = mysql_query($query_userRS) or die(mysql_error());
$row_userRS = mysql_fetch_assoc($userRS);
$totalRows_userRS = mysql_num_rows($userRS);
			if($totalRows_userRS <= 0){
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
				  $_GET['MESSAGE'] = "USERADD";
			} else {
				$_GET['MESSAGE'] = "USEREXIST";
			}
}


$query_rolesRS = "SELECT * FROM role_master ORDER BY ROLE_ID ASC";
$rolesRS = mysql_query($query_rolesRS) or die(mysql_error());
$row_rolesRS = mysql_fetch_assoc($rolesRS);
$totalRows_rolesRS = mysql_num_rows($rolesRS);

$colname_userRS = "ADMIN";

$query_userRS = sprintf("SELECT * FROM `user` WHERE LOGIN_TYPE = %s ORDER BY USERNAME ASC", GetSQLValueString($colname_userRS, "text"));
$userRS = mysql_query($query_userRS) or die(mysql_error());
$row_userRS = mysql_fetch_assoc($userRS);
$totalRows_userRS = mysql_num_rows($userRS);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include('meta-title.php')?>
<link href="adminstyle.css" rel="stylesheet" type="text/css">
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<script>
function gotoUrl(url){
		window.location.href = url;
}

</script>


</head>

<body >
<?php include('top.php')?>
    <div >
   <div>
<br />
<br />
    <div class="phead">Existing Users</div>
    <br />
    <div>
      <?php if ($totalRows_userRS > 0) { // Show if recordset not empty ?>
<table  border="1px" bordercolor="#666666" cellpadding="0" cellspacing="0">
          <tr class="label">
            <td>Username</td>
            <td>Roles</td>
            <td style="width:100px">Actions</td>
        </tr>
          <?php do { ?>
            <tr>
              <td><?php echo $row_userRS['USERNAME']; ?></td>
              <td>
					<?php
                    $colname_userRoleRS = $row_userRS['USERNAME'];

                    $query_userRoleRS = sprintf("SELECT * FROM user_role_view WHERE USERNAME_RL = %s", GetSQLValueString($colname_userRoleRS, "text"));
                    $userRoleRS = mysql_query($query_userRoleRS) or die(mysql_error());
                    $row_userRoleRS = mysql_fetch_assoc($userRoleRS);
                    $totalRows_userRoleRS = mysql_num_rows($userRoleRS);
                    
                    ?>

				  <?php if ($totalRows_userRoleRS > 0) { // Show if recordset not empty ?>
          <?php do { ?>
                              <?php echo $row_userRoleRS['ROLE_DESCRIPTION']; ?>,<br />
                    <?php } while ($row_userRoleRS = mysql_fetch_assoc($userRoleRS)); ?>
                  <?php } // Show if recordset not empty ?>

              
              </td>
              <td>
              <input type="button" style="width:90px" class="buttonsmall" value="Edit" onclick="gotoUrl('adminedituser.php?USERNAME=<?php echo  $row_userRS['USERNAME'];?>')"/> <br />
              <input type="button" style="width:90px" class="buttonsmall" value="Delete" onclick="if(confirm('Are you sure you want to delete <?php echo $row_userRS['USERNAME']; ?>?')){ gotoUrl('adminadduser.php?USERNAME=<?php echo  $row_userRS['USERNAME'];?>&DELETE=DELETE')}"/> <br />

              </td>
            </tr>
            <?php } while ($row_userRS = mysql_fetch_assoc($userRS)); ?>
      </table>
        <?php } else {// Show if recordset not empty  ?>
        No result found.
        <?php } ?>
    </div>
</div>
     <div>
          <div class="phead" style="margin-top:30px">Add New User</div><br />
      <?php message($_GET['MESSAGE']);?>
<div>
<?php if ($_GET['MESSAGE'] != 'USERADD') {?>
  <form action="<?php echo $editFormAction; ?>" method="POST" name="form_login" id="form_login">
    
    
    <table align="center" class="form_table" cellpadding="5px">
      <tr valign="baseline">
        <td  align="right" width="200" class="label">Usernmae * :</td>
        <td><span id="sprytextfield1_form_login_username">
          <input name="form_login_username" type="text" value="<?php echo $_POST['form_login_username'] ?>"/><br />
          <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
        </tr>
      <tr valign="baseline">
        <td  align="right"  class="label">Password * :</td>
        <td><span id="sprytextfield2_form_login_password">
          <input id="form_login_password" name="form_login_password" type="password"><br />
          <span class="textfieldRequiredMsg">Mandatory.</span></span></td>
        </tr>
      <tr valign="baseline">
        <td  align="right" class="label">Confirm Password * :</td>
        <td><span id="spryconfirm1">
          <input id="form_login_passwordconfirm" name="form_login_passwordconfirm" type="password" /><br />
          <span class="confirmRequiredMsg">Mandatory.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
        </tr>
      <tr class="label">
        <td  valign="top">Roles * :</td>
        <td>
          
          <?php $i= 1; do {
			    
				
			  	$selected = "-1";
				if(isset($_POST['role']) && !empty($_POST['role']))
				foreach ($_POST['role'] as $rl)
				{
					if(strcmp($row_rolesRS['ROLE_ID'],$rl) == 0)
					{
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
            <?php  } while ($row_rolesRS = mysql_fetch_assoc($rolesRS)); ?>
          
          </td>
        </tr>
      
      
      <tr valign="baseline">
        <td  align="right">&nbsp;</td>
        <td><input class="button"  type="submit"  value="Submit" ></td>
        </tr>
      
      </table>
    <input type="hidden" name="MM_insert" value="form_login" />
  </form>
  <script type="text/javascript">
<!--

var sprytextfield1_form_login_username = new Spry.Widget.ValidationTextField("sprytextfield1_form_login_username");
var sprytextfield2_form_login_password = new Spry.Widget.ValidationTextField("sprytextfield2_form_login_password");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "form_login_password");
//-->
</script>

  <?php } ?>
</div>
    </div>
    </div>

<?php include('bottom.php')?>
</body>
</html>
<?php
mysql_free_result($rolesRS);

mysql_free_result($userRS);

mysql_free_result($userRoleRS);
?>