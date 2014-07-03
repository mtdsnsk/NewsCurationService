<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Getheaders extends Model {

    public static function get_header($param) {
        // リダイレクト先のURLを取得する
        $kekka = '';
        $headers = get_headers($param);
        if ($headers == NULL) {
            // エラーの場合引数をそのまま処理
            return $param;
        } else {
            foreach ($headers as $key => $value) {
                // 文字列に'html://'が含まれるか
                if (strstr($value, 'Location:') && strstr($value, 'http://')) {
                    // 含まれる時は取得する
                    $kekka = $value;
                    break;
                }
            }
        }

        if ($kekka != '') {
            $html_str = explode(' ', $kekka);
            // URL部分だけを返却
            //echo 'html:' . $html_str[1] . '<br>';       
            return ($html_str[1]);
        }

        // 引数の文字列をそのまま返却
        return $param;
    }

    public static function get_plain_header($param) {
        // リダイレクト先のURLを取得する
        $headers = get_headers($param);
        if ($headers == NULL) {
            // エラーの場合引数をそのまま処理
            return $param;
        } else {
            $html = explode(' ', $headers[1]);
            // echo 'html:' . $html[1] . '<br>';
            // URL部分だけを返却
            return ($headers);
        }
    }

}
