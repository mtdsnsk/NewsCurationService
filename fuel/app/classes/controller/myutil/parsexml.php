<?php

Class Controller_Myutil_Parsexml extends Controller {

    public static function action_fn() {
        echo '<h1>xml取得</h1>'
        . '<div style="width: 900px; text-align:ceter; background-color: lightpink; margin: 0 auto;">'
        . '<p>';
        $rsslist = DB::select()->from('sk_rsslist')
                ->execute();
        Log::info('xml解析開始');
        Log::info('対象RSS数:' . count($rsslist));
        foreach ($rsslist as $value) {

            $kekka = Controller_Myutil_Parsexml::func($value['id'], $value['rssurl'], $value['category']);
            if ($kekka === FALSE) {
                Log::info('取得失敗 RSS NO:' . $value['id'] . '/URL:' . $value['rssurl']);
                DB::update('sk_rsslist')->set(array(
                    'error' => 1,
                ))->where('id', $value['id'])->execute();
                echo '<h1>' . Html::anchor($value['rssurl'], $value['rssurl']) . '</h1>';
                echo 'エラー発生!';
            } else {
                Log::info('取得成功 RSS NO:' . $value['id'] . '/URL:' . $value['rssurl']);
                DB::update('sk_rsslist')->set(array(
                    'error' => 0,
                ))->where('id', $value['id'])->execute();
            }
        }
        Log::info('xml解析終了');
        echo '</p></div>';
    }

    private static function func($rssid, $myurl, $category) {
        try {
            $context = stream_context_create(array(
                'http' => array('ignore_errors' => true)
            ));
            $mycontents = file_get_contents($myurl, false, $context); // RSSの内容を取得
            if ($mycontents === 'false') {
                echo '<h3>エラー</h3>contents false[' . $myurl . ']<br>';
                echo '<hr>';
                return FALSE;
            }
            $pos = strpos($http_response_header[0], '200'); // レスポンス取得
            if ($pos === false) {
                echo '<h3>エラー</h3>response false[' . $myurl . ']<br>';
                echo '<hr>';
                return FALSE;
            }
            if ($mycontents == NULL) {
                echo '<h3>エラー</h3>no-contents! [' . $myurl . ']<br>';
                echo '<hr>';
                return FALSE;
            }
// XML文字列に変換
            $myrss = simplexml_load_string($mycontents);
            foreach ($myrss->item as $item) {
                $imgurl = '';
                $kekka = '';
                if ($item->description != '') {
                    $desc = $item->description;
                } else {
                    $desc = $item->summary;
                }
                $bl = preg_match('/http.*(jpe?g|png)/i', $desc, $kekka);
                if ($bl) {
//echo Html::anchor($kekka[0], '画像');
                    $imgurl = $kekka[0];
                }
                $source = $myrss->channel->title;
                Controller_Myutil_Parsexml::insert_news($rssid, $item->title, $item->link, $item->guid, $imgurl, $desc, $category, $source);
            }
            foreach ($myrss->entry as $item) {
                $imgurl = '';
                $kekka = '';
                if ($item->summary != '') {
                    $desc = $item->summary;
                } else {
                    $desc = $item->description;
                }
                $bl = preg_match('/http.*(jpe?g|png)/i', $desc, $kekka);
                if ($bl) {
//echo Html::anchor($kekka[0], '画像');
                    $imgurl = $kekka[0];
                }
                $source = $myrss->channel->title;
                Controller_Myutil_Parsexml::insert_news($rssid, $item->title, $item->link->attributes()->href, $item->guid, $imgurl, $desc, $category, $source);
            }
            foreach ($myrss->channel->item as $item) {
                $imgurl = '';
                $kekka = '';
                if ($item->description != '') {
                    $desc = $item->description;
                } else {
                    $desc = $item->desc;
                }
                $bl = preg_match('/http.*(jpe?g|png)/i', $desc, $kekka);
                if ($bl) {
//echo Html::anchor($kekka[0], '画像');
                    $imgurl = $kekka[0];
                }
                $source = $myrss->channel['title'];
                Controller_Myutil_Parsexml::insert_news($rssid, $item->title, $item->link, $item->guid, $imgurl, $desc, $category, $source);
            }
            echo '<hr>';
        } catch (Exception $exc) {
            echo '<h1>致命的なエラー</h1>' . $exc->getTraceAsString() . '<br>';
            echo '<h1>DBエラー</h1>';
            print_r(DB::error_info());
            return FALSE;
        }
        return TRUE;
    }

    private static function insert_news($rssid, $title, $url, $guid, $imgurl, $desc, $category, $source) {

        $query = DB::select('id')->from('sk_news')
                ->where_open()
                ->where('title', $title)
                ->and_where('url', $url)
                ->where_close()
                ->execute();
        if (DB::count_last_query() > 0) {
            DB::update('sk_news')->set(array(
                'title' => $title,
                'url' => $url,
                'guid' => $guid,
                'image_url' => $imgurl,
                'description' => $desc,
                'category' => $category,
                'source' => $source,
                'updated_at' => date("Y-m-d H:i:s"),
                'rsslist_id' => $rssid,
            ))->where('id', $query[0]['id'])->execute();
        } else {
            DB::insert('sk_news')->set(array(
                'title' => $title,
                'url' => $url,
                'guid' => $guid,
                'image_url' => $imgurl,
                'description' => $desc,
                'category' => $category,
                'source' => $source,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'rsslist_id' => $rssid,
            ))->execute();
        }
//echo DB::last_query();
    }

}
