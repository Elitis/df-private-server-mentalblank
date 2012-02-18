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

    $intTownID = $doc->getElementsByTagName('intTownID');
    $TownID = $intTownID->item(0)->nodeValue;

    $query = mysql_query("SELECT * FROM `df_towns` WHERE TownID = '".$TownID."' LIMIT 1") or $error = 1;
    $db_town = mysql_fetch_assoc($query) or $error = 1;
    $town_rows = mysql_num_rows($query);

    $user_query = mysql_query("SELECT access FROM `df_users` WHERE name = '".$_SESSION['name']."' LIMIT 1") or $error = 1;
    $user = mysql_fetch_assoc($user_query);

    if($db_town['admin'] == '1' && $user['access'] < 40){
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
    } else {
        $db_TownID = $db_town['TownID'];
        $db_strQuestFileName = $db_town['strQuestFileName'];
        $db_strQuestXFileName = $db_town['strQuestXFileName'];

        if($db_town['strExtra'] != "none") {
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
        if($town_rows < 1){ $error = 1; }

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
            $newTown->setAttribute("intTownID", $db_TownID);
            $newTown->setAttribute("strQuestFileName", $db_strQuestFileName);
            $newTown->setAttribute("strQuestXFileName", $db_strQuestXFileName);
            $newTown->setAttribute("strExtra", $zones);
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>
