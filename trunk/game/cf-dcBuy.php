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

    $intBuyID = $doc->getElementsByTagName('intBuyID');
    $BuyID = $intBuyID->item(0)->nodeValue;

    $strCommand = $doc->getElementsByTagName('strCommand');
    $Command = $strCommand->item(0)->nodeValue;

    $intAction = $doc->getElementsByTagName('intAction');
    $Action = $intAction->item(0)->nodeValue;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE id = '".$char_id."' LIMIT 1") or $error = 1;
    $char = mysql_fetch_assoc($char_result);
    $user_result = mysql_query("SELECT * FROM df_users WHERE id = '".$char['userid']."' AND pass = '".$UserToken."' LIMIT 1") or $error = 1;
    $user = mysql_num_rows($user_result);

    if($user != 1 || $error == 1){
            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
    } else {
        if($BuyID == 0){
            if($char['Coins'] >= 1000){
                $newcoins = $char['Coins'] - 1000;
                if($Command == "M"){
                    $ChangeGender = mysql_query("UPDATE df_characters SET gender='M' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeHair = mysql_query("UPDATE df_characters SET hairid='3' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeCoins = mysql_query("UPDATE df_characters SET Coins='".$newcoins."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                } else if($Command == "F"){
                    $ChangeGender = mysql_query("UPDATE df_characters SET gender='F' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeHair = mysql_query("UPDATE df_characters SET hairid='3' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeCoins = mysql_query("UPDATE df_characters SET Coins='".$newcoins."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else if($BuyID == 1){
            if($char['Coins'] >= 1000){
                $newcoins = $char['Coins'] - 1000;
                $ChangeGender = mysql_query("UPDATE df_characters SET name='".$Command."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                $ChangeCoins = mysql_query("UPDATE df_characters SET Coins='".$newcoins."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
            } else {
                $error = 1;
            }
        } else if($BuyID == 2){
            if($char['Coins'] >= 500){
                $newcoins = $char['Coins'] - 1000;
                if($Action == "2"){
                    $ChangeClass = mysql_query("UPDATE df_characters SET classid='".$Action."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeCoins = mysql_query("UPDATE df_characters SET Coins='".$newcoins."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                } else if($Action == "3"){
                    $ChangeClass = mysql_query("UPDATE df_characters SET classid='".$Action."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeCoins = mysql_query("UPDATE df_characters SET Coins='".$newcoins."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                } else if($Action == "4"){
                    $ChangeClass = mysql_query("UPDATE df_characters SET classid='".$Action."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                    $ChangeCoins = mysql_query("UPDATE df_characters SET Coins='".$newcoins."' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                } else {
                    $error = 1;
                }
            } else {
                $error = 1;
            }
        } else {
            $reason = "Error!";
            $message = "Cannot Process Transaction.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        }

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Cannot Process Transaction.";

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
            $XML2 = $dom->appendChild($dom->createElement('success'));
            $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $XML2->setAttribute("status", "SUCCESS");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>