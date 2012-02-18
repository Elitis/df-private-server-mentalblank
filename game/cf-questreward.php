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

    $intNewItemID = $doc->getElementsByTagName('intNewItemID');
    $item_id = $intNewItemID->item(0)->nodeValue;

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
        $item_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '" . $item_id . "' LIMIT 1") or $error = 1;
        $item = mysql_fetch_array($item_result);

        if($error == 1)
        {
            $reason = "Error!";
            $message = "Could not load Quest reward.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        } else {
            $query = mysql_query("SELECT * FROM df_equipment WHERE ItemID = '".$item['ItemID']."' AND CharID = '".$char_id."' AND count > '0' LIMIT 1") or $error = 1;
            $query_rows = mysql_num_rows($query);
            $query_fetched = mysql_fetch_assoc($query);

            $newcount = $query_fetched['count'] + 1;
            if($query_rows == 1) {
                if($item['MaxStackSize'] > 1) {
                    if($newcount < $item['MaxStackSize']) {
                        $query = mysql_query("UPDATE df_equipment SET count = '".$newcount."' WHERE id = '".$query_fetched['id']."' LIMIT 1") or $error = 1;
                    }
                } else {
                    $error = 1;
                }
            } else {
                $additem = mysql_query("INSERT INTO `df_equipment` (`id`, `CharID`, `ItemID`) VALUES ('', '".$char_id."', '".$item['ItemID']."')") or $error = 1;
            }

            if($error == 1)
            {
                $reason = "Error!";
                $message = "Could not Save Quest reward.";

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
                $XML = $dom->appendChild($dom->createElement('CharItemID'));
                $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $CharItemID = $XML->appendChild($dom->createElement('CharItemID'));
                $items = $CharItemID->appendChild($dom->createElement('CharItemID'));
                $items->setAttribute("ItemID", $shop_query['ItemID']);
                $items->setAttribute("strItemName",  $item["ItemName"]);
                $items->setAttribute("intCount",  '1');
                $items->setAttribute("strItemDescription",  $item["ItemDescription"]);
                $items->setAttribute("bitVisible",  $item["Visible"]);
                $items->setAttribute("bitDestroyable",  $item["Destroyable"]);
                $items->setAttribute("bitSellable",  $item["Sellable"]);
                $items->setAttribute("bitDragonAmulet",  $item["DragonAmulet"]);
                $items->setAttribute("intCurrency",  $item["Currency"]);
                $items->setAttribute("intCost",  $item["Cost"]);
                $items->setAttribute("intMaxStackSize",  $item["MaxStackSize"]);
                $items->setAttribute("intBonus",  $item["Bonus"]);
                $items->setAttribute("intRarity",  $item["Rarity"]);
                $items->setAttribute("intLevel",  $item["Level"]);
                $items->setAttribute("strType",  $item["Type"]);
                $items->setAttribute("strElement",  $item["Element"]);
                $items->setAttribute("strCategory",  $item["Category"]);
                $items->setAttribute("strEquipSpot",  $item["EquipSpot"]);
                $items->setAttribute("strItemType",  $item["ItemType"]);
                $items->setAttribute("strFileName",  $item["FileName"]);
                $items->setAttribute("strIcon",  $item["Icon"]);
                $items->setAttribute("intHP",  $item["hp"]);
                $items->setAttribute("intMaxHP",  $item["hp"]);
                $items->setAttribute("intMP",  $item["mp"]);
                $items->setAttribute("intMaxMP",  $item["mp"]);
                $items->setAttribute("bitEquipped", '0');
                $items->setAttribute("bitDefault", '0');
                $items->setAttribute("intStr",  $item["intStr"]);
                $items->setAttribute("intDex",  $item["intDex"]);
                $items->setAttribute("intInt",  $item["intInt"]);
                $items->setAttribute("intLuk",  $item["intLuk"]);
                $items->setAttribute("intCha",  $item["intCha"]);
                $items->setAttribute("intEnd",  $item["intEnd"]);
                $items->setAttribute("intMin",  $item["Min"]);
                $items->setAttribute("intMax",  $item["Max"]);
                $items->setAttribute("intDefMelee",  $item["intDefMelee"]);
                $items->setAttribute("intDefPierce",  $item["intDefPierce"]);
                $items->setAttribute("intDefMagic",  $item["intDefMagic"]);
                $items->setAttribute("intCrit",  $item["intCrit"]);
                $items->setAttribute("intParry",  $item["intParry"]);
                $items->setAttribute("intDodge",  $item["intDodge"]);
                $items->setAttribute("intBlock",  $item["intBlock"]);
                $items->setAttribute("strResists",  $item["Resists"]);
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>


