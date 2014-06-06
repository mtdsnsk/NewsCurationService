<?php

class Setting {

    public static function rsslist() {
        $top = array(
            'http://rss.dailynews.yahoo.co.jp/fc/rss.xml',
        );
        $ent = array(
            'http://rss.dailynews.yahoo.co.jp/fc/entertainment/rss.xml',
        );
        $spo = array(
            'http://rss.dailynews.yahoo.co.jp/fc/sports/rss.xml',
        );
        $neta = array(
            'http://gigazine.net/index.php?/news/rss_2.0/',
            'http://getnews.jp/feed/ext/orig',
            'http://rocketnews24.com/feed/',
            'http://feeds.gizmodo.jp/rss/gizmodo/index.xml',
            'http://www.narinari.com/index.xml',
        );
        $net = array(
            'http://rss.dailynews.yahoo.co.jp/fc/computer/rss.xml',
            'http://jp.techcrunch.com/feed/',
            'http://rss.rssad.jp/rss/itmnews/2.0/news_special.xml'
                //'http://www.itmedia.co.jp/info/rss/',
        );
        $com = array(
            'http://natalie.mu/comic/feed/news',
            'http://kai-you.net/contents/feed.rss',
        );
        $ani = array(
            'http://akiba-souken.com/feed/anime/',
            'http://animeanime.jp/rss/index.rdf',
        );
        $cul = array(
            'http://natalie.mu/music/feed/news',
            'http://www.cinematoday.jp/index.xml',
            'http://www.oricon.co.jp/rss/news/total/',
        );
        $gos = array(
            //'http://www.zakzak.co.jp/rss/rss.htm',
            'http://rss.rssad.jp/rss/zakzak/entertainment/entertainment.xml',
            'http://headlines.yahoo.co.jp/rss/nkgendai-c_ent.xml',
            'http://news.livedoor.com/rss/article/vender/cyzo',
            'http://www.tokyo-sports.co.jp/?feed=rss2',
            'http://zasshi.news.yahoo.co.jp/rss/friday-all.xml',
        );
        $jos = array(
            'http://joshi-spa.jp/feed',
            'http://wol.nikkeibp.co.jp/rss/all_wol.rdf',
            'http://rd.yahoo.co.jp/media/news/zasshi/rss/list/*http://zasshi.news.yahoo.co.jp/rss/health-all.xml',
        );
        $r30 = array(
            'http://wpb.shueisha.co.jp/feed/',
            'http://r25.yahoo.co.jp/rss/',
        );
        $r40 = array(
            'http://nikkan-spa.jp/feed',
            'http://zasshi.news.yahoo.co.jp/rss/takaraj-all.xml',
            'http://zasshi.news.yahoo.co.jp/rss/gqjapan-all.xml',
        );
        $r50 = array(
            //'http://www.zakzak.co.jp/rss/rss.htm',
            'http://rss.rssad.jp/rss/zakzak/all/zakzak-all.xml',
            'http://headlines.yahoo.co.jp/rss/nkgendai-c_ent.xml',
            'http://www.news-postseven.com/feed',
            'http://shukan.bunshun.jp/list/feed/rss',
        );
        $lif = array(
            'http://entabe.jp/news/feed.rss',
            //'http://entabe.jp/news/rss',
            'http://straightpress.jp/feed/',
        );
        $biz = array(
            'http://toyokeizai.net/list/feed/rss',
            'http://www.sankeibiz.jp/rss/news/points.xml',
            'http://biz-journal.jp/index.xml',
        );
        return array(
            'トップ' => $top,
            'エンタメ' => $ent,
            'スポーツ' => $spo,
            'ネタ' => $neta,
            'ネット・IT' => $net,
            'コミック' => $com,
            'アニメ' => $ani,
            'カルチャー' => $cul,
            'ゴシップ' => $gos,
            '女子' => $jos,
            'R30' => $r30,
            'R40' => $r40,
            'R50' => $r50,
            'ライフ' => $lif,
            'ビジネス' => $biz,
        );
    }

    public static function rsslist2() {
        $top = array(
            'http://rss.dailynews.yahoo.co.jp/fc/rss.xml',
            'http://natalie.mu/music/feed/news',
        );
        $neta = array(
            'http://gigazine.net/index.php?/news/rss_2.0/',
            'http://getnews.jp/feed/ext/orig',
            'http://rocketnews24.com/feed/',
            'http://feeds.gizmodo.jp/rss/gizmodo/index.xml',
            'http://www.narinari.com/index.xml',
        );
        return array(
            'トップ' => $top,
            'ネタ' => $neta,
        );
    }

}
