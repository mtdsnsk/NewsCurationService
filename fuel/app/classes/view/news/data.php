<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View_News_Data extends ViewModel {

    public function view() {

        $no = Input::param('no');
        $date = Input::param('date');
        $order_by = Input::param('order_by');

        if ($order_by == 1) {
            $query = DB::select()->from('view_news_and_from')
                    ->where('pubdate', '>=', $date)
                    ->and_where('category', $no)
                    ->group_by('url')
                    ->order_by('pubdate', 'desc')
                    ->execute();
        } else if ($order_by == 2) {
            $query = DB::select()->from('view_news_and_from')
                    ->where('pubdate', '>=', $date)
                    ->and_where('category', $no)
                    ->group_by('url')
                    ->order_by('tweet_count', 'desc')
                    ->execute();
        } else if ($order_by == 3) {
            $query = DB::select()->from('view_news_and_from')
                    ->where('pubdate', '>=', $date)
                    ->and_where('category', $no)
                    ->group_by('url')
                    ->order_by('ranking1', 'desc')
                    ->execute();
        } else {
            // 指定なしの場合
            $query = DB::select()->from('view_news_and_from')
                    ->where('pubdate', '>=', date("Ymd"))
                    ->and_where('category', $no)
                    ->order_by('ranking1', 'desc')
                    ->order_by('ranking2', 'desc')
                    ->order_by('tweet_count', 'desc')
                    ->limit(50);
            if (count($query) < 10) {
                //データが少ない時は前の日も取得
                $date = strtotime("-1 day", date("Ymd", strtotime("-1 day")));
                $query = DB::select()->from('view_news_and_from')
                        ->where('pubdate', '>=', $date)
                        ->and_where('category', $no)
                        ->order_by('tweet_count', 'desc')
                        ->limit(50);
            }
        }

        $this->data = $this->create_html($query);
    }

    private function create_html($query) {

        $array = array();

        foreach ($query as $data) {

            $img = '';
            $title = $data['title'];

            if ($data['image_url'] != '') {
                $img_array = explode(',', $data['image_url']);
                $img = Html::img($img_array[0]);
                // 画像をリストで返す
                //foreach ($img_array as $key => $value) {
                //    $img = '<li>' . Html::img($value) . '</li>' . $img;
                //}
            }

            $sum = '<a href="' . $data['url'] . '">' . $img .
                    '<h4>' . $title . '</h4>' .
                    '<span class="news_from">' .
                    '<b>' . $data['from'] . '</b><br>' .
                    '<a><b>DATE</b>:' . $data['pubdate'] . '</a>' .
                    '<a><b>TWEET</b>:' . $data['tweet_count'] . '</a>' .
                    '<a><b>RANK1</b>:' . $data['ranking1'] . '</a>' .
                    '<a><b>RANK2</b>:' . $data['ranking2'] . '</a>' .
                    '<a><b>URL</b>:' . $data['url'] . '</a></br>' .
                    '</span>' .
                    '</a>';

            array_push($array, array(
                'title' => $data['title'],
                'url' => $data['url'],
                'image_url' => $data['image_url'],
                'description' => $data['description'],
                'pubdate' => $data['pubdate'],
                'tweet' => $data['tweet_count'],
                'ranking1' => $data['ranking1'],
                'ranking2' => $data['ranking2'],
                'sum' => $sum
            ));
        }
        return $array;
    }

}
