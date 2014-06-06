<?php

$html = file_get_contents('http://rocketnews24.com/2014/06/06/450240/');
$xmlObject = simplexml_load_string($html);
var_dump($xmlObject);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

