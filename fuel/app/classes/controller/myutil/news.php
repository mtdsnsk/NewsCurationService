<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_News extends Controller_Rest {

    public function get_data() {
        $url = 'http://feeds.cinematoday.jp/~r/cinematoday_update/~3/-Ouk92AkBzs/N0064303';
        require_once ( APPPATH . 'classes/model/Getheaders.php');
        $header = Getheaders::get_plain_header($url);
        print_r($header);
    }

    public function get_news() {

        $no = Input::param('no');

        $query = DB::select()->from('view_news_and_from')
                ->where('created_at', '>=', date("Ymd"))
                ->and_where('category', $no)
                ->order_by('tweet_count', 'desc')
                ->group_by('url')
                ->limit(50)
                ->execute();
        if (count($query) < 10) {
            //データが少ない時は前の日も取得
            $date = strtotime("-1 day", date("Ymd", strtotime("-1 day")));
            $query = DB::select()->from('view_news_and_from')
                    ->where('created_at', '>=', $date)
                    ->and_where('category', $no)
                    ->order_by('tweet_count', 'desc')
                    ->group_by('url')
                    ->limit(50)
                    ->execute();
        }

        foreach ($query as $data) {
            $img = '';
            if ($data['image_url'] != '') {
                $img_array = explode(',', $data['image_url']);
                foreach ($img_array as $key => $value) {
                    $img = Html::img($value) . '<br>' . $img;
                }
            }

            $title = str_pad($data['title'], 120, "　");

            $sum = '<tr><td><a href="' . $data['url'] . '">' .
                    '<p id="slideshow">' .
                    $img .
                    '<p>' .
                    '<h4>' . $title . '</h4>' .
                    '<span class="news_from"><b>' . $data['from'] . '</b></span>' .
                    '</a></td></tr>';

            $id = $data['id'];
            $array[$id] = array(
                'title' => $id,
                'title' => $data['title'],
                'url' => $data['url'],
                'image_url' => $data['image_url'],
                'description' => $data['description'],
                'sum' => $sum
            );
        }

        return $query->as_array();
    }

}
