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
        $bankquery = mysql_query("SELECT * FROM df_bank WHERE CharID = '".$char_id."' LIMIT 1") or $error = 1;
        $bank_rows = mysql_num_rows($bankquery);

        if ($error != 1) {
            if($bank_rows >= 0) {
                while($bank = mysql_fetch_assoc($bankquery)){
                        $shop_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '".$bank['ItemID']."' LIMIT 1") or $error = 1;
                        $shop = mysql_fetch_assoc($shop_result);

                        $invent_result = mysql_query("SELECT * FROM df_bank WHERE ItemID = '".$bank['ItemID']."' AND CharID = '".$char_id."' LIMIT 1") or $error = 1;
                        $invent = mysql_fetch_assoc($invent_result);

                        $count_result = mysql_query("SELECT * FROM df_bank WHERE ItemID = '".$bank['ItemID']."' AND CharID = '".$char_id."'") or $error = 1;
                        $count = mysql_num_rows($count_result);

                        $dom = new DOMDocument("1.0", "UTF-8");
                        $XML = $dom->appendChild($dom->createElement('bank'));
                        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                        $bankitems = $XML->appendChild($dom->createElement('bank'));
                        $items = $bankitems->appendChild($dom->createElement('items'));
                        $items->setAttribute("ItemID", $shop_query['ItemID']);
                        $items->setAttribute("CharItemID", $invent["CharItemID"]);
                        $items->setAttribute("strItemName",  $shop["ItemName"]);
                        $items->setAttribute("intCount", $count);
                        $items->setAttribute("strItemDescription",  $shop["ItemDescription"]);
                        $items->setAttribute("bitVisible",  $shop["Visible"]);
                        $items->setAttribute("bitDestroyable",  $shop["Destroyable"]);
                        $items->setAttribute("bitSellable",  $shop["Sellable"]);
                        $items->setAttribute("bitDragonAmulet",  $shop["DragonAmulet"]);
                        $items->setAttribute("intCurrency",  $shop["Currency"]);
                        $items->setAttribute("intCost",  $shop["Cost"]);
                        $items->setAttribute("intMaxStackSize",  $shop["MaxStackSize"]);
                        $items->setAttribute("intBonus",  $shop["Bonus"]);
                        $items->setAttribute("intRarity",  $shop["Rarity"]);
                        $items->setAttribute("intLevel", $shop['Level']);
                        $items->setAttribute("intCharLevel", $invent['Level']);
                        $items->setAttribute("intExp", $invent['exp']);
                        $items->setAttribute("strType",  $shop["Type"]);
                        $items->setAttribute("strElement",  $shop["Element"]);
                        $items->setAttribute("strCategory",  $shop["Category"]);
                        $items->setAttribute("strEquipSpot",  $shop["EquipSpot"]);
                        $items->setAttribute("strItemType",  $shop["ItemType"]);
                        $items->setAttribute("strFileName",  $shop["FileName"]);
                        $items->setAttribute("strIcon",  $shop["Icon"]);
                        $items->setAttribute("intHP",  $shop["hp"]);
                        $items->setAttribute("intMaxHP",  $shop["hp"]);
                        $items->setAttribute("intMP",  $shop["mp"]);
                        $items->setAttribute("intMaxMP",  $shop["mp"]);
                        $items->setAttribute("bitEquipped", '0');
                        $items->setAttribute("bitDefault", '0');
                        $items->setAttribute("intStr",  $shop["intStr"]);
                        $items->setAttribute("intDex",  $shop["intDex"]);
                        $items->setAttribute("intInt",  $shop["intInt"]);
                        $items->setAttribute("intLuk",  $shop["intLuk"]);
                        $items->setAttribute("intCha",  $shop["intCha"]);
                        $items->setAttribute("intEnd",  $shop["intEnd"]);
                        $items->setAttribute("intMin",  $shop["Min"]);
                        $items->setAttribute("intMax",  $shop["Max"]);
                        $items->setAttribute("intDefMelee",  $shop["intDefMelee"]);
                        $items->setAttribute("intDefPierce",  $shop["intDefPierce"]);
                        $items->setAttribute("intDefMagic",  $shop["intDefMagic"]);
                        $items->setAttribute("intCrit",  $shop["intCrit"]);
                        $items->setAttribute("intParry",  $shop["intParry"]);
                        $items->setAttribute("intDodge",  $shop["intDodge"]);
                        $items->setAttribute("intBlock",  $shop["intBlock"]);
                        $items->setAttribute("strResists",  $shop["Resists"]);
                }
                $XML2 = $dom->appendChild($dom->createElement('success'));
                $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $XML2->setAttribute("status", "SUCCESS");
            } else {
                $reason = "Error!";
                $message = "Could not load bank items.";
                $dom = new DOMDocument("1.0", "UTF-8");
                $XML = $dom->appendChild($dom->createElement('error'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $info = $XML->appendChild($dom->createElement('info'));
                $info->setAttribute("code", "526.14");
                $info->setAttribute("reason", $reason);
                $info->setAttribute("message", $message);
                $info->setAttribute("action", "None");
            }
        } else {
            $reason = "Error!";
            $message = "Could not load bank items.";

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
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>