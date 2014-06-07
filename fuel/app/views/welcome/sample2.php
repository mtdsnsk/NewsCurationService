<?php

/*
  $html = file_get_contents('http://rocketnews24.com');
  $ex = preg_replace("/[<>]/", " ", $html);
  $sp = explode(" ", $ex);
  $sp2 = array_unique($sp);
  $sp3 = array_filter($sp2, 'strlen');
  foreach ($sp3 as $data) {
  $bl = preg_match('/http.*(jpe?g|png)/i', $data, $kekka);
  if ($bl) {
  $img = file_get_contents($kekka[0]);
  $size = ceil(strlen($img)/1024);
  //echo 'size:' . $size . 'KB/';
  //echo $kekka[0] . '<br>';
  $fn = explode("/", $kekka[0]);
  file_put_contents('/Applications/XAMPP/htdocs/comicnews/xml/' . $fn[count($fn) - 1] , $img);
  }
  }
 * 
 */

echo '<h1>xml取得テスト</h1>'
 . '<div style="width: 900px; text-align:ceter; background-color: lightpink; margin: 0 auto;">'
 . '<p>';

$rsslist = DB::select()->from('sk_rsslist')
        ->where('id', 43)
        ->execute();

foreach ($rsslist as $value) {
    echo '<h1>' . Html::anchor($value['rssurl'], $value['rssurl']) . '</h1>';
    $kekka = func($value['id'], $value['rssurl'], $value['category']);
    if ($kekka === FALSE) {
        DB::update('sk_rsslist')->set(array(
            'error' => 1,
        ))->where('id', $value['id'])->execute();
        echo 'error';
    } else {
        DB::update('sk_rsslist')->set(array(
            'error' => 0,
        ))->where('id', $value['id'])->execute();
    }
}

echo '</p>'
 . '</div>';

function func($rssid, $myurl, $category) {
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
        var_dump($myrss);
        echo '<div style="background-color:lightgreen; padding: 5px;">';
        foreach ($myrss->item as $item) {
            insert_news($rssid, $item->title, $item->link, $item->guid, '', $item->description, $category, $myrss->channel->title);
        }
        foreach ($myrss->entry as $item) {
            //$entry->link->attributes()->href
            insert_news($rssid, $item->title, $item->link->attributes()->href, $item->guid, '', $item->summary, $category, $myrss->channel->title);
        }
        foreach ($myrss->channel->item as $item) {
            echo '<p style="width: 90%; background-color:#00ffff; margin: 5 auto;">';
            echo Html::anchor($item->link, $item->title) . '<br>';
            $imgurl = '';
            $kekka = '';
            if ($item->description != '') {
                $desc = $item->description;
            } else {
                $desc = $item->desc;
            }
            $bl = preg_match('/http.*(jpe?g|png)/i', $desc, $kekka);
            if ($bl) {
                echo Html::anchor($kekka[0], '画像');
                $imgurl = $kekka[0];
            }
            $source = $myrss->channel['title'];
            echo '</p>';
            insert_news($rssid, $item->title, $item->link, $item->guid, $imgurl, $desc, $category, $source);
        }
        echo '</div>';
        echo '<hr>';
    } catch (Exception $exc) {
        echo '<h1>致命的なエラー</h1>' . $exc->getTraceAsString() . '<br>';
        echo '<h1>DBエラー</h1>';
        print_r(DB::error_info());
        echo '<hr>';
        return FALSE;
    }
    return TRUE;
}

function insert_news($rssid, $title, $url, $guid, $imgurl, $desc, $category, $source) {

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
    echo DB::last_query();
}
