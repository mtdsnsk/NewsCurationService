<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Model\Multithreading;

Class Controller_Myutil_Parsetweet extends Controller {

    public function action_fntweet() {

        $array_url = array();

        Log::debug('twitter解析開始');
        $query = DB::select('url', 'id', 'tweet_count')->from('sk_news')
                ->where('created_at', '>=', date("Ymd"))
                ->execute();
        Log::debug("対象データ:" . count($query));

        foreach ($query as $key => $data) {

            $id = $data['id'];
            $url = $data['url'];
            $tweet_count = $data['tweet_count'];
            $th = Uri::base(false) . 'myutil/parsetweet/tweetcount' .
                    "?id=$id" . "&tweet_count=$tweet_count" . "&url=$url";

            // URLリスト作成
            array_push($array_url, $th);
        }
        // スレッドを実行
        //Multithreading::execute($array_url);
        Multithreading::exe_setnum($array_url);

        Log::debug('twitter解析終了');

        return;
    }

    public function action_tweetcount() {

        $id = Input::param('id');
        $url = Input::param('url');
        $tweet_count = Input::param('tweet_count');

        try {
            $sp = explode('?', $url); //パラメータがある場合は区切り文字で分割            
            $twitterurl = 'http://urls.api.twitter.com/1/urls/count.json?url='; // APIのURL
            $apiurl = $twitterurl . $sp[0];
            $json1 = file_get_contents($apiurl); // JSON取得
            $json2 = mb_convert_encoding($json1, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'); // UTF-8に変換            
            $obj = json_decode($json2); // デコード
            // 配列にキーがあるか確認
            if (array_key_exists('count', $obj)) {
                $count = $obj->count; // つぶやき回数取得
            }
            // 更新処理
            DB::update('sk_news')->set(array(
                'tweet_count' => $count,
                'tweet_count_rise' => $count - $tweet_count,
            ))->where('id', $id)->execute();
            
        } catch (Exception $exc) {
            
            Log::debug("(エラー) つぶやき回数取得失敗 / 対象URL:$url" . $exc->getMessage());
            return "(エラー) つぶやき回数取得失敗 / 対象URL:$url" . $exc->getMessage();
        }
        
        Log::debug("つぶやき回数取得結果:$count / 対象URL:$url");
        return "つぶやき回数取得結果:$count / 対象URL:$url";
    }

    public function action_fngraph() {

        $array_url = array();

        Log::debug('graph解析開始');
        $query = DB::select('url', 'id')->from('sk_news')
                ->where('pubdate', '>=', date("Ymd"))
                ->execute();
        Log::debug("QUERY:" . DB::last_query());
        Log::debug("graph解析対象データ:" . count($query));
        echo "graph解析対象データ:" . count($query);

        foreach ($query as $key => $data) {

            $id = $data['id'];
            $url = $data['url'];
            $th = Uri::base(false) . 'myutil/parsetweet/goodcount?' .
                    "id=$id" . '&' . "url=$url";

            // URLリスト作成
            array_push($array_url, $th);
        }
        // スレッドを実行
        Multithreading::exe_setnum($array_url);
        Log::debug('graph解析終了');
        return;
    }

    public function action_goodcount() {

        $id = Input::param('id');
        $url = Input::param('url');
        $shares = 0;
        $comments = 0;

        Log::debug("解析URL:" . $url);

        try {
            $sp = explode('?', $url); //パラメータがある場合は区切り文字で分割            
            $graphurl = 'http://graph.facebook.com/'; // APIのURL
            $apiurl = $graphurl . $sp[0];
            Log::debug("実行URL:" . $apiurl);
            $json1 = file_get_contents($apiurl);
            $json2 = mb_convert_encoding($json1, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'); // UTF-8に変換            
            $obj = json_decode($json2); // デコード
            // 配列にキーがあるか確認
            if (array_key_exists('shares', $obj)) {
                $shares = $obj->shares; // つぶやき回数取得
            }
            if (array_key_exists('comments', $obj)) {
                $comments = $obj->comments; // つぶやき回数取得
            }
        } catch (Exception $exc) {
            Log::debug("(エラー) facebook graph取得失敗 / 対象URL:$url" . $exc->getMessage());
            return "(エラー) facebook graph取得失敗 / 対象URL:$url" . $exc->getMessage();
        }

        // 更新処理
        DB::update('sk_news')->set(array(
            'ranking1' => $shares,
            'ranking2' => $comments,
        ))->where('id', $id)->execute();
        
        Log::debug("シェア回数:$shares / コメント回数:$comments /　対象URL:$url");
        return "シェア回数:$shares / コメント回数:$comments /　対象URL:$url";
    }

}
