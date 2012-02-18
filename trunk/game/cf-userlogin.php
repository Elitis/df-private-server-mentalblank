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

session_start();
include ("../includes/config.php");
header("content-type: text/xml; charset=UTF-8", true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);
    
    $strUsername = $doc->getElementsByTagName('strUsername');
    $username = $strUsername->item(0)->nodeValue;

    $user_result = mysql_query("SELECT * FROM df_users WHERE name = '".$username."' LIMIT 1") or $error = 1;
    $userq = mysql_fetch_assoc($user_result);
    if(mysql_num_rows($user_result) != 1){
        $error = true;
        $reason = "User Not Found!";
        $message = "The username you typed was not found. Please check your spelling and try again.";
    } else {

    if($userq['access'] <= 0){
        $error = true;
        $reason = "Banned";
        $message = "You have been Banned from this Server, If you believe this is a mistake contact the Server Administrator.";
    } else {
        $salt = $userq['salt'];
        $strPassword = $doc->getElementsByTagName('strPassword');
        $password = md5(sha1($strPassword->item(0)->nodeValue, $salt));
        if($userq['pass'] != $password){
                $error = true;
                $reason = "Invalid Password!";
                $message = "The username and password you entered did not match. Please check the spelling and try again.";
        } else {
            $settings_query = mysql_query("SELECT * FROM df_settings LIMIT 1") or $error = 1;
            $news_result = mysql_fetch_assoc($settings_query);
            $news = $news_result['news'];

                $_SESSION['name'] = $username;
                if($data['access'] >=40){
                    $_SESSION['admin'] = "true";
                }
                $char_result = mysql_query("SELECT * FROM df_characters WHERE userid = ".$userq['id']." LIMIT 1") or $error = 1;

                $dom = new DOMDocument("1.0");
                $XML = $dom->appendChild($dom->createElement('characters'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $user = $XML->appendChild($dom->createElement('user'));
                $user->setAttribute("UserID", $userq['id']);
                $user->setAttribute("intCharsAllowed", $userq['chars_allowed']);
                $user->setAttribute("intAccessLevel", $userq['access']);
                $user->setAttribute("intUpgrade", $userq['upgrade']);
                $user->setAttribute("intActivationFlag", $userq['activation']);
                $user->setAttribute("strToken", $password);
                $user->setAttribute("strNews", $news);
                $user->setAttribute("bitAdFlag", $userq['AdFlag']);
                $user->setAttribute("strServer", "Private Server");
                $user->setAttribute("dateToday", date('Y')."-".date('j')."-".date('d')."T".date('H').":".date('i').":".date('s').".".date('u'));

                while ($char = mysql_fetch_array($char_result)) {
                    $class_result = mysql_query("SELECT * FROM df_class WHERE ClassID = '" . $char['classid'] . "' LIMIT 1");
                    $class = mysql_fetch_assoc($class_result);

                    $characters = $user->appendChild($dom->createElement('characters'));
                    $characters->setAttribute("CharID", $char['id']);
                    $characters->setAttribute("strCharacterName", $char['name']);
                    $characters->setAttribute("intLevel", $char['level']);
                    $characters->setAttribute("intDragonAmulet", $char['dragon_amulet']);
                    $characters->setAttribute("strRaceName", $char['race']);
                    $characters->setAttribute("strClassName", $class['ClassName']);
                }
        }
    }
    }
    if ($error) {
        $dom = new DOMDocument("1.0");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>