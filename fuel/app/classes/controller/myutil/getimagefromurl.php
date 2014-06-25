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
            //$html = 'http://dev-tachiyomi.torico-tokyo.com/commic_news/public/myutil/getimagefromurl/getimagefromurl?' .
            $th = 'http://localhost/sukima_server/public/myutil/getimagefromurl/getimagefromurl?' .
                    "id=$id" . '&' . "url=$url";
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

    public function action_testseikihyogen() {
        $max_size_url = $this->getimage('http://biz-journal.jp/business-topic/working/2014/03/post_13.html');
    }

    private function getimage($url) {
        $max_size = 0; // サイズを格納する
        $max_size_url = ''; //画像URLを格納する
        $image_dat = array(); // リンクの画像データの配列を格納する
        if ($url == '') {
            return NULL;
        }
        $exist = @file_get_contents($url, NULL, NULL, 1, 1);
        if (!$exist) {
            return NULL;
        }

        $html = file_get_contents($url); // リンク先のデータを取得する
        $ex = preg_replace("/<a +.*?>.*?<\/a>/", "", $html); // データ文字列を置換
        $ex = preg_replace("/[<>]/", " ", $html); // データ文字列を置換
        $sp1 = explode(" ", $ex); // 文字列を分割
        $sp2 = array_unique($sp1); // 重複排除
        $sp3 = array_filter($sp2, 'strlen'); // null削除

        foreach ($sp3 as $data) {
            try {
                $this->push_image_data($data, $max_size_url, $max_size);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        //echo Html::img($max_size_url);
        Log::info("画像取得元URL：$url 最大サイズURL：$max_size_url");
        return $max_size_url;
    }

    private function getimage_old($url) {
        $max_size = 0; // サイズを格納する
        $max_size_url = ''; //画像URLを格納する
        $image_dat = array(); // リンクの画像データの配列を格納する
        if ($url == '') {
            return NULL;
        }
        $exist = @file_get_contents($url, NULL, NULL, 1, 1);
        if (!$exist) {
            return NULL;
        }

        $html = file_get_contents($url); // リンク先のデータを取得する
        $ex = preg_replace("/<a>*.</a>/", " ", $html); // データ文字列を置換
        $ex = preg_replace("/[<>]/", " ", $html); // データ文字列を置換
        $sp1 = explode(" ", $ex); // 文字列を分割
        $sp2 = array_unique($sp1); // 重複排除
        $sp3 = array_filter($sp2, 'strlen'); // null削除
        foreach ($sp3 as $data) {
            try {
                $this->push_image_data($data, $max_size_url, $max_size);
            } catch (Exception $exc) {
                echo $exc->getMessage();
            }
        }
        Log::info("画像取得元URL：$url 最大サイズURL：$max_size_url");
        return $max_size_url;
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
        $dat['width'] = $width;
        $dat['height'] = $height;
        $dat['ratio'] = $height / $width;

        if (($dat['ratio'] > 0.6 && $dat['ratio'] < 3) && $dat['width'] > 120) {
            if ($size > $max_size) {
                // 最大サイズを更新したら書き換える
                $max_size = $size;
                $max_size_url = $dat['url'];
            }
        }
    }

    public function action_showimage($url) {
        $image_dat = $this->getimage($url, $max_size_url);
        return $max_size_url;
    }

    public function action_showimage2($url) {
        $image_dat = $this->getimage($url, $max_size_url);
        if ($image_dat == NULL) {
            echo "error:$url<br>";
            return NULL;
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

}
