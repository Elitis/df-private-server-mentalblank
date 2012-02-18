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

    $char_result = mysql_query("SELECT * FROM df_characters WHERE id = '".$char_id."' LIMIT 1") or $error = 1;
    $char = mysql_fetch_assoc($char_result);
    $user_result = mysql_query("SELECT * FROM df_users WHERE id = '".$char['userid']."' AND pass = '".$UserToken."' LIMIT 1") or $error = 1;
    $user = mysql_num_rows($user_result);

    if($user != 1 || $error == 1){
        $reason = "Error!";
        $message = "There was an issue with your account... Please Login and try again";

        echo "<error>";
        echo "<info code=\"526.14\" reason=\"" . $reason . "\" message=\"" . $message . "\" action=\"None\"/>";
        echo "</error>";
        die();
    } else {
        $intItemID = $doc->getElementsByTagName('intItemID');
        $item_id = $intItemID->item(0)->nodeValue;

        $item_result = mysql_query("SELECT * FROM df_items WHERE ItemID = '".$item_id."' LIMIT 1") or $error = 1;
        $item = mysql_fetch_assoc($item_result) or $error = 1;

        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not purchase item.";

            $dom = new DOMDocument("1.0", "UTF-8");
            $XML = $dom->appendChild($dom->createElement('error'));
            $XML->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
            $info = $XML->appendChild($dom->createElement('info'));
            $info->setAttribute("code", "526.14");
            $info->setAttribute("reason", $reason);
            $info->setAttribute("message", $message);
            $info->setAttribute("action", "None");
        } else {
            $query = mysql_query("SELECT * FROM df_equipment WHERE ItemID = '".$item_id."' AND CharID = '".$char_id."' LIMIT 1") or $error = 1;
            $query_rows = mysql_num_rows($query);
            $query_fetched = mysql_fetch_assoc($query);

            if($item['Admin'] >= 1 && $user['access'] < 40) {
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
            } else {
                if($item['Currency'] == 2) {
                    $newgold = $char['gold'] - $item['Cost'];
                    $takegold = mysql_query("UPDATE df_characters SET gold='" . $newgold . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                } else if($item['Currency'] == 1) {
                    $newgold = $char['Coins'] - $item['Cost'];
                    $takegold = mysql_query("UPDATE df_characters SET Coins='" . $newgold . "' WHERE ID='" . $char_id . "' LIMIT 1") or $error = 1;
                }
                $additem = mysql_query("INSERT INTO `df_equipment` (`CharItemID`, `CharID`, `ItemID`, `Level`) VALUES ('', '".$char_id."', '".$item_id."', '".$item['Level']."')");
            }

            if ($error == 1)
            {
                $reason = "Error!";
                $message = "Could not purchase item.";

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
                $XML2 = $dom->appendChild($dom->createElement('success'));
                $XML2->setAttribute("xmlns:sql", "urn:schemas-microsoft-com:xml-sql");
                $XML2->setAttribute("status", "SUCCESS");
            }
        }
    }
    $dom->formatOutput = true;
    echo $dom->saveXML();
}
?>