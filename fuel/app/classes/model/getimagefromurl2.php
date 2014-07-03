<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;

Class Getimagefromurl extends \Model {

    // テスト用
    public function execute() {
        echo '開始<br>';
        //$url = 'http://techbooster.jpn.org/andriod/application/2199/';
        //$url = 'http://biz-journal.jp/business-topic/working/2014/03/post_13.html';
        //$url = 'http://gigazine.net';
        //$url = 'http://gigazine.net/news/20140627-lametric-smart-display/';
        try {
            $url = 'http://blog.livedoor.jp/dqnplus/';
            $image_array = $this->getimage_array($url);
            echo '結果表示<br>';
            foreach ($image_array as $data) {
                if ($data != NULL) {
                    echo Html::img($data);
                }
            }
        } catch (Exception $ex) {
            
        }
    }

    private function getimage_array($url) {
        echo '画像取得<br>';
        $image_dat = array(); // リンクの画像データの配列を格納する
        if ($url == '') {
            echo 'エラー１<br>';
            return $image_dat;
        }
        $exist = @file_get_contents($url, NULL, NULL, 1, 1);
        if (!$exist) {
            echo 'エラー２<br>';
            return $image_dat;
        }
        $sp = $this->html_string_parse($url);

        foreach ($sp as $data) {
            // 画像URLの配列作成
            array_push($image_dat, $this->push_images($data));
        }
        return $image_dat;
    }

    private function html_string_parse($url) {
        echo '文字列分割<br>';
        $html = file_get_contents($url); // リンク先のデータを取得する

        $ex0 = preg_replace("/<a .*?(amazon|rakuten).*?>.*?<\/a>/i", "", $html);
        $ex1 = preg_replace("/<a .*?(html.*?|.*?\.js).*?>.*?<\/a>/i", "", $ex0);
        $ex2 = preg_replace("/[<>]/", " ", $ex1); // データ文字列を置換
        $sp1 = explode(" ", $ex2); // 文字列を分割
        $sp2 = array_unique($sp1); // 重複排除
        $sp3 = array_filter($sp2, 'strlen'); // null削除

        return $sp3;
    }

    private function push_images($data) {

        $kekka = '';
        $bl = preg_match('/http.*(jpe?g|pne?g|gif)/i', $data, $kekka); // jpeg,pngを探す
        if (!$bl) {
            return NULL;
        }
        if (!is_array($kekka)) {
            return NULL;
        }

        $img = file_get_contents($kekka[0]); // 最初の要素]
        $url = $kekka[0];
        list($width, $height) = getimagesize($kekka[0]); // 大きさ
        $ratio = $height / $width;

        if (($ratio > 0.6 && $ratio < 3) && $width > 120) {
            echo $url . '<br>';
            return $url;
        }
    }

}
