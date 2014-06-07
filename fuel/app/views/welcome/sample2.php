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

echo '<h1>xml取得テスト</h1><div style="width: 900px; text-align:ceter; background-color: lightpink; margin: 0 auto;"><p>';

// RSSフィードからXML取得する
$myurl = 'http://feed.rssad.jp/rss/gigazine/rss_2.0';
//$myurl = 'http://rss.dailynews.yahoo.co.jp/fc/rss.xml';
//$myurl = 'http://gigazine.net/index.php?/news/rss_2.0';

$array = array(
    'http://rss.dailynews.yahoo.co.jp/fc/entertainment/rss.xml',
    'http://rss.dailynews.yahoo.co.jp/fc/sports/rss.xml',
    //'http://gigazine.net/index.php?/news/rss_2.0/',
    'http://feed.rssad.jp/rss/gigazine/rss_2.0',
    'http://getnews.jp/feed/ext/orig',
    //'http://rocketnews24.com/feed/',
    'http://feeds.rocketnews24.com/rocketnews24',
    'http://feeds.gizmodo.jp/rss/gizmodo/index.xml',
    'http://www.narinari.com/index.xml',
    'http://rss.dailynews.yahoo.co.jp/fc/computer/rss.xml',
    //'http://jp.techcrunch.com/feed/',
    'http://feed.rssad.jp/rss/techcrunch/feed',
    //'http://www.itmedia.co.jp/info/rss/',
    'http://rss.rssad.jp/rss/itmtop/2.0/itmedia_all.xml',
    'http://natalie.mu/comic/feed/news',
    'http://kai-you.net/contents/feed.rss',
    'http://akiba-souken.com/feed/anime/',
    'http://animeanime.jp/rss/index.rdf',
    'http://natalie.mu/music/feed/news',
    //'http://www.cinematoday.jp/index.xml',
    'http://feeds.cinematoday.jp/cinematoday_update',
    //'http://www.oricon.co.jp/rss/news/total/',
    'http://rss.rssad.jp/rss/oricon/news/total',
    //'http://www.zakzak.co.jp/rss/rss.htm',
    'http://rss.rssad.jp/rss/zakzak/all/zakzak-all.xml',
    'http://headlines.yahoo.co.jp/rss/nkgendai-c_ent.xml',
    'http://news.livedoor.com/rss/article/vender/cyzo',
    //'http://www.tokyo-sports.co.jp/?feed=rss2',
    'http://www.tokyo-sports.co.jp/feed/',
    'http://zasshi.news.yahoo.co.jp/rss/friday-all.xml',
    //'http://joshi-spa.jp/feed',
    'http://feed.rssad.jp/rss/joshispa/feed',
    'http://wol.nikkeibp.co.jp/rss/all_wol.rdf',
    //'http://rd.yahoo.co.jp/media/news/zasshi/rss/list/*http://zasshi.news.yahoo.co.jp/rss/health-all.xml',
    'http://zasshi.news.yahoo.co.jp/rss/health-all.xml',
    'http://wpb.shueisha.co.jp/feed/',
    'http://r25.yahoo.co.jp/rss/',
    //'http://nikkan-spa.jp/feed',
    'http://feed.rssad.jp/rss/spa/feed',
    'http://zasshi.news.yahoo.co.jp/rss/takaraj-all.xml',
    'http://zasshi.news.yahoo.co.jp/rss/gqjapan-all.xml',
    //'http://www.zakzak.co.jp/rss/rss.htm',
    'http://headlines.yahoo.co.jp/rss/nkgendai-c_ent.xml',
    'http://www.news-postseven.com/feed',
    'http://shukan.bunshun.jp/list/feed/rss',
    //'http://entabe.jp/news/rss',
    'http://entabe.jp/news/feed.rss',
    //'http://straightpress.jp/feed/',
    'http://straightpress.jp/feed',
    'http://toyokeizai.net/list/feed/rss',
    //'http://www.sankeibiz.jp/rss/news/points.xml',
    'http://rss.rssad.jp/rss/sankeibiz/points',
    //'http://biz-journal.jp/index.xml',
    'http://rss.rssad.jp/rss/bizjournal/index.xml',
);

foreach ($array as $value) {
    echo '<h1>' . Html::anchor($value, $value) . '</h1>';
    functionName($value);
}

echo '</p></div>';

function functionName($myurl) {
    try {
        $context = stream_context_create(array(
            'http' => array('ignore_errors' => true)
        ));

        $mycontents = file_get_contents($myurl, false, $context); // RSSの内容を取得
        if ($mycontents === 'false') {
            echo '<h3>エラー</h3>contents false[' . $myurl . ']<br>';
            echo '<hr>';
            return;
        }
        $pos = strpos($http_response_header[0], '200'); // レスポンス取得
        if ($pos === false) {
            echo '<h3>エラー</h3>response false[' . $myurl . ']<br>';
            echo '<hr>';
            return;
        }
        if ($mycontents == NULL) {
            echo '<h3>エラー</h3>no-contents! [' . $myurl . ']<br>';
            echo '<hr>';
            return;
        }
        // XML文字列に変換
        $myrss = simplexml_load_string($mycontents);
        echo '<div style="background-color:lightgreen; padding: 5px;">';
        foreach ($myrss->channel->item as $item) {
            echo '<p style="width: 90%; background-color:#00ffff; margin: 5 auto;">';
            echo Html::anchor($item->link, $item->title) . '<br>';
            //echo $item->link . "<br>";
            echo $item->guid . "<br>";
            //echo $item->description . "<br>";
            $kekka = '';
            $bl = preg_match('/http.*(jpe?g|png)/i', $item->description, $kekka);
            if ($bl) {
                //echo 'img url:' . $kekka[0];
                echo Html::anchor($kekka[0], '画像');
            }
            echo $myrss->channel['title'];
            echo '</p>';
        }
        echo '</div>';
        echo '<hr>';
    } catch (Exception $exc) {
        echo '<h1>致命的なエラー</h1>' . Html::anchor($myurl, $myurl) . '<br>' . $exc->getTraceAsString();
        echo '<hr>';
        return;
    }
    return;
}
