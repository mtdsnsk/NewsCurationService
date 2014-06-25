<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Getimagefromurl extends Controller {

    public function action_fnimage() {

        $mh = curl_multi_init(); // スレッド関数初期化
        Log::info('画像取得開始');
        $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->execute();
        //開始時間取得
        $time = time();
        foreach ($query as $key => $data) {
            Log::info('KEY=' . $key);
            $timeout = 60;
            $id = $data['id'];
            $url = $data['url'];
            $html = 'http://localhost/sukima_server/public/myutil/getimagefromurl/getimagefromurl?' .
                    "id=$id" . '&' . "url=$url";
            $conn[$key] = curl_init($html); // コネクション
            curl_setopt($conn[$key], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn[$key], CURLOPT_FAILONERROR, 1);
            curl_setopt($conn[$key], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($conn[$key], CURLOPT_MAXREDIRS, 3);

            //SSL証明書を無視
            curl_setopt($conn[$key], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn[$key], CURLOPT_SSL_VERIFYHOST, false);

            //タイムアウト
            if ($timeout) {
                curl_setopt($conn[$key], CURLOPT_TIMEOUT, $timeout);
            }
            curl_multi_add_handle($mh, $conn[$key]);
        }

        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active and $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        if ($mrc != CURLM_OK) {
            echo '読み込みエラーが発生しました:' . $mrc;
        }

        //ソースコードを取得
        $res = array();
        foreach ($query as $key => $data) {
            if (($err = curl_error($conn[$key])) == '') {
                $res[$key] = curl_multi_getcontent($conn[$key]);
            } else {
                echo '取得に失敗しました:' . '<br />';
            }
            curl_multi_remove_handle($mh, $conn[$key]);
            curl_close($conn[$key]);
        }
        curl_multi_close($mh);

        //実行時間
        Log::info('time:' . (time() - $time) . ' sec');
        Log::info('画像取得終了');
        return;
    }

    function action_getimagefromurl() {
        //echo $_SERVER['DOCUMENT_ROOT'];
        $id = Input::param('id');
        $url = Input::param('url');
        try {
            Log::info("画像登録 ID=$id");
            $image_url = $this->action_showimage($url);
            DB::update('sk_news')->set(array(
                'image_url' => $image_url,
                'updated_at' => date("Y-m-d H:i:s"),
            ))->where('id', $id)->execute();
        } catch (Exception $exc) {
            Log::info('画像登録エラー');
            echo $exc->getTraceAsString();
        }
    }

    function get_image($data) {
        $url = Input::param('url');
        try {
            $id = $data['id'];
            Log::info("画像登録 ID=$id");
            $image_url = $this->action_showimage($data['url']);
            DB::update('sk_news')->set(array(
                'image_url' => $image_url,
                'updated_at' => date("Y-m-d H:i:s"),
            ))->where('id', $data['id'])->execute();
        } catch (Exception $exc) {
            Log::info('画像登録エラー');
            echo $exc->getTraceAsString();
        }
    }

    public function action_showimage($url) {
        $image_dat = Controller_Myutil_Getimagefromurl::getimage($url, $max_size_url);
        if ($image_dat == null) {
            echo "error:$url<br>";
            return null;
        }
        foreach ($image_dat as $data) {
            echo html_tag('img', array(
                'src' => $data['url'],
            ));
            echo 'url:<b>' . $data['url'] . '</b><br>';
            echo 'size:<b>' . $data['size'] . '</b><br>';
            echo 'width:<b>' . $data['width'] . '</b><br>';
            echo 'height:<b>' . $data['height'] . '</b><br>';
            echo 'ratio:<b>' . $data['ratio'] . '</b><br>';
            echo '<hr>';
        }
        return $max_size_url;
    }

    private function getimage($url, &$max_size_url) {
        $max_size = 0; // サイズを格納する
        $max_size_url = '';
        $image_dat = array(); // リンクの画像データの配列を格納する
        if ($url == '') {
            return NULL;
        }
        $exist = @file_get_contents($url, NULL, NULL, 1, 1);
        if (!$exist) {
            return NULL;
        }
        $html = file_get_contents($url); // リンク先のデータを取得する
        $ex = preg_replace("/[<>]/", " ", $html); // データ文字列を置換
        $sp1 = explode(" ", $ex); // 文字列を分割
        $sp2 = array_unique($sp1); // 重複排除
        $sp3 = array_filter($sp2, 'strlen'); // null削除
        foreach ($sp3 as $data) {
            try {
                Controller_Myutil_Getimagefromurl::push_image_data($image_dat, $max_size, $max_size_url, $data);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        echo "画像取得元URL：$url<br>最大サイズURL：$max_size_url<br>";
        return $image_dat;
    }

    private function push_image_data(&$image_dat, &$max_size, &$max_size_url, $data) {
        $kekka = '';
        $bl = preg_match('/http.*(jpe?g|png)/i', $data, $kekka); // jpeg,pngを探す
        if (!$bl) {
            return;
        }
        if (!is_array($kekka)) {
            return;
        }
        $img = file_get_contents($kekka[0]); // 最初の要素
        $size = ceil(strlen($img) / 1024); // ファイルサイズ
        list($width, $height) = getimagesize($kekka[0]); // 大きさ
        $dat['url'] = $kekka[0];
        $dat['size'] = $size;
        $dat['width'] = $width;
        $dat['height'] = $height;
        $dat['ratio'] = $height / $width;
        $dat['image'] = $kekka[0];
        if ($size > $max_size) {
            $max_size = $size;
            $max_size_url = $dat['url'];
        }
        if (($dat['ratio'] > 0.6 && $dat['ratio'] < 2) && $dat['width'] > 120) {
            array_push($image_dat, $dat);
        }
    }

    // 並列実行
}
