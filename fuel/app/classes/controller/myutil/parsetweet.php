<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Parsetweet extends Controller {

    public function action_fn() {
        Log::info('twitter解析開始');
        $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->execute();
        foreach ($query as $data) {
            $count = $this->tweetcount($data['url']);
            Log::info('結果:' . $count . '/対象URL:' . $data['url']);
            DB::update('sk_news')->set(array(
                'tweet_count' => $count,
                'tweet_count_rise' => $count - $data['tweet_count'],
            ))->where('id', $data['id'])->execute();
        }
        Log::info('twitter解析終了');
        return;
    }

    public function action_fnimage() {
        $obj = new Controller_Myutil_Getimagefromurl();
        
        Log::info('画像取得開始');
        $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->execute();
        foreach ($query as $data) {
            /*$count = $this->tweetcount($data['url']);
            Log::info('結果:' . $count . '/対象URL:' . $data['url']);
            DB::update('sk_news')->set(array(
                'tweet_count' => $count,
                'tweet_count_rise' => $count - $data['tweet_count'],
            ))->where('id', $data['id'])->execute();
             * 
             */
            $obj->action_showimage($data['url']);
        }
        Log::info('画像取得終了');
        return;
    }

    private function tweetcount($url) {

        $count = 0;
        try {
            //パラメータがある場合は削除
            $sp = explode('?', $url);
            // APIのURL
            $twitterurl = 'http://urls.api.twitter.com/1/urls/count.json?url=';
            $twitterapiurl = $twitterurl . $sp[0];
            $json1 = file_get_contents($twitterapiurl);
            // 文字化けするかもしれないのでUTF-8に変換
            $json2 = mb_convert_encoding($json1, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            // デコード
            $obj = json_decode($json2);
            // 配列にキーがあるか確認
            if (array_key_exists('count', $obj)) {
                $count = $obj->count;
            }
        } catch (Exception $exc) {
            echo '<br>get_twitter_count<br>エラーメッセージ=' . $exc->getMessage() . '<br>';
        }
        echo 'tweet回数:' . $count;
        return $count;
    }

}
