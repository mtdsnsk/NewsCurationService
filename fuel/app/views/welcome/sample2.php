<?php

$html = file_get_contents('http://rocketnews24.com');
$ex = preg_replace("/[<>]/", " ", $html);
$sp = explode(" ", $ex);

$sp = array_filter($sp, 'strlen');
foreach ($sp as $data) {
    $bl = preg_match('/http.*(jpe?g|png)/i', $data, $kekka);
    if ($bl) {
        echo $kekka[0];
        echo '<br>';
    }
}