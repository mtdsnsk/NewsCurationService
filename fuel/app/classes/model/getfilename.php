<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;

class Getfilename extends \Model {

    public static function getlist($param) {

        $list = array();

        if ($dir = opendir($param)) {
            while (($file = readdir($dir)) !== false) {
                if ($file != "." && $file != "..") {
                    array_push($list, $file);
                }
            }
            closedir($dir);
        }
        
        return $list;
    }

}
