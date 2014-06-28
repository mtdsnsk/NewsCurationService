<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Getimagefromurl2 extends Controller {

    // テスト用
    public function action_fn() {

        try {
            $id = Input::param('id');
            $url = Input::param('url');

            if ($url == '') {
                Log::debug('(エラー)　画像取得開始 url=' . $url);
                return;
            }
            $this->getimage($id, $url);
        } catch (Exception $ex) {
            Log::debug($ex->getMessage());
        }
    }

    public function action_getimage() {

        echo '開始<br>';
        $url = 'http://wpb.shueisha.co.jp/2014/06/12/31438/';
        $str_url = array();

        try {
            Log::debug('画像取得開始 url=' . $url);
            $image_array = $this->getimage_array($url);

            Log::debug('取得要素数:' . count($image_array));

            foreach ($image_array as $key => $row) {
                $image_size[$key] = $row['size'];
                $image_url[$key] = $row['url'];
            }

            // サイズ順に並べ替え
            Log::debug('サイズ順に並べ替え');
            if (count($image_array) > 0) {
                array_multisort($image_size, SORT_DESC, $image_url, SORT_ASC, $image_array);
            }

            // URL配列作成
            Log::debug('URL配列作成');
            foreach ($image_array as $data) {
                echo Html::img($data['url']);
                array_push($str_url, $data['url']);
            }

            if (is_array($str_url)) {
                Log::debug('配列を文字列にする');
                $string = implode(',', $str_url);
                Log::debug('画像取得OK url=' . $string);
            } else {
                Log::debug('画像データなし url=' . $url);
            }
        } catch (Exception $exc) {
            echo 'エラー<br>';
            Log::debug($exc->getMessage());
            Log::debug($exc->getTraceAsString());
        }
    }

    private function getimage($id, $url) {

        $image_count = 0;
        $max_image_count = 4;
        $str_url = array();
        Log::debug('画像取得開始 url=' . $url);

        // 画像URL配列作成
        $image_array = $this->getimage_array($url);
        foreach ($image_array as $key => $row) {
            $image_size[$key] = $row['size'];
            $image_url[$key] = $row['url'];
        }
        // サイズ順に並べ替え
        if (count($image_array) > 0) {
            array_multisort($image_size, SORT_DESC, $image_url, SORT_ASC, $image_array);
        }
        // URL配列作成
        foreach ($image_array as $data) {
            array_push($str_url, $data['url']);
            $image_count++;
            if ($image_count > $max_image_count) {
                break;
            }
        }
        if (count($str_url) > 0) {
            $string = implode(',', $str_url);
            Log::debug('画像取得OK url=' . $string);
            DB::update('sk_news')->set(array(
                        'image_url' => $string,
                        'updated_at' => date("Y-m-d H:i:s"),
                    ))->where('id', $id)
                    ->execute();
        }
    }

    private function getimage_array($url) {
        Log::debug("画像URL配列作成");
        $image_dat = array(); // リンクの画像データの配列を格納する
        if ($url == '') {
            Log::debug("エラー１");
            return $image_dat;
        }
        $exist = @file_get_contents($url, NULL, NULL, 1, 1);
        if (!$exist) {
            Log::debug("エラー２");
            return $image_dat;
        }
        // 必要文字配列取得
        $sp = $this->html_string_parse($url);
        if ($sp == NULL) {
            Log::debug("エラー３");
            return $image_dat;
        }
        foreach ($sp as $data) {
            $data_ = $this->push_images($data);
            if ($data_ != NULL) {
                array_push($image_dat, array(
                    'url' => $data_['url'], 'size' => $data_['size'],
                ));
            }
        }
        return $image_dat;
    }

    private function html_string_parse($url) {

        set_time_limit(1000);
        $exist = @file_get_contents($url, NULL, NULL, 1);
        if (!$exist) {
            Log::debug("存在しないURL:" . $url);
            return NULL;
        }

        $ex0 = preg_replace("/<a .*?(amazon|rakuten|valuecommerce|linksynergy|trafficgate).*?>.*?<\/a>/i", "", $exist);
        $ex1 = preg_replace("/<a .*?(\.html|\.js).*?>.*?<\/a>/i", "", $ex0);
        $ex2 = preg_replace("/[<>]/", " ", $ex1); // データ文字列を置換
        $sp1 = explode(" ", $ex2); // 文字列を分割
        $sp2 = array_unique($sp1); // 重複排除
        $sp3 = array_filter($sp2, 'strlen'); // null削除
        return $sp3;
    }

    private function push_images($data) {

        $kekka = '';
        // jpeg,pngを探す
        $bl = preg_match('/http.*(jpe?g|pne?g)/i', $data, $kekka);
        if (!$bl) {
            return NULL;
        }

        $exist = @file_get_contents($kekka[0], NULL, NULL, 1);
        if (!$exist) {
            Log::debug("画像が存在しない:" . $kekka[0]);
            return NULL;
        }
        $size = ceil(strlen($exist) / 1024); // ファイルサイズ
        list($width, $height) = getimagesize($kekka[0]); // 大きさ

        $dat['url'] = $kekka[0];
        $dat['size'] = $size;
        $ratio = $height / $width;

        if (($ratio > 0.6 && $ratio < 3) && $width > 120) {
            return $dat;
        }
    }

}
