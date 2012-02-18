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
header("content-type: text/xml; charset=UTF-8",true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $UserToken = $strToken->item(0)->nodeValue;

    $intClassID = $doc->getElementsByTagName('intClassID');
    $class_id = $intClassID->item(0)->nodeValue;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE id = '".$char_id."' LIMIT 1") or $error = 1;
    $char = mysql_fetch_assoc($char_result);
    $user_result = mysql_query("SELECT * FROM df_users WHERE id = '".$char['userid']."' AND pass = '".$UserToken."' LIMIT 1") or $error = 1;
    $user = mysql_num_rows($user_result);

    if($user != 1 || $error == 1){
        $reason = "Error!";
        $message = "There was an issue with your account... Please Login and try again";

        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        $class_result = mysql_query("SELECT * FROM df_class WHERE ClassID = '" . $class_id . "' LIMIT 1") or $error = 1;
        $class = mysql_fetch_assoc($class_result) or $error = 1;

        if($class['Save'] == 1 && $error != 1){
            $prevclass_change = mysql_query("UPDATE df_characters SET PrevClassID ='" . $char['classid'] . "'  WHERE id = '" . $char_id . "' LIMIT 1") or $error = 1;
            $class_change = mysql_query("UPDATE df_characters SET classid ='" . $class_id . "'  WHERE id = '" . $char_id . "' LIMIT 1") or $error = 1;
        } else {
            if($class['Save'] == 1 && $user['access'] < 40){
                $reason = "Banned!";
                $message = "You have been banned for trying to access an admin town, If this is incorrect please contact the Server Administrator.";

                $dom = new DOMDocument("1.0", "UTF-8");
                $XML = $dom->appendChild($dom->createElement('error'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $info = $XML->appendChild($dom->createElement('info'));
                $info->setAttribute("code", "526.14");
                $info->setAttribute("reason", $reason);
                $info->setAttribute("message", $message);
                $info->setAttribute("action", "None");
                $Ban_User = mysql_query("UPDATE df_users SET access ='0' WHERE name='".$_SESSION['name']."' LIMIT 1") or $error = 1;
                $dom->formatOutput = true;
                echo $dom->saveXML();
                die();
            }
            $reason = "Error!";
            $message = "Cannot load Class.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        }
        if ($error != 1)
        {
            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('character'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $character = $XML->appendChild($dom->createElement('character'));
            $character->setAttribute("BaseClassID", $class_id);
            $character->setAttribute("ClassID", $class_id);
            $character->setAttribute("strClassName", $class['ClassName']);
            $character->setAttribute("strClassFileName", $class['ClassSWF']);
            $character->setAttribute("strArmorName", $class['ArmorName']);
            $character->setAttribute("strArmorDescription", $class['ArmorDescription']);
            $character->setAttribute("strArmorResists", $class['ArmorResists']);
            $character->setAttribute("intDefMelee", $class['DefMelee']);
            $character->setAttribute("intDefPierce", $class['DefPierce']);
            $character->setAttribute("intDefMagic", $class['DefMagic']);
            $character->setAttribute("intParry", $class['Parry']);
            $character->setAttribute("intDodge", $class['Dodge']);
            $character->setAttribute("intBlock", $class['Block']);
            $character->setAttribute("strWeaponName", $class['WeaponName']);
            $character->setAttribute("strWeaponDescription", $class['WeaponDescription']);
            $character->setAttribute("strWeaponDesignInfo", $class['WeaponDesignInfo']);
            $character->setAttribute("strWeaponResists", $class['WeaponResists']);
            $character->setAttribute("intWeaponLevel", $class['WeaponLevel']);
            $character->setAttribute("strWeaponIcon", $class['WeaponIcon']);
            $character->setAttribute("strType", $class['Type']);
            $character->setAttribute("strItemType", $class['ItemType']);
            $character->setAttribute("intCrit", $class['Crit']);
            $character->setAttribute("intDmgMin", $class['DmgMin']);
            $character->setAttribute("intDmgMax", $class['DmgMax']);
            $character->setAttribute("intBonus", $class['Bonus']);
            $character->setAttribute("strElement", $class['Element']);

            $XML2 = $dom->appendChild($dom->createElement('success'));
            $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $XML2->setAttribute("status", "SUCCESS");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>
