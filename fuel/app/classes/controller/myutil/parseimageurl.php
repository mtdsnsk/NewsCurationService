<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Parseimageurl extends Controller {

    public function action_sample() {

        $array = DB::query("SELECT count(id), image_url "
                        . "FROM sk_icon_images "
                        . "Group by image_url "
                        . "Order by count(id) desc")
                ->execute();
        
        foreach ($array as $value) {
            if($value['count(id)'] <= 6){
                continue;
            }
            $url = $value['image_url'] . ',';
            $space = '';
            DB::query("UPDATE sk_news SET image_url = REPLACE(image_url, '$url' , '$space')")
                    ->execute();
        }
    }

    public function action_fn() {

        $tables1 = 'sk_news';
        $tables2 = 'sk_icon_images';

        $array = DB::select('id', 'url', 'image_url')
                ->from($tables1)
                ->execute();

        foreach ($array as $value) {
            // 分割
            $array_url = explode(',', $value['image_url']);

            foreach ($array_url as $url) {
                echo $url . '<br>';
                if ($url == '' || $url == NULL) {
                    echo '空データ CONTINUE';
                    continue;
                }

                // 重複チェック
                $exist_data = DB::select()->from($tables2)
                        ->where('image_url', $url)
                        ->and_where('sk_news_url', $value['url'])
                        ->execute();

                if (count($exist_data) > 0) {
                    echo '登録済み なにもせーへん';
                } else {
                    DB::insert($tables2)->set(array(
                                'image_url' => $url,
                                'sk_news_id' => $value['id'],
                                'sk_news_url' => $value['url']
                            ))
                            ->execute();
                }
            }
        }
    }

}
