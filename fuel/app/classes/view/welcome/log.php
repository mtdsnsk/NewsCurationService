
<?php foreach ($myrss->channel->item as $item): ?>

    <?php
    $myurl = 'http://rss.dailynews.yahoo.co.jp/fc/rss.xml';
    $mycontents = file_get_contents($myurl);
    $myrss = simplexml_load_string($mycontents);
    ?>

    <?php
    $twitterapiurl = 'http://urls.api.twitter.com/1/urls/count.json?url=' . $item->link;
    $json1 = file_get_contents($twitterapiurl);
    // 文字化けするかもしれないのでUTF-8に変換
    $json2 = mb_convert_encoding($json1, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    // オブジェクト毎にパース
    // trueを付けると連想配列として分解して格納してくれます。
    $obj = json_decode($json2);
    echo $obj->count . '<br>';
    echo $twitterapiurl . '<br>';
    //echo '松田';
    ?>

    <ul>
        <li>
            <?= '<a href="' . $item->link . '">'; ?>
            <?= $item->title ?>
            <?= '</a>'; ?>
        </li> 
        <hr>
    </ul>

<?php endforeach; ?>