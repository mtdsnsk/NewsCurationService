<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Model\Multithreading;

Class Controller_Myutil_Parsegraph extends Controller {

    /*
     * 
     */
    public function action_execute($param, $date, $array_url = array()) {

        Log::debug('graph解析開始');
        $query = DB::select('url', 'id')->from('sk_news')
                ->where('pubdate', '>=', $date)
                ->and_where('category', $param)
                ->execute();
        
        Log::debug("graph解析対象データ:" . count($query));
        echo "graph解析対象データ:" . count($query);

        foreach ($query as $key => $data) {

            $id = $data['id'];
            $url = $data['url'];
            $th = Uri::base(false) . 'myutil/parsegraph/goodcount?' .
                    "id=$id" . '&' . "url=$url";

            // URLリスト作成
            array_push($array_url, $th);
        }
        
        // スレッドを実行
        Multithreading::exe_setnum($array_url);
        Log::debug('graph解析終了');
        return;
    }
    
    /*
     * 
     */
    public function action_fn($param, $date, $array_url = array()) {

        Log::debug('graph解析開始');
        $query = DB::select('url', 'id')->from('sk_news')
                ->where('pubdate', '>=', $date)
                ->and_where('category', $param)
                ->execute();
        
        Log::debug("graph解析対象データ:" . count($query));
        echo "graph解析対象データ:" . count($query);

        foreach ($query as $key => $data) {

            $id = $data['id'];
            $url = $data['url'];
            $th = Uri::base(false) . 'myutil/parsegraph/goodcount?' .
                    "id=$id" . '&' . "url=$url";
            // 実行
            file_get_contents($th);
        }
        
        Log::debug('graph解析終了');
        return;
    }

    public function action_goodcount($shares = 0, $comments = 0) {

        $id = Input::param('id');
        $url = Input::param('url');

        Log::debug("解析URL:" . $url);

        try {
            //$sp = explode('?', $url); //パラメータがある場合は区切り文字で分割            
            $graphurl = 'http://graph.facebook.com/'; // APIのURL
            //$apiurl = $graphurl . $sp[0];
            $apiurl = $graphurl . $url;
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