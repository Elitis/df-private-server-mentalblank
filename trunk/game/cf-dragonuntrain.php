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
    $drag_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $UserToken = $strToken->item(0)->nodeValue;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE ID = '" . $drag_id . "' LIMIT 1") or $error = 1;
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
        $drag_result = mysql_query("SELECT * FROM df_dragons WHERE CharDragID = '" . $drag_id . "' LIMIT 1") or $error = 1;
        $drag = mysql_fetch_assoc($drag_result);

        if ($error == 1 || $char['gold'] < 1000)
        {
            $reason = "Error!";
            $message = "Could not untrain dragon.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        } else {
            $addHeal = mysql_query("UPDATE df_dragons SET intHeal='0' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
            $addMagic = mysql_query("UPDATE df_dragons SET intMagic='0' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
            $addMelee = mysql_query("UPDATE df_dragons SET intMelee='0' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
            $addBuff = mysql_query("UPDATE df_dragons SET intBuff='0' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
            $addDebuff = mysql_query("UPDATE df_dragons SET intDebuff='0' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
            $gold_left = $char['gold'] - 1000;
            $ChangeGold = mysql_query("UPDATE df_characters SET gold='".$gold_left."' WHERE ID='".$drag_id."' LIMIT 1") or $error = 1;

            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Could not untrain dragon.";

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
                $XML = $dom->appendChild($dom->createElement('success'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $XML->setAttribute("status", "SUCCESS");
                $dragon = $XML->appendChild($dom->createElement('dragon'));
                $dragon->setAttribute("intHeal", "0");
                $dragon->setAttribute("intMagic", "0");
                $dragon->setAttribute("intMelee", "0");
                $dragon->setAttribute("intBuff", "0");
                $dragon->setAttribute("intDebuff", "0");
            }
        }
        $dom->formatOutput = true;
        echo $dom->saveXML();
    }
}
?>