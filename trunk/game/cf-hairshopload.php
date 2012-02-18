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
    $intHairShopID = $doc->getElementsByTagName('intHairShopID');
    $HairShop_id = $intHairShopID->item(0)->nodeValue;

    $intCharID = $doc->getElementsByTagName('intCharID');
    $char_id = $intCharID->item(0)->nodeValue;
    $strGender = $doc->getElementsByTagName('strGender');
    $gender = $strGender->item(0)->nodeValue;

    $vendor_result = mysql_query("SELECT * FROM df_vendors WHERE HairShopID = '" . $HairShop_id . "' and ShopID = 'none' LIMIT 1") or $error = 1;
    $vendor = mysql_fetch_assoc($vendor_result) or $error = 1;
    $HairShop_result = mysql_query("SELECT * FROM df_hairs WHERE HairShopID = '".$HairShop_id."' LIMIT 1") or $error = 1;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE id = '" . $char_id . "' LIMIT 1") or $error = 1;
    $char = mysql_fetch_assoc($char_result);

    if($error == 1)
    {
        $reason = "Error!";
        $message = "Could not load hair shop";

        $dom = new DOMDocument("1.0", "UTF-8");
        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        if($vendor['admin'] != 1)
        {
            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('hairShop'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $shop = $XML->appendChild($dom->createElement('HairShop'));
            $shop->setAttribute("HairShopID", $HairShop_id);
            $shop->setAttribute("strHairShopName", $vendor['ShopName']);
            $shop->setAttribute("strFileName", $vendor['SwfFile']);
            while ($HairShop = mysql_fetch_array($HairShop_result))
            {
                $hair = $shop->appendChild($dom->createElement('hair'));
                $hair->setAttribute("HairID", $HairShop['HairID']);
                $hair->setAttribute("strName", $HairShop['HairName']);
                $hair->setAttribute("strFileName", "head/".$gender."/".$HairShop['HairSWF']);
                $hair->setAttribute("intFrame", "1");
                $hair->setAttribute("intPrice", $HairShop['Price']);
                $hair->setAttribute("strGender", $HairShop['Gender']);
                $hair->setAttribute("RaceID", "1");
                $hair->setAttribute("bitEarVisible", $HairShop['EarVisible']);
            }
        } else if($vendor['admin'] == 1){
            $reason = "Error!";
            $message = "Could not load hair shop data";
                    
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
