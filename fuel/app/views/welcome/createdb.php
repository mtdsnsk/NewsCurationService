<?php

echo '<h1>RSS パース</h1>';
func2();

function func1($param) {

    $cnt = 0;
    $dat = Setting::rsslist();
    $keys = array_keys($dat);

    foreach ($dat as $category) {
        echo $keys[$cnt];
        foreach ($category as $url) {
            echo $url . '<br>';
            DB::insert('sk_rsslist')->set(array(
                'title' => 'yahoo',
                'rssurl' => $url,
                'category' => $cnt,
                'active' => '1',
            ))->execute();
        }
        $cnt++;
    }
}

function func2() {
// 外部ＲＳＳを簡単に読み込んで出力するルーチン
    $RSSURL = "http://www3.asahi.com/rss/index.rdf";

    $buff = "";
    $fp = fopen($RSSURL, "r");
    while (!feof($fp)) {
        $buff .= fgets($fp, 4096);
    }
    fclose($fp);

// パーサ作成
    $parser = xml_parser_create();
// パーサオプションを指定
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
// パース実行、連想配列にパース結果代入
    xml_parse_into_struct($parser, $buff, $values, $idx);
// パーサ開放
    xml_parser_free($parser);

// パースして得た連想配列をまわす
    $in_item = 0;
    foreach ($values as $value) {
        $tag = $value["tag"];
        $type = $value["type"];
//$value = $value["value"];
        $tag = strtolower($tag);
        if ($tag == "item" && $type == "open") {
            $in_item = 1;
        } else if ($tag == "item" && $type == "close") {

            echo <<<EOM
$title<br>
$description<hr>
EOM;
            $in_item = 0;
        }
        if ($in_item) {
            switch ($tag) {
                case "title":
                    // UTF-8なドキュメントの場合ここで
                    // $value = mb_convert_encoding($value, "EUC-JP", "UTF-8"); などする必要あり
                    $title = $value;
                    break;
                case "link":
                    $link = $value;
                    break;
                case "description":
                    // UTF-8なドキュメントの場合ここで
                    // $value = mb_convert_encoding($value, "EUC-JP", "UTF-8"); などする必要あり
                    $description = $value;
                    break;
            }
        }
    }
}
