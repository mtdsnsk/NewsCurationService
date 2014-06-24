<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>sample</title>
        <?php echo Asset::css('bootstrap.css'); ?>
        <style>
            body{
                width: 100%;
            }
            table{
                border-collapse: collapse;
            }
            td {
                vertical-align: top;
                border:1px solid #ccc;
                padding:5px;
            }
            img{
                max-height: 140px;
                float: right;
            }
            .sm img{
                max-height: 120px;
            }
            .midashi{
                font-size: 24px;
            }
            a {
                color: black;
                text-decoration: none;
            }
            a :hover{
                color: black;
                text-decoration: none;
            }
            a:link{
                text-decoration: none;
            }
        </style>
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