<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use \Model\Getheaders;

Class Controller_Myutil_News extends Controller_Rest {

    public function get_data() {
        $url = 'http://feeds.cinematoday.jp/~r/cinematoday_update/~3/-Ouk92AkBzs/N0064303';
        require_once ( APPPATH . 'classes/model/Getheaders.php');
        $header = Getheaders::get_plain_header($url);
        print_r($header);
    }

    public function get_news() {

        $no = Input::param('no');
        $date = Input::param('date');
        $order_by = Input::param('$order_by');

        if ($date == '') {
            $date = date("Ymd");
        }
                
        switch ($order_by) {
            case 1:
                $order = 'tweet_count';
                break;

            case 2:
                $order = 'ranking1';
                break;

            case 3:
                $order = 'ranking2';
                break;

            default:
                $order = 'pubdate';
                break;
        }

        $query = DB::select()->from('view_news_and_from')
                ->where('pubdate', '=', $date)
                ->and_where('category', $no)
                ->order_by($order, 'desc')
                ->execute();

        Log::debug(DB::last_query());
        return $query->as_array();
    }

}
