<?php

function writeInFile($outputPath, $outStr){
    $file = fopen($outputPath,"w");
    fwrite($file, $outStr);
    fclose($file);
}

function printJustArray($array){
    echo "<pre>";
    print_r($array);
    die;
}
