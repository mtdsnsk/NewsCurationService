<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View_News_Data extends ViewModel {

    public function view() {

        $no = Input::param('no');
        
        $query = DB::select()->from('view_news_and_from')
                ->where('created_at', '>=', date("Ymd"))
                ->and_where('category', $no)
                ->order_by('ranking1', 'desc')
                ->order_by('ranking2', 'desc')
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
        
        $this->data = $this->create_html($query);
    }

    private function create_html($query) {
        
        $array = array();
        
        foreach ($query as $data) {

            $img = '';
            $title = $data['title'];
            
            if ($data['image_url'] != '') {
                $img_array = explode(',', $data['image_url']);
                foreach ($img_array as $key => $value) {
                    $img = '<li>' . Html::img($value) . '</li>' . $img;
                }
            }

            $sum = '<tr><td><a href="' . $data['url'] . '">' .
                    '<ul class="bxslider">' . $img . '</ul>' .
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
        return $array;
    }

}
