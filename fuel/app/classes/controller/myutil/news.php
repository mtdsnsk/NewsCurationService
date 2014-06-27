<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_News extends Controller_Rest {

    public function get_news() {

        $no = Input::param('no');
        $array = array();
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
            if ($data['image_url'] != '') {
                $img = Html::img($data['image_url']);
            } else {
                $img = '';
                //$img = Html::img('http://cdn-ak.f.st-hatena.com/images/fotolife/e/emija/20140128/20140128163352.jpg');
            }

            $title = str_pad($data['title'], 120, "　");
            $sum = '<tr><td><a href="' . $data['url'] . '">' .
                    $img .
                    '<h4>' . $title . '</h4>' .
                    '<span class="news_from"><b>' . $data['from'] . '</b></span>' .
                    '</a></td></tr>';

            array_push($array, array(
                'title' => $data['title'],
                'url' => $data['url'],
                'image_url' => $data['image_url'],
                'description' => $data['description'],
                'sum' => $sum
            ));
        }
        //return $array;
        $this->response(array(
            'data' => $array,
            'empty' => null)
        );
    }

}
