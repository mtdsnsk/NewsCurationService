<h1><?= $title ?></h1>
<div>
    <ul>
        <?php
        if ($status === 'success') {
            foreach ($data as $item) {    
                echo '<hr>';
                if(!array_key_exists('desc', $item)){
                    echo 'no-contents <br>continue<br>';
                    continue;
                }
                $temp = html_entity_decode($item['desc']);
                echo 'つぶやかれた回数<span style="color: red;">[' .$item['count'].']</span><br>';
                //echo '<span style="color: gley;">リンクURL<a href="#">' . $item['url'] . '</a></span><br>';
                echo '<li>';
                echo 'タイトル:';
                echo Html::anchor($item['link'], $item['title']);
                echo '<br>';
                echo html_tag('a', array('href' => '#'),$item['link']);
                echo '</li>';
                echo '<p>記述:' . $temp . '</p>';
                echo '<p>要約:' . $item['summary'] . '</p>';
            }
        } else {
            echo $status;
        }
        ?>             
    </ul>
</div>

