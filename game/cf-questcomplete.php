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

    $intQuestID = $doc->getElementsByTagName('intQuestID');
    $QuestID = $intQuestID->item(0)->nodeValue;

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
        $quest_result = mysql_query("SELECT * FROM df_quests WHERE QuestID = '" . $QuestID . "' LIMIT 1") or $error = 1;
        $quest = mysql_fetch_assoc($char_result);

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

            if($gold != 0){
                $addgold = mysql_query("UPDATE df_characters SET gold='" . $gold_total . "' WHERE id='" . $char_id . "' LIMIT 1") or $error = 1;
            }
            if($exp != 0){
                $addexp = mysql_query("UPDATE df_characters SET exp='" . $exp_total . "' WHERE id='" . $char_id . "' LIMIT 1") or $error = 1;
            }

            if($QuestID == 54 && $char['HomeTownID'] != 40){
                $set_newhome = mysql_query("UPDATE df_characters SET HomeTownID='40' WHERE id='" . $char_id . "' LIMIT 1") or $error = 1;
            }

            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Could not save exp or gold.";

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
                $quest = $XML->appendChild($dom->createElement('questreward'));
                $quest->setAttribute("intGold", $gold_total);
                $quest->setAttribute("intExp", $exp_tota);
                $quest->setAttribute("intSilver", "0");
                $quest->setAttribute("intGems", "0");
                $quest->setAttribute("intExpToLevel", $exptolevel);

                if($quest['Rewards'] != '0'){
                    $replaced = str_replace(",", " OR ItemID = ", $quest['Rewards']);
                    $rewards = mysql_query("SELECT * FROM df_items WHERE ItemID = ".$replaced." ORDER BY RAND() LIMIT 1") or $error = 1;
                    if (mysql_num_rows($rewards) < 1 || $error == 1){
                        $reason = "Error!";
                        $message = "Could not grab reward information.";

                        $dom = new DOMDocument("1.0", "UTF-8");
                        $XML = $dom->appendChild($dom->createElement('error'));
                        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                        $info = $XML->appendChild($dom->createElement('info'));
                        $info->setAttribute("code", "526.14");
                        $info->setAttribute("reason", $reason);
                        $info->setAttribute("message", $message);
                        $info->setAttribute("action", "None");
                    } else {
                        while($shop = mysql_fetch_assoc($rewards)){
                            $items = $shop->appendChild($dom->createElement('items'));
                            $items->setAttribute("ItemID", $shop_query['ItemID']);
                            $items->setAttribute("strItemName",  $shop["ItemName"]);
                            $items->setAttribute("intCount",  '1');
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
                            $items->setAttribute("intLevel",  $shop["Level"]);
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
                    }
                }
                if(!$error){
                    $XML2 = $dom->appendChild($dom->createElement('success'));
                    $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                    $XML2->setAttribute("status", "SUCCESS");
                }
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>