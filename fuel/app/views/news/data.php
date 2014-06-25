<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>sample</title>
        <?php echo Asset::css('bootstrap.css'); ?>
        <?php echo Asset::css('newslist.css'); ?>

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