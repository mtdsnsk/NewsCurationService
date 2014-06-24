<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View_News_Data extends ViewModel {

    public function view() {

        $no = Input::param('no');
        $array = array();
        $query = DB::select()->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->and_where('category', $no)
                ->order_by('tweet_count', 'desc')
                ->limit(20)
                ->execute();
        foreach ($query as $data) {
            if($data['image_url'] != '') {
                $img = '';
            } else {
                $img = Html::img('http://cdn-ak.f.st-hatena.com/images/fotolife/e/emija/20140128/20140128163352.jpg');
            }

            $sum = '<tr><td><a href="' . $data['url'] . '">' .
                    $img .
                    '<p class="midashi">' . $data['title'] . '</p>' .
                    '提供元:<b>' . $data['guid'] . '</b>' .
                    '</a></td></tr>';

            array_push($array, array(
                'title' => $data['title'],
                'url' => $data['url'],
                'image_url' => $data['image_url'],
                'description' => $data['description'],
                'sum' => $sum
            ));
        }
        $this->data = $array;
        $this->title = array('absde', 'qwerty');
    }

}
