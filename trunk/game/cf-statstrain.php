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

    $intSTR = $doc->getElementsByTagName('intSTR');
    $STR = $intSTR->item(0)->nodeValue;

    $intINT = $doc->getElementsByTagName('intINT');
    $INT = $intINT->item(0)->nodeValue;

    $intDEX = $doc->getElementsByTagName('intDEX');
    $DEX = $intDEX->item(0)->nodeValue;

    $intEND = $doc->getElementsByTagName('intEND');
    $END = $intEND->item(0)->nodeValue;

    $intLUK = $doc->getElementsByTagName('intLUK');
    $LUK = $intLUK->item(0)->nodeValue;

    $intCHA = $doc->getElementsByTagName('intCHA');
    $CHA = $intCHA->item(0)->nodeValue;

    $intWIS = $doc->getElementsByTagName('intWIS');
    $WIS = $intWIS->item(0)->nodeValue;

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
        $newSTR = $char['intSTR'] + $STR;
        $newINT = $char['intINT'] + $INT;
        $newDEX = $char['intDEX'] + $DEX;
        $newEND = $char['intEND'] + $END;
        $newLUK = $char['intLUK'] + $LUK;
        $newCHA = $char['intCHA'] + $CHA;
        $newWIS = $char['intWIS'] + $WIS;

        $points_used = $STR + $INT + $DEX + $END + $LUK + $CHA + $WIS;
        $points_left = $char['intStatPoints'] - $points_used;

        $cost = $points_used * 5;
        $gold_left = $char['gold'] - $cost;

        $addSTR = mysql_query("UPDATE df_characters SET intSTR='" . $newSTR . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $addINT = mysql_query("UPDATE df_characters SET intINT='" . $newINT . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $addDEX = mysql_query("UPDATE df_characters SET intDEX='" . $newDEX . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $addEND = mysql_query("UPDATE df_characters SET intEND='" . $newEND . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $addLUK = mysql_query("UPDATE df_characters SET intLUK='" . $newLUK . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $addCHA = mysql_query("UPDATE df_characters SET intCHA='" . $newCHA . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $addWIS = mysql_query("UPDATE df_characters SET intWIS='" . $newWIS . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $ChangePoints = mysql_query("UPDATE df_characters SET intStatPoints='" . $points_left . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
        $ChangeGold = mysql_query("UPDATE df_characters SET gold='" . $gold_left . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not train stats.";

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
            $character->setAttribute("intStatPoints", $points_left);

            $XML2 = $dom->appendChild($dom->createElement('success'));
            $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $XML2->setAttribute("status", "SUCCESS");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>