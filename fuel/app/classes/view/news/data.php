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
        $key = Input::param('key');

        switch ($order_by) {
            case 1:
                $order_column_name = 'pubdate';
                break;
            case 2:
                $order_column_name = 'tweet_count';
                break;
            case 3:
                $order_column_name = 'ranking1';
                break;

            default:
                $order_column_name = 'power';
                break;
        }

        if ($key != '') {
            // キーワード検索
            $query = DB::select()->from('view_news_and_from')
                    ->where('title', 'like', '%' . $key . '%')
                    ->order_by($order_column_name, 'desc')
                    ->execute();
        } else if ($order_by != '') {
            // 並び替え指定あり
            $query = DB::select()->from('view_news_and_from')
                    ->where('pubdate', '>=', $date)
                    ->and_where('category', $no)
                    ->group_by('url')
                    ->order_by($order_column_name, 'desc')
                    ->execute();
        } else {
            // 指定なしの場合
            $query = DB::select()->from('view_news_and_from')
                    ->where('pubdate', '>=', $date)
                    ->and_where('category', $no)
                    ->order_by($order_column_name, 'desc')
                    ->limit(50)
                    ->execute();
            if (count($query) < 20) {
                //データが少ない時は前日のデータからも取得
                $date = strtotime("-1 day", $date);
                $query = DB::select()->from('view_news_and_from')
                        ->where('pubdate', '>=', $date)
                        ->and_where('category', $no)
                        ->order_by($order_column_name, 'desc')
                        ->limit(50)
                        ->execute();
            }
        }

        Log::debug($key);
        Log::debug(DB::last_query());
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
                    //'<a><b>DATE</b>:' . $data['pubdate'] . '</a>' .
                    //'<a><b>TWEET</b>:' . $data['tweet_count'] . '</a>' .
                    //'<a><b>RANK1</b>:' . $data['ranking1'] . '</a>' .
                    //'<a><b>RANK2</b>:' . $data['ranking2'] . '</a>' .
                    //'<a><b>URL</b>:' . $data['url'] . '</a></br>' .
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
