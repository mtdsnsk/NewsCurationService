<!DOCTYPE html>
<!--
編集画面
-->
<html>
    <head>
        <meta charset="utf-8">
        <style>
            img{
                float:right;
                max-width: 250px;
                max-height: 250px;
            }
            div.imagebox {
                border: 1px dashed #0000cc; /* 枠線 */
                background-color: #eeeeff;  /* 背景色 */
                max-width: 250px; /* 横幅 */
                max-height: 250px;
                float: left; /* 左に配置 */
                margin: 5px; /* 周囲の余白 */
            }
            p.image {
                text-align: center; /* 3.中央寄せ */
                margin: 5px;        /* 4.余白・間隔 */
            }
        </style>
    </head>
    <body>
        <h1><?= $title ?></h1>
        <div>
            <ul>
                <?php
                foreach ($data as $item) {
                    echo '<hr>';
                    if (!array_key_exists('desc', $item)) {
                        echo 'no-contents <br>continue<br>';
                        continue;
                    }
                    $temp = html_entity_decode($item['desc']);
                    echo 'つぶやかれた回数<span style="color: red;">[' . $item['count'] . ']</span><br>';
                    //echo '<span style="color: gley;">リンクURL<a href="#">' . $item['url'] . '</a></span><br>';
                    echo '<li>';
                    echo 'タイトル:';
                    echo Html::anchor($item['link'], $item['title']);
                    echo '<br>';
                    echo html_tag('a', array('href' => '#'), $item['link']);
                    echo '</li>';
                    echo '<p>記述:' . $temp . '</p>';
                    echo '<p>要約:' . $item['summary'] . '</p>';

                    /*
                      if(is_array($item['images'])) {
                      foreach ($item['images'] as $img) {
                      echo '<div class="imagebox">';
                      echo '<p class="image">';
                      echo html_tag('img', array(
                      'src' => $img['url'],
                      ));
                      echo '</p>';
                      echo '</div>';
                      }
                      }
                     */
                    echo '<p style="clear: both;">後続の文章</p>';
                }
                ?>             
            </ul>
        </div>
    </body>
</html>
