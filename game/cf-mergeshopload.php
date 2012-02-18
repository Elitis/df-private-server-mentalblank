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

    $intMergeShopID = $doc->getElementsByTagName('intMergeShopID');
    $MergeShop_id = $intMergeShopID->item(0)->nodeValue;

    $vendor_result = mysql_query("SELECT * FROM df_vendors WHERE ShopID = 'none' and MergeShopID = '".$MergeShop_id."' LIMIT 1") or $error = 1;
    $vendor = mysql_fetch_assoc($vendor_result) or $error = 1;
    $shop_result = mysql_query("SELECT * FROM df_items WHERE MergeShopID = '" . $MergeShop_id . "' LIMIT 1") or $error = 1;

    $dom = new DOMDocument("1.0", "UTF-8");
    $XML = $dom->appendChild($dom->createElement('mergeshop'));
    $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
    $shop = $XML->appendChild($dom->createElement('mergeshop'));
    $shop->setAttribute("MergeShopID", $MergeShop_id);
    if($error == 1)
    {
        $shop->setAttribute("strCharacterName", "Empty Shop");
    } else {
        if($vendor['admin'] != 1)
        {
            $shop->setAttribute("strCharacterName", $vendor['ShopName']);
            while ($shop_query = mysql_fetch_array($shop_result))
            {
                $merges_result = mysql_query("SELECT * FROM df_merges WHERE ResultID = '" . $shop_query['ItemID'] . "' LIMIT 1") or $error = 1;
                $merges = mysql_fetch_assoc($merges_result);
                $item_needed1 = mysql_query("SELECT * FROM df_items WHERE ItemID = '" . $merges['RequiredID1'] . "' LIMIT 1") or $error = 1;
                $item_needed2 = mysql_query("SELECT * FROM df_items WHERE ItemID = '" . $merges['RequiredID2'] . "' LIMIT 1") or $error = 1;
                $item1 = mysql_fetch_assoc($item_needed1);
                $item2 = mysql_fetch_assoc($item_needed2);

                $items = $shop->appendChild($dom->createElement('items'));
                $items->setAttribute("ItemID", $shop_query['ItemID']);
                $items->setAttribute("ID", $shop_query['ItemID']);
                $items->setAttribute("ItemID1", $merges['RequiredID1']);
                $items->setAttribute("Item1", $item1['ItemName']);
                $items->setAttribute("Qty1", $merges['RequiredQTY1']);
                $items->setAttribute("ItemID2", $merges['RequiredID2']);
                $items->setAttribute("Item2", $item2['ItemName']);
                $items->setAttribute("Qty2", $merges['RequiredQTY2']);
                $items->setAttribute("strItemName", $shop_query['ItemName']);
                $items->setAttribute("intCount", "1");
                $items->setAttribute("intHP", $shop_query['hp']);
                $items->setAttribute("intMaxHP", $shop_query['hp']);
                $items->setAttribute("intMP", $shop_query['mp']);
                $items->setAttribute("intMaxMP", $shop_query['mp']);
                $items->setAttribute("bitEquipped", "0");
                $items->setAttribute("bitDefault", "0");
                $items->setAttribute("intCurrency", $shop_query['Currency']);
                $items->setAttribute("intCost", $shop_query['Cost']);
                $items->setAttribute("intHP", "0");
                $items->setAttribute("intLevel", $shop_query['Level']);
                $items->setAttribute("strItemDescription", $shop_query['ItemDescription']);
                $items->setAttribute("bitDragonAmulet", $shop_query['DragonAmulet']);
                $items->setAttribute("strEquipSpot", $shop_query['EquipSpot']);
                $items->setAttribute("strCategory", $shop_query['Category']);
                $items->setAttribute("strItemType", $shop_query['ItemType']);
                $items->setAttribute("strType", $shop_query['Type']);
                $items->setAttribute("strFileName", $shop_query['FileName']);
                $items->setAttribute("intMin", $shop_query['Min']);
                $items->setAttribute("intCrit", $shop_query['intCrit']);
                $items->setAttribute("intDefMelee", $shop_query['intDefMelee']);
                $items->setAttribute("intDefPierce", $shop_query['intDefPierce']);
                $items->setAttribute("intDodge", $shop_query['intDodge']);
                $items->setAttribute("intParry", $shop_query['intParry']);
                $items->setAttribute("intDefMagic", $shop_query['intDefMagic']);
                $items->setAttribute("intBlock", $shop_query['intBlock']);
                $items->setAttribute("intDefRange", $shop_query['intDefRange']);
                $items->setAttribute("intMax", $shop_query['Max']);
                $items->setAttribute("intBonus", $shop_query['Bonus']);
                $items->setAttribute("strResists", $shop_query['Resists']);
                $items->setAttribute("strElement", $shop_query['Element']);
                $items->setAttribute("intRarity", $shop_query['Rarity']);
                $items->setAttribute("intMaxStackSize", $shop_query['MaxStackSize']);
                $items->setAttribute("strIcon", $shop_query['Icon']);
                $items->setAttribute("bitSellable", $shop_query['Sellable']);
                $items->setAttribute("bitDestroyable", "1");
            }
        } else if($vendor['admin'] == 1) {
            $shop->setAttribute("strCharacterName", "Empty Shop");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>