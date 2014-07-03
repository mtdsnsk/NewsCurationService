<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>sample</title>

        <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <?php echo Asset::js('jquery.bxslider.min.js'); ?>

        <?php echo Asset::css('bootstrap.css'); ?>
        <?php echo Asset::css('newslist.css'); ?>
        <?php echo Asset::css('jquery.bxslider.css'); ?>

        <script>
            $(document).ready(function() {
                $('.bxslider').bxSlider({
                    infiniteLoop: true,
                    minSlides: 4,
                    maxSlides: 4,
                    slideWidth: 170,
                    slideMargin: 10,
                    ticker: true,
                    speed: 6000
                });
            });
        </script>
    </head>
    <body>
        <!--
        <a href='https://twitter.com/share' class='twitter-share-button' data-lang='ja'>ツイート</a>
        <script>!function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = p + '://platform.twitter.com/widgets.js';
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, 'script', 'twitter-wjs');</script>
        -->

        <table class="sample_01">
            <tbody>

                <?php
                foreach ($data as $list) {
                    echo '<tr><td>';
                    echo html_entity_decode($list['sum']);
                    echo '</td></tr>';
                }
                ?>

            </tbody>
        </table>
    </body>
</html>