<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Multithreading extends Model{

    public static function _init() {
        // これはクラスをロードしているときに呼び出されます。
        set_time_limit(6000);
    }

    public function action_index() {
        echo 'Controller_Myutil_Multithreading';
    }

    public static function execute($url_list) {

        header('Content-type:text/html; charset=UTF-8');

        //開始時間取得
        $time = time();
        //実行
        $res = Multithreading::fetch_multi_url($url_list);

        //結果出力
        echo '実行結果:<pre>';
        print_r($res);
        echo '</pre>';

        //実行時間
        echo '--<br />time:' . (time() - $time) . ' sec';
    }

    private static function fetch_multi_url($url_list, $timeout = 0) {
        $mh = curl_multi_init();

        foreach ($url_list as $i => $url) {
            $conn[$i] = curl_init($url);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn[$i], CURLOPT_FAILONERROR, 1);
            curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($conn[$i], CURLOPT_MAXREDIRS, 3);

            //SSL証明書を無視
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, false);

            //タイムアウト
            if ($timeout) {
                curl_setopt($conn[$i], CURLOPT_TIMEOUT, $timeout);
            }

            curl_multi_add_handle($mh, $conn[$i]);
        }

        //URLを取得
        //すべて取得するまでループ
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
        foreach ($url_list as $i => $url) {
            if (($err = curl_error($conn[$i])) == '') {
                $res[$i] = curl_multi_getcontent($conn[$i]);
            } else {
                echo '取得に失敗しました:' . $url_list[$i] . '<br />';
            }
            curl_multi_remove_handle($mh, $conn[$i]);
            curl_close($conn[$i]);
        }
        curl_multi_close($mh);

        return $res;
    }

}
