<?php
$itemsloaded = array();
array_push($itemsloaded, '8');
array_push($itemsloaded, '8');
array_push($itemsloaded, '282');
$a2 = array_unique($itemsloaded);
//for ($i = 0; $i < count($itemsloaded); $i++) {
//
//}
for ($i = 0; $i < count($a2); $i++) {
    if('8' == $a2[$i]){
        echo("Found <br />");
    } else {
        echo("Not Found <br />");
    }
}
?>
