<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fuel\Tasks;
use \Fuel\Core\Uri;

class GetNews {

    public function run($category, $date) {

        echo 'category:' . $category;
        echo ' date:' . $date;

        $base = 'http://localhost/sukima_server/public/';
        
        $filename1 = $base . "myutil/parsexml/fn/$category";
        $res1 = file_get_contents($filename1);
        
        $filename2 = $base . "myutil/parsegraph/fn/$category/$date";
        $res2 = file_get_contents($filename2);
        
        $filename3 = $base . "myutil/parsetweet/fn/$category/$date";
        $res3 = file_get_contents($filename3);
        
        $filename4 = $base . "myutil/getimagefromurl/fn/$category/$date";
        $res4 = file_get_contents($filename4);

        print_r($res1);
        print_r($res2);
        print_r($res3);
        print_r($res4);

    }
    
    public function image($param, $date) {

        echo 'category ' . $param;
        echo 'date ' . $date;

        $filename = "http://dev-tachiyomi.torico-tokyo.com/sukima_server/public/news/getimages/$param/$date";
        $res = file_get_contents($filename);

        print_r($res);
    }

}
