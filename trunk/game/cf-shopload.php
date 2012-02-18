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

    $intShopID = $doc->getElementsByTagName('intShopID');
    $shop_id = $intShopID->item(0)->nodeValue;

    $vendor_result = mysql_query("SELECT * FROM df_vendors WHERE ShopID = '" . $shop_id . "' and MergeShopID = 'none' LIMIT 1") or $error = 1;
    $vendor = mysql_fetch_assoc($vendor_result) or $error = 1;
    $shop_result = mysql_query("SELECT * FROM df_items WHERE ShopID = '" . $shop_id . "'") or $error = 1;

    $dom = new DOMDocument("1.0", "UTF-8");
    $XML = $dom->appendChild($dom->createElement('shop'));
    $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
    $shop = $XML->appendChild($dom->createElement('shop'));
    $shop->setAttribute("ShopID", $shop_id);
    if($error == 1)
    {
        $shop->setAttribute("strCharacterName", "Empty Shop");
    } else {
        if($vendor['admin'] != 1)
        {
            $shop->setAttribute("strCharacterName", $vendor['ShopName']);
            while ($shop_query = mysql_fetch_array($shop_result))
            {
                $items = $shop->appendChild($dom->createElement('items'));
                $items->setAttribute("ItemID", $shop_query['ItemID']);
                $items->setAttribute("strItemName",  $shop_query["ItemName"]);
                $items->setAttribute("intCount",  '1');
                $items->setAttribute("strItemDescription",  $shop_query["ItemDescription"]);
                $items->setAttribute("bitVisible",  $shop_query["Visible"]);
                $items->setAttribute("bitDestroyable",  $shop_query["Destroyable"]);
                $items->setAttribute("bitSellable",  $shop_query["Sellable"]);
                $items->setAttribute("bitDragonAmulet",  $shop_query["DragonAmulet"]);
                $items->setAttribute("intCurrency",  $shop_query["Currency"]);
                $items->setAttribute("intCost",  $shop_query["Cost"]);
                $items->setAttribute("intMaxStackSize",  $shop_query["MaxStackSize"]);
                $items->setAttribute("intBonus",  $shop_query["Bonus"]);
                $items->setAttribute("intRarity",  $shop_query["Rarity"]);
                $items->setAttribute("intLevel",  $shop_query["Level"]);
                $items->setAttribute("strType",  $shop_query["Type"]);
                $items->setAttribute("strElement",  $shop_query["Element"]);
                $items->setAttribute("strCategory",  $shop_query["Category"]);
                $items->setAttribute("strEquipSpot",  $shop_query["EquipSpot"]);
                $items->setAttribute("strItemType",  $shop_query["ItemType"]);
                $items->setAttribute("strFileName",  $shop_query["FileName"]);
                $items->setAttribute("strIcon",  $shop_query["Icon"]);
                $items->setAttribute("intHP",  $shop_query["hp"]);
                $items->setAttribute("intMaxHP",  $shop_query["hp"]);
                $items->setAttribute("intMP",  $shop_query["mp"]);
                $items->setAttribute("intMaxMP",  $shop_query["mp"]);
                $items->setAttribute("bitEquipped", '0');
                $items->setAttribute("bitDefault", '0');
                $items->setAttribute("intStr",  $shop_query["intStr"]);
                $items->setAttribute("intDex",  $shop_query["intDex"]);
                $items->setAttribute("intInt",  $shop_query["intInt"]);
                $items->setAttribute("intLuk",  $shop_query["intLuk"]);
                $items->setAttribute("intCha",  $shop_query["intCha"]);
                $items->setAttribute("intEnd",  $shop_query["intEnd"]);
                $items->setAttribute("intMin",  $shop_query["Min"]);
                $items->setAttribute("intMax",  $shop_query["Max"]);
                $items->setAttribute("intDefMelee",  $shop_query["intDefMelee"]);
                $items->setAttribute("intDefPierce",  $shop_query["intDefPierce"]);
                $items->setAttribute("intDefMagic",  $shop_query["intDefMagic"]);
                $items->setAttribute("intCrit",  $shop_query["intCrit"]);
                $items->setAttribute("intParry",  $shop_query["intParry"]);
                $items->setAttribute("intDodge",  $shop_query["intDodge"]);
                $items->setAttribute("intBlock",  $shop_query["intBlock"]);
                $items->setAttribute("strResists",  $shop_query["Resists"]);
                $items->setAttribute("ShopItemID",  $shop_query["ShopItemID"]);
            }
        } else if($vendor['admin'] == 1) {
            $shop->setAttribute("strCharacterName", "Empty Shop");
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>


