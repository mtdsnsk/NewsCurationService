<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fuel\Tasks;

class GetNews {

    public function run($param, $date) {

        echo 'category ' . $param;
        echo 'date ' . $date;

        $filename = "http://dev-tachiyomi.torico-tokyo.com/sukima_server/public/news/get/$param/$date";
        $res = file_get_contents($filename);

        print_r($res);
    }
    
    public function image($param, $date) {

        echo 'category ' . $param;
        echo 'date ' . $date;

        $filename = "http://dev-tachiyomi.torico-tokyo.com/sukima_server/public/news/getimages/$param/$date";
        $res = file_get_contents($filename);

        print_r($res);
    }

}
