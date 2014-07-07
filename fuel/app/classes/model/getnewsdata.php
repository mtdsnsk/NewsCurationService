<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//namespace Model;
//use Controller_News;

Class Model_Getnewsdata extends Model {

    public static function ex() {
        echo 'TEST';
    }

    public static function execute($param, $date, $array = array()) {
        echo "カテゴリー:$param 日付:$date";
        Controller_News::action_get($param, $date);
    }

}
