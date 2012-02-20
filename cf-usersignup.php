<?php
/*
CREATED BY MENTALBLANK
http://cris-is.stylin-on.me
Contact me at: mentalblank@live.com
*/

include ("includes/config.php");

$username = mysql_real_escape_string(stripslashes($_POST['strUserName']));
$salt = rand(1000, 10000);
$password = md5(sha1($_POST['strPassword'], $salt));

$email = mysql_real_escape_string(stripslashes($_POST['strEmail']));
$dob = mysql_real_escape_string(stripslashes($_POST['strDOB']));
$user_result = mysql_query("SELECT * FROM df_users WHERE name = '".$username."'") or $error = true;

if($error == true){
	$status = "Failure";
    $message = "Database Issue.";
    $reason = "Unable to communicate with database properly";
    $errorMessage = "No error.";
	$error = true;
} else if($username == "" || $password == "" || $email == "" || $dob == ""){
       	$status = "Failure";
       	$message = "Something went Wrong.";
       	$reason = "Error";
       	$errorMessage = "Error.";
       	$error = true;
} else {
	if (mysql_num_rows($user_result) == 0) {
		$user_insert = mysql_query("INSERT INTO df_users (name, pass, salt, email, dob) VALUES ('".$username."', '".$password."', '".$salt."', '".$email."', '".$dob."')") or $error = true;
		$user_result = mysql_query("SELECT * FROM df_users WHERE name = '".$username."'") or $error = true;
		$user = mysql_fetch_assoc($user_result);
		if($error == true){
			$status = "Failure";
       		$message = "Database Issue.";
       		$reason = "Could not add user to database";
       		$errorMessage = "No error.";
		} else {
       		echo("&ID=" . $user['id']);
			$status = "Success";
			$message = "Account Created Successfully";
		}
	} else {
       		$status = "Failure";
       		$message = "Your username is already taken by another user account.";
       		$reason = "Username is taken";
       		$errorMessage = "No error.";
       		$error = true;
	}
}
echo("&status=" . $status);
echo("&strMsg=" . $message);

if ($error) {
	echo("&strReason=" . $reason);
	echo("&strErr=" . $errorMessage);
}

echo("&strButtonName=Continue");
?>
