<?php

//$html = 'http://rocketnews24.com/2014/06/06/450240/';
//$html = file_get_contents('http://blog.katty.in/');
$dom = file_get_html('http://www.google.co.jp/');
echo $dom->find('img',0);
/*
$domDocument = new DOMDocument();
$domDocument->loadHTML($html);
$xmlString = $domDocument->saveXML();

$xmlObject = simplexml_load_string($xmlString);
var_dump($xmlObject);

$array = json_decode(json_encode($xmlObject), true);

foreach ($array as $data) {
    echo '<hr>';
    var_dump($data);
}
*/
 

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

