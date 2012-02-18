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

//This file is Incomplete and will be finished Later... although chances are you will never need it... COZ YOU SUCK :D

include ("../includes/config.php");
header("content-type: text/xml; charset=UTF-8",true);

if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents("php://input");

    $doc = new DOMDocument();
    $doc->loadXML($HTTP_RAW_POST_DATA);

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;
    
    $dom = new DOMDocument("1.0", "UTF-8");
    $XML = $dom->appendChild($dom->createElement('warvars'));
    $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
    $warvars = $XML->appendChild($dom->createElement('warvars'));
    $warvars->setAttribute("strSomething", "MentalBlank is King");
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>

