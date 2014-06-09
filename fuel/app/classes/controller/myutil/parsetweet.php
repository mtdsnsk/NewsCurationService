<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Parsetweet extends Controller {

    public static function action_fn() {
        Log::info('twitter解析開始');
        $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
                //->where('rsslist_id', 50)
                ->execute();
        foreach ($query as $data) {
            $count = Controller_Myutil_Myfunc::action_tweetcount($data['url']);
            Log::info('結果:' . $count . '/対象URL:' . $data['url']);
            DB::update('sk_news')->set(array(
                'tweet_count' => $count,
                'tweet_count_rise' => $count - $data['tweet_count'],
            ))->where('id', $data['id'])->execute();
        }
        return;
    }
    
    public function action_test() {
        $url = 'http://netouyonews.net/archives/8402491.html';
        $count = Controller_Myutil_Myfunc::action_tweetcount($url);
        echo '<br>tweet回数:' .$count;
        return;
    }

    public static function action_tweetcount($url) {

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
        echo 'tweet回数:' .$count;
        return $count;
    }
    
    public static function action_phpintmax(){
        echo 'PHP_INT_MAX:';
        var_dump(PHP_INT_MAX); 
    }

}
