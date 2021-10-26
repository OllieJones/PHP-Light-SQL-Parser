<?php


require_once ("../src/mysqlstatus.php");
$testfileName = realpath('imfsstats.json');
$stream  = fopen($testfileName, 'r');
$data = json_decode(fread($stream, filesize($testfileName)), true);
foreach ($data as $item) {
    $k =  $item;
}