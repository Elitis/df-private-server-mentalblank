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
    $drag_id = $intCharID->item(0)->nodeValue;

    $strToken = $doc->getElementsByTagName('strToken');
    $UserToken = $strToken->item(0)->nodeValue;

    $intColorSkin = $doc->getElementsByTagName('intColorSkin');
    $ColorSkin = $intColorSkin->item(0)->nodeValue;

    $intColorWing = $doc->getElementsByTagName('intColorWing');
    $ColorWing = $intColorWing->item(0)->nodeValue;

    $intColorEye = $doc->getElementsByTagName('intColorEye');
    $ColorEye = $intColorEye->item(0)->nodeValue;

    $intColorHorn = $doc->getElementsByTagName('intColorHorn');
    $ColorHorn = $intColorHorn->item(0)->nodeValue;

    $intWings = $doc->getElementsByTagName('intWings');
    $Wings = $intWings->item(0)->nodeValue;

    $intHeads = $doc->getElementsByTagName('intHeads');
    $Heads = $intHeads->item(0)->nodeValue;

    $intTails = $doc->getElementsByTagName('intTails');
    $Tails = $intTails->item(0)->nodeValue;

    $char_result = mysql_query("SELECT * FROM df_characters WHERE ID = '" . $drag_id . "' LIMIT 1") or $error = 1;
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
    }

    $drag_result = mysql_query("SELECT * FROM df_dragons WHERE CharDragID = '" . $drag_id . "' LIMIT 1") or $error = 1;
    $drag = mysql_fetch_assoc($drag_result);

    if ($error == 1)
    {
        $reason = "Error!";
        $message = "Could not Customize dragon.";

        echo "<error>";
        echo "<info code=\"526.14\" reason=\"" . $reason . "\" message=\"" . $message . "\" action=\"None\"/>";
        echo "</error>";
        die();
    } else {
        $change_intColorSkin = mysql_query("UPDATE df_dragons SET intColorSkin='".$ColorSkin."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        $change_intColorWing = mysql_query("UPDATE df_dragons SET intColorWing='".$ColorWing."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        $change_intColorEye = mysql_query("UPDATE df_dragons SET intColorEye='".$ColorEye."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        $change_intColorHorn = mysql_query("UPDATE df_dragons SET intColorHorn='".$ColorHorn."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        $change_intWings = mysql_query("UPDATE df_dragons SET intWings='".$Wings."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        $change_intHeads = mysql_query("UPDATE df_dragons SET intHeads='".$Heads."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        $change_intTails = mysql_query("UPDATE df_dragons SET intTails='".$Tails."' WHERE CharDragID='" . $drag_id . "' LIMIT 1") or $error = 1;
        if ($error == 1)
        {
            $reason = "Error!";
            $message = "Could not Customize dragon.";

            echo "<error>";
            echo "<info code=\"526.14\" reason=\"" . $reason . "\" message=\"" . $message . "\" action=\"None\"/>";
            echo "</error>";
            die();
        } else {
            echo "<success>";
            echo("<dragon intColorSkin='".$ColorSkin."' intColorWing='".$ColorWing."' intColorEye='".$ColorEye."' intColorHorn='".$ColorHorn."' intWings='".$Wings."' intHeads='".$Heads."' intTails='".$Tails."'/>");
            echo "<status=\"SUCCESS\"/>";
            echo "</success>";
        }
    }
}
?>
