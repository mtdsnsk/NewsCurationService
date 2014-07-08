<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Model\Multithreading;

Class Controller_Myutil_Getimagefromurl extends Controller {
    /*
     * スレッドの画像処理
     */
    
    /*
      public function action_execute($param, $date, $array_url = array()) {

      Log::info("画像取得開始 カテゴリー:$param 日付$date");

      $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
      ->where('created_at', '>', $date)
      ->and_where('category', $param)
      ->and_where('image_url', '')
      ->execute();

      Log::info('対象データ:' . count($query));

      foreach ($query as $data) {

      $id = $data['id'];
      $url = $data['url'];
      $url_encode = urlencode($url);
      $th = Uri::base(false) . 'myutil/getimagefromurl2/fn?' .
      "id=$id" . '&' . "url=$url_encode";
      Log::info('対象URL追加:' . urlencode($url_encode));
      array_push($array_url, $th);
      }

      Multithreading::execute($array_url);
      Log::info('画像取得終了');
      }
     */

    /*
     * スレッドでない画像処理
     */
    public function action_execute($param, $date, $array_url = array()) {

        Log::info("画像取得開始 カテゴリー:$param 日付$date");
        
        $query = DB::select('url', 'id', 'power')->from('view_news_and_from')
                ->where('created_at', '>=', $date)
                ->and_where('category', $param)
                ->and_where('image_url', '')
                ->order_by('power', 'DESC')
                ->limit(50)
                ->execute();

        Log::info('対象データ:' . count($query));

        foreach ($query as $key => $data) {

            $id = $data['id'];
            $url = $data['url'];
            $url_encode = urlencode($url);
            $th = Uri::base(false) . 'myutil/getimagefromurl2/fn?' .
                    "id=$id" . '&' . "url=$url_encode";

            Log::info("画像取得URL:$url"); // ログ
            file_get_contents($th); // 取得
        }

        Log::info('画像取得終了');
    }

}
