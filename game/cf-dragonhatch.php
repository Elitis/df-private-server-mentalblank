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
        $changevalue = mysql_query("UPDATE df_characters SET HasDragon='1' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $adddragon = mysql_query("INSERT INTO `df_dragons` (`id`, `CharDragID`, dateLastFed) VALUES ('', '".$char_id."', '".date('Y')."-".date('j')."-".date('d')."T".date('H').":".date('i').":".date('s').".".date('u')."')");

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not hatch dragon.";

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
            $XML = $dom->appendChild($dom->createElement('character'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $character = $XML->appendChild($dom->createElement('character'));
            if ($char['HasDragon'] == 1) {
                $dragon = mysql_query("SELECT * FROM df_dragons WHERE CharDragID = '" . $char_id . "' LIMIT 1") or $error = 1;
                $drag = mysql_fetch_assoc($dragon);
                $dragon = $character->appendChild($dom->createElement('dragon'));
                $dragon->setAttribute("strName", $drag['strName']);
                $dragon->setAttribute("intCrit", $drag['intCrit']);
                $dragon->setAttribute("intMin", $drag['intMin']);
                $dragon->setAttribute("intMax", $drag['intMax']);
                $dragon->setAttribute("strElement", $drag['strElement']);
                $dragon->setAttribute("intPowerBoost", $drag['intPowerBoost']);
                $dragon->setAttribute("dateLastFed", $drag['dateLastFed']);
                $dragon->setAttribute("intTotalStats", $drag['intTotalStats']);
                $dragon->setAttribute("intHeal", $drag['intHeal']);
                $dragon->setAttribute("intMagic", $drag['intMagic']);
                $dragon->setAttribute("intMelee", $drag['intMelee']);
                $dragon->setAttribute("intBuff", $drag['intBuff']);
                $dragon->setAttribute("intDebuff", $drag['intDebuff']);
            }
            $XML2 = $dom->appendChild($dom->createElement('success'));
            $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $XML2->setAttribute("status", "SUCCESS");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>