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

//TODO: ADD SECURITY FOR ADMIN ITEMS AND MAX STACK SIZE

include ("../includes/config.php");
header("content-type: text/xml; charset=UTF-8",true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;

    $intCharItemID = $doc->getElementsByTagName('intCharItemID');
    $item_id = $intCharItemID->item(0)->nodeValue;

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

        $item_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '".$item_id."' LIMIT 1") or $error = 1;
        $item = mysql_fetch_assoc($item_result) or $error = 1;

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not purchase item.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        } else {
            $query2 = mysql_query("SELECT * FROM df_bank WHERE ItemID = '".$item['ItemID']."' AND CharID = '".$char_id."'") or $error = 1;
            $query2_rows = mysql_num_rows($query2);
            if($query2_rows > 0) {
                while($query2_fetched = mysql_fetch_assoc($query2)){
                    $additem = mysql_query("INSERT INTO `df_equipment` (`CharItemID`, `CharID`, `ItemID`, `Level`, `Exp` ) VALUES ('$item_id', '".$char_id."', '".$item_id."', '".$query2_fetched['Level']."', '".$query2_fetched['Exp']."')") or $error = 1;
                    $removeitem = mysql_query("DELETE FROM `df_bank` WHERE `CharID` = ".$char_id." AND `ItemID` = ".$item_id." LIMIT 1") or $error = 1;
                }
            }

            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Could not add item to inventory.".  mysql_error();

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
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>