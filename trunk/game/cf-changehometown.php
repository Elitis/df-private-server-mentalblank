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

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $UserToken = $strToken->item(0)->nodeValue;

    $intTownID = $doc->getElementsByTagName('intTownID');
    $TownID = $intTownID->item(0)->nodeValue;

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
        $dom->formatOutput = true;
        echo $dom->saveXML();
        die();
    }

    $query = mysql_query("SELECT * FROM `df_towns` WHERE TownID = '".$TownID."' LIMIT 1") or $error = 1;
    $db_town = mysql_fetch_assoc($query) or $error = 1;

    $user = mysql_fetch_assoc($user_result);
    if($db_town['admin'] == 1){
        if($user['access'] < 40){
            $reason = "Banned!";
            $message = "You have been banned for trying to access an admin town, If this is incorrect please contact the Server Administrator";

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
    }
    $addexp = mysql_query("UPDATE df_characters SET HomeTownID='" . $TownID . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;

    if($town['strExtra'] != "none") {
        //This Grabs Zone Data
        $zones = "";
        //Converts Extra string into Array
        $replaced = str_replace(",", " OR ZoneID = ", $db_town['strExtra']);
        $query = mysql_query("SELECT * FROM df_extra WHERE ZoneID = ".$replaced."");
        //Gets Data for Individual Zones
        while($zq = mysql_fetch_assoc($query)){
            $zones2 = mysql_fetch_assoc(mysql_query("SELECT * FROM df_extra WHERE ZoneID = '".$zq['ZoneID']."' LIMIT 1"));
            if($zones != ""){
                if($zones2['Name']=="none"){
                    $zones = $zones.$zones2['Data']."\n";
                } else {
                    $zones = $zones.$zones2['Name']."=".$zones2['Data']."\n";
                }
            } else {
                if($zones2['Name']=="none"){
                    $zones = $zones2['Data']."\n";
                } else {
                    $zones = $zones2['Name']."=".$zones2['Data']."\n";
                }
            }
        }
    } else {
        $zones = "none";
    }

    if ($error == 1) {
        $reason = "Error!";
        $message = "Could not load town information.";

        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('newTown'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $newTown = $XML->appendChild($dom->createElement('newTown'));
        $newTown->setAttribute("intTownID", $db_town['TownID']);
        $newTown->setAttribute("strQuestFileName", $db_town['strQuestFileName']);
        $newTown->setAttribute("strQuestXFileName", $db_town['strQuestXFileName']);
        $newTown->setAttribute("strExtra", $zones);
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>

