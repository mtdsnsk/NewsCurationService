<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Getfilelist extends Model {

    public static function functionName($param) {

        $list = array();

        if ($dir = opendir($param)) {
            while (($file = readdir($dir)) !== false) {
                if ($file != "." && $file != "..") {
                    //echo "$file\n";
                    array_push($list, $file);
                }
            }
            closedir($dir);
        }
        
        return $list;
    }

}
