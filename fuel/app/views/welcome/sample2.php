<?php


$html = file_get_contents('http://rocketnews24.com');
$ex = preg_replace("/[<>]/", " ", $html);
$sp = explode(" ", $ex);
$sp2 = array_unique($sp);
$sp3 = array_filter($sp2, 'strlen');
foreach ($sp3 as $data) {
    $bl = preg_match('/http.*(jpe?g|png)/i', $data, $kekka);
    if ($bl) {
        $img = file_get_contents($kekka[0]); 
        $size = ceil(strlen($img)/1024);
        //echo 'size:' . $size . 'KB/';
        //echo $kekka[0] . '<br>';
        $fn = explode("/", $kekka[0]);
        file_put_contents('/Applications/XAMPP/htdocs/comicnews/xml/' . $fn[count($fn) - 1] , $img);
    }
}