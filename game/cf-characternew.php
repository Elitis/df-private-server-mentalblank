<?php
/*
 * DragonFable Private Server Files
 * Created By MentalBlank (mentalblank@live.com)
 * Layout by: HellFireAE
 *
 * -- IMPORTANT INFORMATIONS --
 * AT THE CURRENT TIME THESE FILES ARE IN DEVELOPMENT
 * AND ARE NOT CURRENTLY COMPLETE OR 100% SECURE
 *
 * THESE FILES WERE CREATED FOR EDUCATIONAL PURPOSES ONLY
 * I DO NOT SUPPORT ANY SERVERS CREATED FROM THESE FILES
*/

include ("../includes/config.php");

$username = str_replace("%0D", "", mysql_real_escape_string(stripslashes($_POST["strUsername"])));
$userid = mysql_real_escape_string(stripslashes($_POST['intUserID']));
$salt_query = mysql_query("SELECT salt FROM df_users WHERE id='".$userid."' LIMIT 1") or $error = 1;
$salt_result = mysql_fetch_assoc($salt_query);
$salt = $salt_result['salt'];
$password = md5(sha1($_POST["strPassword"], $salt));

$character = mysql_real_escape_string(stripslashes($_POST["strCharacterName"]));
$gender = mysql_real_escape_string(stripslashes($_POST["strGender"]));
$hairid = mysql_real_escape_string(stripslashes($_POST["intHairID"]));
$haircolor = mysql_real_escape_string(stripslashes($_POST["intColorHair"]));
$skincolor = mysql_real_escape_string(stripslashes($_POST["intColorSkin"]));
$colorbase = mysql_real_escape_string(stripslashes($_POST["intColorBase"]));
$colortrim = mysql_real_escape_string(stripslashes($_POST["intColorTrim"]));
$classid = mysql_real_escape_string(stripslashes($_POST["intClassID"]));
$raceid = mysql_real_escape_string(stripslashes($_POST["intRaceID"]));
$hairframe = mysql_real_escape_string(stripslashes($_POST["intHairFrame"]));
$today = date('Y')."-".date('j')."-".date('d');
$error = 0;

if($character == "" || $gender == ""){
       	$status = "Failure";
       	$message = "Something went Wrong.";
       	$reason = "Error";
       	$errorMessage = "Error.";

	echo "<error>";
	echo "<result_lv code=\"526.14\" reason=\"" . $reason . "\" message=\"" . $message . "\" action=\"None\"/>";
	echo "</error>";
	echo "&code=526.14&reason=$reason&message=$message&action=none";
} else{
	$user = mysql_query("SELECT * FROM df_users WHERE name='".$username."' AND pass='".$password."' LIMIT 1") or $error = 1;
	$user_result = mysql_fetch_assoc($user);
	$char = mysql_query("SELECT * FROM df_characters WHERE name='".$character."' AND userid = '" . $userid . "' LIMIT 1") or $error = 1;

	if($error == 1){
		$error = 1;
		$reason = "Error!";
		$message = "Issue with database information, please contact server staff.";
	} else {
		if(mysql_num_rows($user) == 0){
			$error = 1;
			$reason = "Error!";
			$message = "User Does Not Exist.";
		}
		if($password != $user_result['pass']){
			$error = 1;
			$reason = "Error!";
			$message = "Something went wrong with your account password.";
		}
		if(empty($character)){
			$error = 1;
			$reason = "Error!";
			$message = "You already have a character with this name.";
		}
		if(mysql_num_rows($char) > 0){
			$error = 1;
			$reason = "Error!";
			$message = "Character Already Exists.";
		}
	}

	if($error != 1){
		$insert = mysql_query("INSERT INTO df_characters(userid, name, gender, race, born, hairid, colorhair, colorskin, colorbase, colortrim, classid, BaseClassID, PrevClassID, raceid, hairframe) VALUES('" . $userid . "', '" . $character . "', '" . $gender . "', 'Human', '".$today."', '" . $hairid . "', '" . $haircolor . "', '" . $skincolor . "', '" . $colorbase . "', '" . $colortrim . "', '" . $classid . "', '" . $classid . "', '" . $classid . "', '" . $raceid . "', '" . $hairframe . "')");
		$reason = "WIN!";
		$message = "Character Successfully created.";
		echo "&code=0&reason=$reason&message=$message&action=none";
	} else {
		echo "&code=526.14&reason=$reason&message=$message&action=none";
	}
}
?>