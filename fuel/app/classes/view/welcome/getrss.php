<?php

class View_Welcome_Getrss extends ViewModel {

    public function view() {
        $tmpdat = array();
        $dat = Setting::rsslist2();
        foreach ($dat as $value) {
            foreach ($value as $child) {
                $this->show_content($child, $tmpdat);
            }
        }
        $this->title = 'ニュースtwitterつぶやかれランキング2';
        $this->data = $tmpdat;
        $this->status = 'success';
        return;
    }

    private static function show_content($myurl, &$tmpdat) {
        try {
            // RSSフィードからXML取得する
            $mycontents = View_Welcome_Getrss::getrssdata($myurl);
            if ($mycontents == NULL) {
                return;
            }
            // XML文字列に変換
            $myrss = simplexml_load_string($mycontents);
            // 配列を追加
            View_Welcome_Getrss::get_keys($myrss, $tmpdat);
        } catch (Exception $exc) {
            echo '<br>showcontent<br>エラー<br>URL=' . $myurl . '<br>' . $exc->getMessage() . '<br>';
            return;
        }
        return;
    }

    private static function getrssdata($myurl) {
        try {
            $context = stream_context_create(array(
                'http' => array('ignore_errors' => true)
            ));
            // RSSの内容を取得
            $mycontents = file_get_contents($myurl, false, $context);
            //$sp = explode('/', $myurl);
            //file_put_contents('/Applications/XAMPP/htdocs/comicnews/xml/' . $sp[2] . date("His") . '.xml', $mycontents);
            if ($mycontents === 'false') {
                return NULL;
            }
            // レスポンス取得
            $pos = strpos($http_response_header[0], '200');
            if ($pos === false) {
                return NULL;
            }
            return $mycontents;
        } catch (Exception $exc) {
            echo '<br>getrssdata<br>エラー<br>URL=' . $myurl . '<br>' . $exc->getMessage() . '<br>';
            return null;
        }
    }

    private static function get_keys($data, &$tmpdat) {
        $var = array();
        if (array_key_exists('channel', $data)) {
            foreach ($data->channel->item as $item) {
                array_key_exists('count', $item) ? $var['count'] = $item->count : $var['count'] = '';
                array_key_exists('url', $item) ? $var['url'] = $item->url : $var['url'] = '';
                array_key_exists('link', $item) ? $var['link'] = $item->link : $var['link'] = '';
                array_key_exists('title', $item) ? $var['title'] = $item->title : $var['title'] = '';
                array_key_exists('desc', $item) ? $var['desc'] = $item->desc : $var['desc'] = '';
                array_key_exists('description', $item) ? $var['description'] = $item->description : $var['description'] = '';
                array_key_exists('guid', $item) ? $var['guid'] = $item->guid : $var['guid'] = '';
                array_key_exists('summary', $item) ? $var['summary'] = $item->summary : $var['summary'] = '';
                //View_Welcome_Getrss::gettwitterapi($var);
                View_Welcome_Getrss::get_image($var['link']);
                array_push($tmpdat, $var);
            }
            return;
        }
        if (array_key_exists('entry', $data)) {
            foreach ($data->entry as $item) {
                array_key_exists('count', $item) ? $var['count'] = $item->count : $var['count'] = '';
                array_key_exists('url', $item) ? $var['url'] = $item->url : $var['url'] = '';
                array_key_exists('link', $item) ? $var['link'] = $item->link : $var['link'] = '';
                array_key_exists('title', $item) ? $var['title'] = $item->title : $var['title'] = '';
                array_key_exists('desc', $item) ? $var['desc'] = $item->desc : $var['desc'] = '';
                array_key_exists('description', $item) ? $var['description'] = $item->description : $var['description'] = '';
                array_key_exists('guid', $item) ? $var['guid'] = $item->guid : $var['guid'] = '';
                array_key_exists('summary', $item) ? $var['summary'] = $item->summary : $var['summary'] = '';
                //View_Welcome_Getrss::gettwitterapi($var);
                View_Welcome_Getrss::get_image($var['link']);
                array_push($tmpdat, $var);
            }
            return;
        }
        echo 'no data<br>';
    }

    private static function gettwitterapi(&$item) {
        try {
            if ($item['link'] == '') {
                echo 'link=null<br>';
                $item['link'] = $item['link']->attributes()->href;
            }
            //パラメータ削除
            $sp = explode('?', $item['link']);
            // APIのURL
            $twitterurl = 'http://urls.api.twitter.com/1/urls/count.json?url=';
            $twitterapiurl = $twitterurl . $sp[0];
            $json1 = file_get_contents($twitterapiurl);
            // 文字化けするかもしれないのでUTF-8に変換
            $json2 = mb_convert_encoding($json1, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            // デコード
            $obj = json_decode($json2);
            // 配列に値を格納する
            $item['url'] = $twitterapiurl;
            array_key_exists('count', $obj) ? $item['count'] = $obj->count : $item['count'] = 'none';
            if ($item['count'] == 'none' || $item['count'] == 0) {
                echo $sp[0] . '<br>';
            }
        } catch (Exception $exc) {
            $item['count'] = 'none';
            echo '<br>gettwwitterapi<br>エラー<br>item=' . $item . '<br>' . $exc->getMessage() . '<br>';
        }
        return;
    }

    public static function get_image($url) {
        
        if($url == ''){
            return;
        }
        
        $html = file_get_contents($url);
        $ex = preg_replace("/[<>]/", " ", $html);
        $sp1 = explode(" ", $ex);
        $sp2 = array_unique($sp1);
        $sp3 = array_filter($sp2, 'strlen');
        foreach ($sp3 as $data) {
            $kekka = '';
            $bl = preg_match('/http.*(jpe?g|png)/i', $data, $kekka);
            if ($bl) {
                $img = file_get_contents($kekka[0]);
                //$size = ceil(strlen($img) / 1024);
                //echo 'size:' . $size . 'KB/';
                //echo $kekka[0] . '<br>';
                $fn = explode("/", $kekka[0]);
                file_put_contents('/Applications/XAMPP/htdocs/comicnews/xml/' . $fn[count($fn) - 1], $img);
            }
        }
    }

}
