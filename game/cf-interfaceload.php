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

    $intInterfaceID = $doc->getElementsByTagName('intInterfaceID');
    $interface_id = $intInterfaceID->item(0)->nodeValue;

    $interface_result = mysql_query("SELECT * FROM df_interface WHERE InterfaceID = '" . $interface_id . "' LIMIT 1") or $error = 1;
    $interface = mysql_fetch_assoc($interface_result) or $error = 1;

    $dom = new DOMDocument("1.0", "UTF-8");
    if ($error == 1)
    {
        $reason = "Error!";
        $message = "There was an error loading the interface.";

        $XML = $dom->appendChild($dom->createElement('error'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $info = $XML->appendChild($dom->createElement('info'));
        $info->setAttribute("code", "526.14");
        $info->setAttribute("reason", $reason);
        $info->setAttribute("message", $message);
        $info->setAttribute("action", "None");
    } else {
        $XML = $dom->appendChild($dom->createElement('intrface'));
        $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $intrface = $XML->appendChild($dom->createElement('intrface'));
        $intrface->setAttribute("strFileName", $interface['InterfaceSWF']);

        $XML2 = $dom->appendChild($dom->createElement('success'));
        $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
        $XML2->setAttribute("status", "SUCCESS");
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>

