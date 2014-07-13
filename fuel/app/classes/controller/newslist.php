<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Newslist extends \Fuel\Core\Controller_Rest {
    /*
     * ニュース表示
     */

    public function action_index() {

        $category = Input::param('category'); //カテゴリー
        $date = Input::param('date'); //日付
        
        // 取得開始位置
        if (Input::param('offset') > 0) {
            $offset = Input::param('offset');
        } else {
            $offset = 0;
        }
        // 取得する最大記事数
        if (Input::param('limit') > 0) {
            $limit = Input::param('limit');
        } else {
            $limit = 50;
        } 

        $query = DB::select()->from('view_news_and_from')
                ->where('pubdate', '>=', $date)
                ->and_where('category', $category)
                ->order_by('power', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->execute();

        return $query->as_array();
    }

}
