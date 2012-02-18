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

    $intQuestID = $doc->getElementsByTagName('intQuestID');
    $quest_id = $intQuestID->item(0)->nodeValue;

    $quest_results = mysql_query("SELECT * FROM df_quests WHERE QuestID = '".$quest_id."' LIMIT 1") or $error = '1';
    $questq = mysql_fetch_array($quest_results) or $error = '1';
    $rows = mysql_num_rows($quest_results) or $error = '1';

    if($error == 1 || $rows < 1)
    {
        $reason = "Error!";
        $message = "There was a problem loading the Quest.";

        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        if($questq['Extra'] != "none") {
            //This Grabs Zone Data
            $zones = "";
            //Converts Extra string into Array
            $replaced = str_replace(",", " OR ZoneID = ", $questq['Extra']);
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
        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('quest'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $quest = $XML->appendChild($dom->createElement('quest'));
        $quest->setAttribute("QuestID", $quest_id);
        $quest->setAttribute("strName", $questq['Name']);
        $quest->setAttribute("strDescription", $questq['Description']);
        $quest->setAttribute("strComplete", $questq['Complete']);
        $quest->setAttribute("strFileName", $questq['FileName']);
        $quest->setAttribute("strXFileName", $questq['XFileName']);
        $quest->setAttribute("intMaxSilver", $questq['MaxSilver']);
        $quest->setAttribute("intMaxGold", $questq['MaxGold']);
        $quest->setAttribute("intMaxGems", $questq['MaxGems']);
        $quest->setAttribute("intMaxExp", $questq['MaxExp']);
        $_SESSION['MaxQuestGold'] = $questq['MaxGold'];
        $_SESSION['MaxQuestGems'] = $questq['MaxGems'];
        $_SESSION['MaxQuestExp'] = $questq['MaxExp'];
        $_SESSION['MaxQuestCoins'] = 0;
        $quest->setAttribute("intMinTime", $questq['MinTime']);
        $quest->setAttribute("intCounter", $questq['Counter']);
        $quest->setAttribute("strExtra", $zones);
        $quest->setAttribute("intMonsterMinLevel", $questq['MonsterMinLevel']);
        $quest->setAttribute("intMonsterMaxLevel", $questq['MonsterMaxLevel']);
        $quest->setAttribute("strMonsterType", $questq['MonsterType']);
        $quest->setAttribute("strMonsterGroupFileName", $questq['MonsterGroupFileName']);

        $replaced = str_replace(",", " OR MonsterID = ", $questq['MonsterIDs']);
        $monsters = mysql_query("SELECT * FROM df_monsters WHERE MonsterID = ".$replaced."");
        if (mysql_num_rows($monsters) < 1){
            echo("Error: Couldn't Grab Monsters");
        }
        while($mon = mysql_fetch_assoc($monsters)){
            $monInfo = mysql_query("SELECT * FROM df_monsters WHERE QuestID = '".$mon['MonsterID']."' LIMIT 1");
            $monster = $quest->appendChild($dom->createElement('monsters'));
            $monster->setAttribute("MonsterID", $mon['MonsterID']);
            $monster->setAttribute("intMonsterRef", $mon['MonsterRef']);
            $monster->setAttribute("strCharacterName", $mon['Name']);
            $monster->setAttribute("intLevel", $mon['Level']);
            $monster->setAttribute("intExp", $mon['Exp']);
            $monster->setAttribute("intHP", $mon['HP']);
            $monster->setAttribute("intMP", $mon['MP']);
            $monster->setAttribute("intSilver", $mon['Silver']);
            $monster->setAttribute("intGold", $mon['Gold']);
            $monster->setAttribute("intGems", $mon['Gems']);
            $monster->setAttribute("intDragonCoins", $mon['Coins']);
            $_SESSION['MaxQuestGold'] = $_SESSION['MaxQuestGold']+$mon['Gold'];
            $_SESSION['MaxQuestGems'] = $_SESSION['MaxQuestGems']+$mon['Gems'];
            $_SESSION['MaxQuestExp'] = $_SESSION['MaxQuestExp']+$mon['Exp'];
            $_SESSION['MaxQuestCoins'] = $_SESSION['MaxQuestCoins']+$mon['Coins'];
            $monster->setAttribute("strGender", $mon['Gender']);
            $monster->setAttribute("intHairStyle", $mon['HairStyle']);
            $monster->setAttribute("intColorHair", $mon['ColorHair']);
            $monster->setAttribute("intColorSkin", $mon['ColorSkin']);
            $monster->setAttribute("intColorBase", $mon['ColorBase']);
            $monster->setAttribute("intColorTrim", $mon['ColorTrim']);
            $monster->setAttribute("intStr", $mon['STR']);
            $monster->setAttribute("intDex", $mon['DEX']);
            $monster->setAttribute("intInt", $mon['INT']);
            $monster->setAttribute("intLuk", $mon['LUK']);
            $monster->setAttribute("intCha", $mon['CHA']);
            $monster->setAttribute("intEnd", $mon['END']);
            $monster->setAttribute("strArmorName", $mon['ArmorName']);
            $monster->setAttribute("strArmorDescription", $mon['ArmorDesc']);
            $monster->setAttribute("strArmorDesignInfo", $mon['ArmorDesignInfo']);
            $monster->setAttribute("strArmorResists", $mon['ArmorResists']);
            $monster->setAttribute("intDefMelee", $mon['DefMelee']);
            $monster->setAttribute("intDefRange", $mon['DefRange']);
            $monster->setAttribute("intDefMagic", $mon['DefMagic']);
            $monster->setAttribute("intParry", $mon['Parry']);
            $monster->setAttribute("intDodge", $mon['Dodge']);
            $monster->setAttribute("intBlock", $mon['Block']);
            $monster->setAttribute("strWeaponName", $mon['WeaponName']);
            $monster->setAttribute("strWeaponDescription", $mon['WeaponDesc']);
            $monster->setAttribute("strWeaponDesignInfo", $mon['WeaponDesignInfo']);
            $monster->setAttribute("strWeaponResists", $mon['WeaponResists']);
            $monster->setAttribute("strType", $mon['Type']);
            $monster->setAttribute("intCrit", $mon['Crit']);
            $monster->setAttribute("intDmgMin", $mon['DmgMin']);
            $monster->setAttribute("intDmgMax", $mon['DmgMax']);
            $monster->setAttribute("intBonus", $mon['Bonus']);
            $monster->setAttribute("strElement", $mon['Element']);
            $monster->setAttribute("strWeaponFile", $mon['WepFile']);
            $monster->setAttribute("strMovName", $mon['MovName']);
            $monster->setAttribute("strMonsterFileName", $mon['MonFile']);
            $monster->setAttribute("RaceID", $mon['RaceID']);
            $monster->setAttribute("strRaceName", $mon['RaceName']);
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>
