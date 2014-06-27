<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Getimagefromurl extends Controller {

    public function action_fnimage() {

        require_once( APPPATH . 'classes/model/Multithreading.php');
        $array_url = array();

        Log::info('画像取得開始');
        $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->and_where_open()
                ->where('image_url', '')
                ->or_where('image_url', NULL)
                ->and_where_close()
                ->execute();
        Log::info('対象データ:' . count($query));
        echo '対象データ' . count($query) . '<br>';
        echo DB::last_query() . '<br>';

        foreach ($query as $key => $data) {

            $id = $data['id'];
            $url = $data['url'];
            $url_encode = urlencode($url);

            //$html = 'http://dev-tachiyomi.torico-tokyo.com/commic_news/public/myutil/getimagefromurl/getimagefromurl?' .
            $th = 'http://localhost/sukima_server/public/myutil/getimagefromurl2/fn?' .
                    "id=$id" . '&' . "url=$url_encode";
            Log::info('対象URL追加:' . urlencode($url_encode));
            array_push($array_url, $th);
        }
        Multithreading::execute($array_url);
        Log::info('画像取得終了');
    }

    function action_getimagefromurl() {
        //echo $_SERVER['DOCUMENT_ROOT'];
        $id = Input::param('id');
        $url = Input::param('url');
        try {
            Log::info("画像登録 ID=$id");
            // URL先のページにある一番大きな画像URLが返る      
            $image_url = $this->getimage($url);
            DB::update('sk_news')->set(array(
                        'image_url' => $image_url,
                        'updated_at' => date("Y-m-d H:i:s"),
                    ))->where('id', $id)
                    ->execute();
        } catch (Exception $exc) {
            Log::info('画像登録エラー');
            echo $exc->getTraceAsString();
        }
    }

    private function getimage($url) {
        $max_size = 0; // サイズを格納する
        $max_size_url = ''; //画像URLを格納する
        if ($url == '') {
            return NULL;
        }
        $exist = @file_get_contents($url, NULL, NULL, 1, 1);
        if (!$exist) {
            return NULL;
        }
        // URL先のHTMLファイルから必要なデータを残す
        $parse_data = $this->html_string_parse($url);

        foreach ($parse_data as $data) {
            $this->push_image_data($data, $max_size_url, $max_size);
        }
        Log::info("画像取得元URL：$url 最大サイズURL：$max_size_url");
        return $max_size_url;
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
        print_r($sp3);
        return $sp3;
    }

    private function push_image_data($data, &$max_size_url, &$max_size) {
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
        $dat['ratio'] = $height / $width;

        if (($dat['ratio'] > 0.6 && $dat['ratio'] < 3) && $dat['width'] > 120) {
            if ($size > $max_size) {
                // 最大サイズを更新したら書き換える
                $max_size = $size;
                $max_size_url = $dat['url'];
            }
        }
    }

}
