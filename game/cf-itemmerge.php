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
        $intMergeID = $doc->getElementsByTagName('intMergeID');
        $merge_id = $intMergeID->item(0)->nodeValue;

        $merges_result = mysql_query("SELECT * FROM df_merges WHERE ResultID = '".$merge_id."' LIMIT 1") or $error = 1;
        $merges = mysql_fetch_assoc($merges_result) or $error = 1;

        $item_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '".$merges['ResultID']."' LIMIT 1") or $error = 1;
        $item = mysql_fetch_assoc($item_result) or $error = 1;

        $query = mysql_query("SELECT * FROM df_equipment WHERE ItemID = '".$merges['RequiredID1']."' AND CharID = '".$char_id."' LIMIT '".$merges['RequiredQTY1']."'") or $error = 1;
        $q = mysql_fetch_assoc($query);
        $qr = mysql_num_rows($query);

        $query2 = mysql_query("SELECT * FROM df_equipment WHERE ItemID = '".$merges['RequiredID2']."' AND count >= '".$merges['RequiredQTY2']."' AND CharID = '".$char_id."' '".$merges['RequiredQTY2']."'") or $error = 1;
        $q2 = mysql_fetch_assoc($query2);
        $qr2 = mysql_num_rows($query2);

        if($qr < 0 || $qr2 < 0){
            $error = 1;
        }

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not merge items.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        } else {
            if($item['Currency'] == 2 && $error != 1) {
                $newgold = $char['gold'] - $item['Cost'];
                $takegold = mysql_query("UPDATE df_characters SET gold='". $newgold."' WHERE ID='". $char_id."' LIMIT 1") or $error = 1;
            } else if($item['Currency'] == 1 && $error != 1) {
                $newgold = $char['Coins'] - $item['Cost'];
                $takegold = mysql_query("UPDATE df_characters SET Coins='". $newgold."' WHERE ID='". $char_id."' LIMIT 1") or $error = 1;
            }
            if($error != 1) {
            $removeitem = mysql_query("DELETE FROM `df_equipment` WHERE `CharID` = ".$char_id." AND `ItemID` = '".$merges['RequiredID1']."' LIMIT '".$merges['RequiredQTY1']."'") or $error = 1;
            }
            if($error != 1) {
                $removeitem2 = mysql_query("DELETE FROM `df_equipment` WHERE `CharID` = ".$char_id." AND `ItemID` = '".$merges['RequiredID2']."' LIMIT '".$merges['RequiredQTY2']."'") or $error = 1;
            }
            if($error != 1) {
                $additem = mysql_query("INSERT INTO `df_equipment` (`CharItemID`, `CharID`, `ItemID`) VALUES ('', '".$char_id."', '".$merges['ResultID']."')") or $error = 1;
            }
            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Could not merge items.";

                $dom = new DOMDocument("1.0", "UTF-8");
                $XML = $dom->appendChild($dom->createElement('error'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $info = $XML->appendChild($dom->createElement('info'));
                $info->setAttribute("code", "526.14");
                $info->setAttribute("reason", $reason);
                $info->setAttribute("message", $message);
                $info->setAttribute("action", "None");
            } else {
                echo("<Merge CharItemID1='".$merges['RequiredID1']."' CharItemID2='".$merges['RequiredID2']."' Qty1='".$merges['RequiredQTY1']."' Qty2='".$merges['RequiredQTY2']."' NewItem='".$merges['ResultID']."'/>");

                $dom = new DOMDocument("1.0", "UTF-8");
                $XML = $dom->appendChild($dom->createElement('Merge'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $Merge = $XML->appendChild($dom->createElement('Merge'));
                $Merge->setAttribute("CharItemID1", $merges['RequiredID1']);
                $Merge->setAttribute("CharItemID2", $merges['RequiredID2']);
                $Merge->setAttribute("Qty1", $merges['RequiredQTY1']);
                $Merge->setAttribute("Qty2", $merges['RequiredQTY2']);
                $Merge->setAttribute("NewItem", $merges['ResultID']);

                $XML2 = $dom->appendChild($dom->createElement('success'));
                $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $XML2->setAttribute("status", "SUCCESS");
            }
        }
        $dom->formatOutput = true;
        echo $dom->saveXML();
    }
}
?>
