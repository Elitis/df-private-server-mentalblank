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

    $intElement = $doc->getElementsByTagName('intElement');
    $Element = $intElement->item(0)->nodeValue;
    
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
    }

    switch ($Element) {
	case 5:
                $newElement = "Fire";
		break;
	case 6:
                $newElement = "Water";
		break;
	case 7:
                $newElement = "Ice";
		break;
	case 8:
                $newElement = "Wind";
		break;
	case 9:
                $newElement = "Energy";
		break;
	case 10:
                $newElement = "Light";
		break;
	case 11:
                $newElement = "Darkness";
		break;
	case 18:
                $newElement = "Nature";
		break;
    }
    
    $ChangeElement = mysql_query("UPDATE df_dragons SET strElement='".$newElement."' WHERE CharDragID='".$char_id."' LIMIT 1") or $error = 1;

    if($error == 1){
        $reason = "Error!";
        $message = "Unable to change your dragons element.";

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
        $XML = $dom->appendChild($dom->createElement('success'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $XML->setAttribute("status", "SUCCESS");
        $dragon = $XML->appendChild($dom->createElement('dragon'));
        $dragon->setAttribute("strElement", $newElement);
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>