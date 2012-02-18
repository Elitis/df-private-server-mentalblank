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
header("content-type: text/xml; charset=UTF-8",true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $UserToken = $strToken->item(0)->nodeValue;

    $Food_ID = $doc->getElementsByTagName('intFoodID');
    $foodid = $Food_ID->item(0)->nodeValue;

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
        $item_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '".$foodid."' LIMIT 1") or $error = 1;
        $item = mysql_fetch_assoc($item_result) or $error = 1;
        $invent_result = mysql_query("SELECT * FROM df_equipment WHERE ItemID = '".$foodid."' AND CharID = '".$char_id."' LIMIT 1") or $error = 1;
        $invent = mysql_fetch_assoc($item_result) or $error = 1;
        $drag_result = mysql_query("SELECT * FROM df_dragons WHERE CharDragID = '".$char_id."' LIMIT 1") or $error = 1;
        $drag = mysql_fetch_assoc($drag_result);

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not feed dragon.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        } else {
            if($foodid == 879){
                //Normal Dragon Chow
                $newstats = $drag['intTotalStats'] + 1;
            } else if($foodid == 880) {
                //Special Dragon Chow
                $newstats = $drag['intTotalStats'] + 2;
            } else if($foodid == 881 || $foodid == 907) {
                //Really Special Dragon Chow
                $newstats = $drag['intTotalStats'] + 5;
            } else if($foodid == 3456) {
                //Super Special Dragon Chow
                $newstats = $drag['intTotalStats'] + 5;
            } else {
                $error = 1;
            }
            if($error != 1){
                $date = date('Y')."-".date('j')."-".date('d')."T".date('H').":".date('i').":".date('s').".".date('u');
                $changedate = mysql_query("UPDATE df_dragons SET dateLastFed='".$date."' WHERE CharDragID='".$char_id."' LIMIT 1") or $error = 1;
                $addpoints = mysql_query("UPDATE df_dragons SET intTotalStats='".$newstats."' WHERE CharDragID='".$char_id."' LIMIT 1") or $error = 1;
                $removeitem = mysql_query("DELETE FROM `df_equipment` WHERE `CharID` = ".$char_id." AND `ItemID` = ".$foodid." LIMIT 1") or $error = 1;
                if($invent['count'] == 1){
                    $removeitem = mysql_query("DELETE FROM `df_equipment` WHERE `CharID` = ".$char_id." AND `ItemID` = ".$foodid." LIMIT 1") or $error = 1;
                } else if($invent['count'] > 1){
                    $newcount = $invent['count'] - 1;
                    $addpoints = mysql_query("UPDATE df_equipment SET count='".$newcount."' WHERE CharDragID='".$char_id."' LIMIT 1") or $error = 1;
                } else {
                    $error = 1;
                }
                if($error != 1) {
                    $dom = new DOMDocument("1.0", "UTF-8");
                    $XML = $dom->appendChild($dom->createElement('success'));
                    $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                    $XML->setAttribute("status", "SUCCESS");
                    $dragon = $XML->appendChild($dom->createElement('dragon'));
                    $dragon->setAttribute("dateLastFed", $date);
                    $dragon->setAttribute("intTotalStats", $newstats);
                }
            }
            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Could not feed dragon.";

                $dom = new DOMDocument("1.0", "UTF-8");
                $XML = $dom->appendChild($dom->createElement('error'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $info = $XML->appendChild($dom->createElement('info'));
                $info->setAttribute("code", "526.14");
                $info->setAttribute("reason", $reason);
                $info->setAttribute("message", $message);
                $info->setAttribute("action", "None");
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>