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

    $intGold = $doc->getElementsByTagName('intGold');
    $gold = $intGold->item(0)->nodeValue;

    $intExp = $doc->getElementsByTagName('intExp');
    $exp = $intExp->item(0)->nodeValue;

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
        if($gold > $_SESSION['MaxQuestGold'] || $exp > $_SESSION['MaxQuestExp'] && $user['access'] < 40){
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
            $gold_total = $char['gold'] + $gold;
            $exp_total = $char['exp'] + $exp;

            $exptolevel = $char['level'] * 20 * $char['level'];
            $intLevel = $char['level'] + 1;
            $intHP = $char['hp'] + 20;
            $intMP = $char['mp'] + 5;
            $intStatPoints = $char['intStatPoints'] + 5;

            $addgold = mysql_query("UPDATE df_characters SET gold='" . $gold_total . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;

            if ($exp_total == $exptolevel){
                $exptolevel2 = $char['level'] * 20 * $char['level'];
                $exp_total2 = 0;
                $levelup = mysql_query("UPDATE df_characters SET level='" . $intLevel . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addstatpoints = mysql_query("UPDATE df_characters SET intStatPoints='" . $intStatPoints . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addhp = mysql_query("UPDATE df_characters SET hp='" . $intHP . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addmp = mysql_query("UPDATE df_characters SET mp='" . $intMP . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addexp = mysql_query("UPDATE df_characters SET exp='" . $exp_total2 . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
            } else if ($exp_total > $exptolevel){
                $exptolevel2 = $char['level'] * 20 * $char['level'];
                $exp_total2 = $exp_total - $exptolevel;
                $levelup = mysql_query("UPDATE df_characters SET level='" . $intLevel . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addstatpoints = mysql_query("UPDATE df_characters SET intStatPoints='" . $intStatPoints . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addhp = mysql_query("UPDATE df_characters SET hp='" . $intHP . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addmp = mysql_query("UPDATE df_characters SET mp='" . $intMP . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $addexp = mysql_query("UPDATE df_characters SET exp='" . $exp_total2 . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
            } else {
                $addexp = mysql_query("UPDATE df_characters SET exp='" . $exp_total . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
            }
            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Cannot save Earnt exp.";

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
                $XML = $dom->appendChild($dom->createElement('questreward'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $questreward = $XML->appendChild($dom->createElement('questreward'));
                $questreward->setAttribute("intEarnedExp", "0");
                $questreward->setAttribute("intEarnedGold", "0");
                $questreward->setAttribute("intEarnedGems", "0");
                $questreward->setAttribute("intEarnedSilver", "0");
                $questreward->setAttribute("intLevel", $intLevel);
                $questreward->setAttribute("intGold", $gold_total);
                $questreward->setAttribute("intHP", $intHP);
                $questreward->setAttribute("intMP", $intMP);
                $questreward->setAttribute("intExp", $exp_total2);
                $questreward->setAttribute("intSilver", "0");
                $questreward->setAttribute("intGems", "0");
                $questreward->setAttribute("intExpToLevel", $exptolevel2);

                $XML2 = $dom->appendChild($dom->createElement('success'));
                $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $XML2->setAttribute("status", "SUCCESS");
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>