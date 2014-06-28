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
                //alert('aaaaa');
                //$('.bxslider').bxSlider(
                //      {
                //        auto: true,
                //autoControls: true
                //  });

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
        <table class="sample_01">
            <tbody>

                <?php
                foreach ($data as $list) {
                    echo html_entity_decode($list['sum']);
                }
                ?>

            </tbody>
        </table>
    </body>
</html>