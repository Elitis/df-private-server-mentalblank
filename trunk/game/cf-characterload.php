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
header("content-type: text/xml; charset=UTF-8", true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $token = $strToken->item(0)->nodeValue;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE id = '" . $char_id . "'") or die(mysql_error());
    $char = mysql_fetch_assoc($char_result);

    $user_result = mysql_query("SELECT * FROM df_users WHERE id = '".$char['userid']."' AND pass = '".$token."' LIMIT 1") or $error = 1;
    $user = mysql_fetch_assoc($user_result);
    $user_rows = mysql_num_rows($user_result);

    if($user_rows != 1 || $error == 1){
        $reason = "Error!";
        $message = "There was an issue with your account... Please Login and try again";

        $dom = new DOMDocument("1.0");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        //Your Characters EXP Needed to Level.
        $exptolevel = $char['level'] * 20 * $char['level'];

        //This Gets your hair from the database and if is not found loads set hair...
        $hair_result = mysql_query("SELECT * FROM df_hairs WHERE HairID = '" . $char['hairid'] . "' AND Gender = '".$char['gender']."' LIMIT 1");
        $hair = mysql_fetch_assoc($hair_result);
        $hair_rows = mysql_num_rows($hair_result);
        if($hair_rows > 0){
            $hairfilename = "head/".$char['gender']."/".$hair['HairSWF']."";
        } else {
            $hairfilename = "head/M/hair-male-carefree.swf";
        }

        //This gets your class from the database
        $class_result = mysql_query("SELECT * FROM df_class WHERE ClassID = '" . $char['classid'] . "'");
        $class = mysql_fetch_assoc($class_result);

       //This fixes unrealistic stats points remaining.
            $statlevel = $char['level'] - 1;
            $maxstats = 5 * $statlevel;
            if ($char['intStatPoints'] > $maxstats) {
                $statpoints = $maxstats;
                $ChangePoints = mysql_query("UPDATE df_characters SET intStatPoints='" . $statpoints . "' WHERE ID='" . $char['id'] . "' LIMIT 1") or $error = 1;
            }

        if (mysql_num_rows($char_result) > 0) {
            //This gets your home town from the Database
            $town_result = mysql_query("SELECT * FROM df_towns WHERE TownID = '" . $char['HomeTownID'] . "'");
            $town = mysql_fetch_assoc($town_result);

            if($town['strExtra'] != "none") {
                //This Grabs Zone Data
                $zones = "";
                //Converts Extra string into Array
                $replaced = str_replace(",", " OR ZoneID = ", $town['strExtra']);
                $query = mysql_query("SELECT * FROM df_extra WHERE ZoneID = ".$replaced."");
                //Gets Data for Individual Zones
                while($zq = mysql_fetch_assoc($query)){
                    $zones2 = mysql_fetch_assoc(mysql_query("SELECT * FROM df_extra WHERE ZoneID = '".$zq['ZoneID']."' LIMIT 1"));
                    if($zones != ""){
                        if($zones2['Name']=="none"){
                            $zones = $zones.$zones2['Data']."\n";
                        } else {
                            $zones = $zones.$zones2['Name']."=".$zones2['Data']."\n";
                        }
                    } else {
                        if($zones2['Name']=="none"){
                            $zones = $zones2['Data']."\n";
                        } else {
                            $zones = $zones2['Name']."=".$zones2['Data']."\n";
                        }
                    }
                }
            } else {
                $zones = "none";
            }

            //Loads The Character
            $dom = new DOMDocument("1.0");
            $XML = $dom->appendChild($dom->createElement('character'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $character = $XML->appendChild($dom->createElement('character'));
            //Character Stats + Info
                $character->setAttribute("CharID", $char['id']);
                $character->setAttribute("strCharacterName", $char['name']);
                $character->setAttribute("dateCreated", $char['born']);
                $date = date('Y')."-".date('j')."-".date('d');
                if($char['born'] == $date){
                    //Happy Birthday!!!!
                    $character->setAttribute("isBirthday", "1");
                } else {
                    //You Suck!!!
                    $character->setAttribute("isBirthday", "0");
                }
                $character->setAttribute("intHP", $char['hp']);
                $character->setAttribute("intMP", $char['mp']);
                $character->setAttribute("intLevel", $char['level']);
                $character->setAttribute("intExp", $char['exp']);
                $character->setAttribute("intAccessLevel", $char['access']);
                $character->setAttribute("intSilver", $char['Silver']);
                $character->setAttribute("intGold", $char['gold']);
                $character->setAttribute("intGems", $char['Gems']);
                $character->setAttribute("intCoins", $char['Coins']);
                $character->setAttribute("intMaxBagSlots", $char['MaxBagSlots']);
                $character->setAttribute("intMaxBankSlots", $char['MaxBankSlots']);
                $character->setAttribute("intMaxHouseSlots", $char['MaxHouseSlots']);
                $character->setAttribute("intMaxHouseItemSlots", $char['MaxHouseItemSlots']);
                $character->setAttribute("intDragonAmulet", $char['dragon_amulet']);
                $character->setAttribute("intAccesslevel", $user['access']);
                $character->setAttribute("strGender", $char['gender']);
                $character->setAttribute("intColorHair", $char['colorhair']);
                $character->setAttribute("intColorSkin", $char['colorskin']);
                $character->setAttribute("intColorBase", $char['colorbase']);
                $character->setAttribute("intColorTrim", $char['colortrim']);
                $character->setAttribute("intStr", $char['intSTR']);
                $character->setAttribute("intDex", $char['intDEX']);
                $character->setAttribute("intInt", $char['intINT']);
                $character->setAttribute("intLuk", $char['intLUK']);
                $character->setAttribute("intCha", $char['intCHA']);
                $character->setAttribute("intEnd", $char['intEND']);
                $character->setAttribute("intWis", $char['intWIS']);
                $character->setAttribute("intSkillPoints", "0");
                $character->setAttribute("intStatPoints", $statpoints);
                $character->setAttribute("intCharStatus", "0");
                $character->setAttribute("strArmor", $char['strArmor']);
                $character->setAttribute("strSkills", $char['strSkills']);
                $character->setAttribute("strQuests", $char['strQuests']);
                $character->setAttribute("intExpToLevel", $exptolevel);
                $character->setAttribute("RaceID", $char['raceid']);
                $character->setAttribute("strRaceName", $char['race']);
                $character->setAttribute("GuildID", "1");
                $character->setAttribute("strGuildName", "None");
                $character->setAttribute("QuestID", $town['TownID']);
                $character->setAttribute("strQuestName", "No Quest");
                $character->setAttribute("strQuestFileName", $town['strQuestFileName']);
                $character->setAttribute("strXQuestFileName", $town['strQuestXFileName']);
                $character->setAttribute("strExtra", $zones);
                $character->setAttribute("BaseClassID", $char['BaseClassID']);
                $character->setAttribute("ClassID", $char['classid']);
                $character->setAttribute("PrevClassID", $char['PrevClassID']);
                $character->setAttribute("strClassName", $class['ClassName']);
                $character->setAttribute("strClassFileName", $class['ClassSWF']);
                $character->setAttribute("strArmorName", $class['ArmorName']);
                $character->setAttribute("strArmorDescription", $class['ArmorDescription']);
                $character->setAttribute("strArmorResists", $class['ArmorResists']);
                $character->setAttribute("intDefMelee", $class['DefMelee']);
                $character->setAttribute("intDefPierce", $class['DefPierce']);
                $character->setAttribute("intDefMagic", $class['DefMagic']);
                $character->setAttribute("intParry", $class['Parry']);
                $character->setAttribute("intDodge", $class['Dodge']);
                $character->setAttribute("intBlock", $class['Block']);
                $character->setAttribute("strWeaponName", $class['WeaponName']);
                $character->setAttribute("strWeaponDescription", $class['WeaponDescription']);
                $character->setAttribute("strWeaponDesignInfo", $class['WeaponDesignInfo']);
                $character->setAttribute("strWeaponResists", $class['WeaponResists']);
                $character->setAttribute("intWeaponLevel", $class['WeaponLevel']);
                $character->setAttribute("strWeaponIcon", $class['WeaponIcon']);
                $character->setAttribute("strType", $class['Type']);
                $character->setAttribute("bitDefault", "1");
                $character->setAttribute("bitEquipped", "0");
                $character->setAttribute("strItemType", $class['ItemType']);
                $character->setAttribute("intCrit", $class['Crit']);
                $character->setAttribute("intDmgMin", $class['DmgMin']);
                $character->setAttribute("intDmgMax", $class['DmgMax']);
                $character->setAttribute("intBonus", $class['Bonus']);
                $character->setAttribute("strElement", $class['Element']);
                $character->setAttribute("strEquippable", "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket");
                $character->setAttribute("strHairFileName", $hairfilename);
                $character->setAttribute("intHairFrame", $char['hairframe']);
                $character->setAttribute("gemReward", "0");
                
                if($error == 1){
                    $reason = "Error!";
                    $message = "There was an issue with your character... Please Login and try again";

                    $dom = new DOMDocument("1.0");
                    $XML = $dom->appendChild($dom->createElement('error'));
                    $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                    $info = $XML->appendChild($dom->createElement('info'));
                    $info->setAttribute("code", "526.14");
                    $info->setAttribute("reason", $reason);
                    $info->setAttribute("message", $message);
                    $info->setAttribute("action", "None");
                } else {
                    //This Grabs the characters Inventory from DB
                    $inventory = mysql_query("SELECT * FROM df_equipment WHERE CharID = '".$char_id."'") or $error = 1;
                    $invent_rows = mysql_num_rows($inventory);

                    //Loads Inventory
                    $itemsloaded = array();
                    if ($invent_rows > 0) {
                        while($inv1 = mysql_fetch_assoc($inventory)){
                            array_push($itemsloaded, $inv1['ItemID']);
                        }
                        $a2 = array_unique($itemsloaded);
                        for ($i = 0; $i <= count($a2); $i++) {
                            if($a2[$i] != NULL) {
                                $inventory2 = mysql_query("SELECT * FROM df_equipment WHERE CharID = '".$char_id."' AND ItemID = '".$a2[$i]."'") or $error = 1;
                                $invent = mysql_fetch_assoc($inventory2);

                                $shop_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '".$a2[$i]."' LIMIT 1") or $error = 1;
                                $shop = mysql_fetch_assoc($shop_result);

                                $count_result = mysql_query("SELECT * FROM df_equipment WHERE ItemID = '".$a2[$i]."' AND CharID = '".$char_id."'") or $error = 1;
                                $count = mysql_num_rows($count_result);

                                if($shop['Admin'] >= 1 && $user['access'] < 40) {
                                    $reason = "Banned!";
                                    $message = "You have been banned for Inventory Related Issues, If this is incorrect please contact the Server Administrator";

                                    $dom = new DOMDocument("1.0", "UTF-8");
                                    $XML = $dom->appendChild($dom->createElement('error'));
                                    $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                                    $info = $XML->appendChild($dom->createElement('info'));
                                    $info->setAttribute("code", "526.14");
                                    $info->setAttribute("reason", $reason);
                                    $info->setAttribute("message", $message);
                                    $info->setAttribute("action", "None");
                                    $Ban_User = mysql_query("UPDATE df_users SET access ='0' WHERE name='".$_SESSION['name']."' LIMIT 1") or $error = 1;
                                    $dom->formatOutput = true;
                                    echo $dom->saveXML();
                                    die();
                                }

                                if($shop["MaxStackSize"] > 1){
                                    if($count <= $shop["MaxStackSize"]){
                                        $items = $character->appendChild($dom->createElement('items'));
                                        $items->setAttribute("ItemID", $shop["ItemID"]);
                                        $items->setAttribute("CharItemID", $invent["CharItemID"]);
                                        $items->setAttribute("strItemName", $shop["ItemName"]);
                                        $items->setAttribute("intCount", $count);
                                        $items->setAttribute("strItemDescription", $shop["ItemDescription"]);
                                        $items->setAttribute("bitVisible", '1');
                                        $items->setAttribute("bitDestroyable", $shop["Destroyable"]);
                                        $items->setAttribute("bitSellable", $shop["Sellable"]);
                                        $items->setAttribute("bitDragonAmulet", $shop["DragonAmulet"]);
                                        $items->setAttribute("intCurrency", $shop["Currency"]);
                                        $items->setAttribute("intCost", $shop["Cost"]);
                                        $items->setAttribute("intMaxStackSize", $shop["MaxStackSize"]);
                                        $items->setAttribute("intBonus", $shop["Bonus"]);
                                        $items->setAttribute("intRarity", $shop["Rarity"]);
                                        $items->setAttribute("intLevel", $shop['Level']);
                                        $items->setAttribute("intCharLevel", $invent['Level']);
                                        $items->setAttribute("intExp", $invent['Exp']);
                                        $items->setAttribute("strType", $shop["Type"]);
                                        $items->setAttribute("strElement", $shop["Element"]);
                                        $items->setAttribute("strCategory", $shop["Category"]);
                                        $items->setAttribute("strEquipSpot", $shop["EquipSpot"]);
                                        $items->setAttribute("strItemType", $shop["ItemType"]);
                                        $items->setAttribute("strFileName", $shop["FileName"]);
                                        $items->setAttribute("strIcon", $shop["Icon"]);
                                        $items->setAttribute("intHP", $shop["hp"]);
                                        $items->setAttribute("intMaxHP", $shop["hp"]);
                                        $items->setAttribute("intMP", $shop["mp"]);
                                        $items->setAttribute("intMaxMP", $shop["mp"]);
                                        $items->setAttribute("bitEquipped", $invent["StartingItem"]);
                                        $items->setAttribute("bitDefault", $invent["StartingItem"]);
                                        $items->setAttribute("intStr", $shop["intStr"]);
                                        $items->setAttribute("intDex", $shop["intDex"]);
                                        $items->setAttribute("intInt", $shop["intInt"]);
                                        $items->setAttribute("intLuk", $shop["intLuk"]);
                                        $items->setAttribute("intCha", $shop["intCha"]);
                                        $items->setAttribute("intEnd", $shop["intEnd"]);
                                        $items->setAttribute("intMin", $shop["Min"]);
                                        $items->setAttribute("intMax", $shop["Max"]);
                                        $items->setAttribute("intDefMelee", $shop["intDefMelee"]);
                                        $items->setAttribute("intDefPierce", $shop["intDefPierce"]);
                                        $items->setAttribute("intDefMagic", $shop["intDefMagic"]);
                                        $items->setAttribute("intCrit", $shop["intCrit"]);
                                        $items->setAttribute("intParry", $shop["intParry"]);
                                        $items->setAttribute("intDodge", $shop["intDodge"]);
                                        $items->setAttribute("intBlock", $shop["intBlock"]);
                                        $items->setAttribute("strResists", $shop["Resists"]);
                                    }
                                }
                                if($shop["MaxStackSize"] == 1){
                                    for ($x = 0; $x <= $count; $x++) {
                                        $items = $character->appendChild($dom->createElement('items'));
                                        $items->setAttribute("ItemID", $shop["ItemID"]);
                                        $items->setAttribute("CharItemID", $invent["CharItemID"]);
                                        $items->setAttribute("strItemName", $shop["ItemName"]);
                                        $items->setAttribute("intCount", '1');
                                        $items->setAttribute("strItemDescription", $shop["ItemDescription"]);
                                        $items->setAttribute("bitVisible", '1');
                                        $items->setAttribute("bitDestroyable", $shop["Destroyable"]);
                                        $items->setAttribute("bitSellable", $shop["Sellable"]);
                                        $items->setAttribute("bitDragonAmulet", $shop["DragonAmulet"]);
                                        $items->setAttribute("intCurrency", $shop["Currency"]);
                                        $items->setAttribute("intCost", $shop["Cost"]);
                                        $items->setAttribute("intMaxStackSize", $shop["MaxStackSize"]);
                                        $items->setAttribute("intBonus", $shop["Bonus"]);
                                        $items->setAttribute("intRarity", $shop["Rarity"]);
                                        $items->setAttribute("intLevel", $shop['Level']);
                                        $items->setAttribute("intCharLevel", $invent['Level']);
                                        $items->setAttribute("intExp", $invent['Exp']);
                                        $items->setAttribute("strType", $shop["Type"]);
                                        $items->setAttribute("strElement", $shop["Element"]);
                                        $items->setAttribute("strCategory", $shop["Category"]);
                                        $items->setAttribute("strEquipSpot", $shop["EquipSpot"]);
                                        $items->setAttribute("strItemType", $shop["ItemType"]);
                                        $items->setAttribute("strFileName", $shop["FileName"]);
                                        $items->setAttribute("strIcon", $shop["Icon"]);
                                        $items->setAttribute("intHP", $shop["hp"]);
                                        $items->setAttribute("intMaxHP", $shop["hp"]);
                                        $items->setAttribute("intMP", $shop["mp"]);
                                        $items->setAttribute("intMaxMP", $shop["mp"]);
                                        $items->setAttribute("bitEquipped", $invent["StartingItem"]);
                                        $items->setAttribute("bitDefault", $invent["StartingItem"]);
                                        $items->setAttribute("intStr", $shop["intStr"]);
                                        $items->setAttribute("intDex", $shop["intDex"]);
                                        $items->setAttribute("intInt", $shop["intInt"]);
                                        $items->setAttribute("intLuk", $shop["intLuk"]);
                                        $items->setAttribute("intCha", $shop["intCha"]);
                                        $items->setAttribute("intEnd", $shop["intEnd"]);
                                        $items->setAttribute("intMin", $shop["Min"]);
                                        $items->setAttribute("intMax", $shop["Max"]);
                                        $items->setAttribute("intDefMelee", $shop["intDefMelee"]);
                                        $items->setAttribute("intDefPierce", $shop["intDefPierce"]);
                                        $items->setAttribute("intDefMagic", $shop["intDefMagic"]);
                                        $items->setAttribute("intCrit", $shop["intCrit"]);
                                        $items->setAttribute("intParry", $shop["intParry"]);
                                        $items->setAttribute("intDodge", $shop["intDodge"]);
                                        $items->setAttribute("intBlock", $shop["intBlock"]);
                                        $items->setAttribute("strResists", $shop["Resists"]);
                                    }
                                }
                            }
                        }
                    }
                    if($error != 1) {
                        //Loads the characters dragon if they have one
                        if ($char['HasDragon'] == 1) {
                            $dragon = mysql_query("SELECT * FROM df_dragons WHERE CharDragID = '" . $char_id . "' LIMIT 1") or $error = 1;
                            $drag = mysql_fetch_assoc($dragon);
                            $dragon = $character->appendChild($dom->createElement('dragon'));
                            $dragon->setAttribute("strName", $drag['strName']);
                            $dragon->setAttribute("intCrit", $drag['intCrit']);
                            $dragon->setAttribute("intMin", $drag['intMin']);
                            $dragon->setAttribute("intMax", $drag['intMax']);
                            $dragon->setAttribute("strElement", $drag['strElement']);
                            $dragon->setAttribute("intPowerBoost", $drag['intPowerBoost']);
                            $dragon->setAttribute("dateLastFed", $drag['dateLastFed']);
                            $dragon->setAttribute("intTotalStats", $drag['intTotalStats']);
                            $dragon->setAttribute("intHeal", $drag['intHeal']);
                            $dragon->setAttribute("intMagic", $drag['intMagic']);
                            $dragon->setAttribute("intMelee", $drag['intMelee']);
                            $dragon->setAttribute("intBuff", $drag['intBuff']);
                            $dragon->setAttribute("intDebuff", $drag['intDebuff']);
                        }
                    } else {
                        $reason = "Error!";
                        $message = "There was an issue with your inventory... Please Login and try again";

                        $dom = new DOMDocument("1.0");
                        $XML = $dom->appendChild($dom->createElement('error'));
                        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                        $info = $XML->appendChild($dom->createElement('info'));
                        $info->setAttribute("code", "526.14");
                        $info->setAttribute("reason", $reason);
                        $info->setAttribute("message", $message);
                        $info->setAttribute("action", "None");
                }
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>