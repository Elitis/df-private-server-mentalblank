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

    $intQuestID = $doc->getElementsByTagName('intQuestID');
    $quest_id = $intQuestID->item(0)->nodeValue;

    $quest_results = mysql_query("SELECT * FROM df_quests WHERE QuestID = '".$quest_id."' LIMIT 1") or $error = '1';
    $quest = mysql_fetch_array($quest_results) or $error = '1';
    $rows = mysql_num_rows($quest_results) or $error = '1';

    if($error == 1 || $rows < 1)
    {
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
        $XML = $dom->appendChild($dom->createElement('quest'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $quest = $XML->appendChild($dom->createElement('quest'));
        $quest->setAttribute("intCounter", $quest['Counter']);

        $XML2 = $dom->appendChild($dom->createElement('success'));
        $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $XML2->setAttribute("status", "SUCCESS");
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>

