<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View_Test_Sample1 extends ViewModel {

    public function view() {
        //$this->mainmenu = 'View Welcome Test 実行';
        //$this->mainmenu1 = Response::forge(ViewModel::forge('welcome/getrss'));
        //$this->mainmenu2 = Response::forge(ViewModel::forge('welcome/getrss'));
        $this->title = array('absde', 'qwerty');
    }

    public function top() {
        //$this->mainmenu = 'View Welcome Test 実行';
        //$this->mainmenu1 = Response::forge(ViewModel::forge('welcome/getrss'));
        //$this->mainmenu2 = Response::forge(ViewModel::forge('welcome/getrss'));
        $this->title = array('absde', 'qwerty');
    }

    public function sports() {
        $no = Input::param('no');
        $array = array();
        $query = DB::select()->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->and_where('category', $no)
                ->order_by('tweet_count', 'desc')
                ->limit(20)
                ->execute();
        foreach ($query as $data) {
            array_push($array, array(
                'title' => $data['title'],
                'url' => $data['url'],
                'image_url' => $data['image_url'],
                'description' => $data['description'],
                'sum' => '<tr><td><a href="' . $data['url'] . '">' .
                '<p class="midashi">' . $data['title'] . '</p>' .
                $data['description'] .
                '<br>' .
                '提供元:<b>' . $data['description'] . '</b>' .
                '</a></td></tr>'
            ));
        }
        $this->data = $array;
        $this->title = array('absde', 'qwerty');
    }

    public function goship() {
        //$this->mainmenu = 'View Welcome Test 実行';
        //$this->mainmenu1 = Response::forge(ViewModel::forge('welcome/getrss'));
        //$this->mainmenu2 = Response::forge(ViewModel::forge('welcome/getrss'));
        $this->title = array('absde', 'qwerty');
    }

    public function sample() {
        //$this->mainmenu = 'View Welcome Test 実行';
        //$this->mainmenu1 = Response::forge(ViewModel::forge('welcome/getrss'));
        //$this->mainmenu2 = Response::forge(ViewModel::forge('welcome/getrss'));
        $this->title = array('absde', 'qwerty');
    }

}
