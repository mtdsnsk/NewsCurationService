<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Test_Fileget extends Controller {

    public function action_index() {
        //取得先URL;
        $filename = 'http://hamusoku.com/archives/8444905.html';
        
        // user_agentを偽装して、iPhoneのフリをする
        $user_agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) '
                . 'AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 '
                . 'Mobile/9A334 Safari/7534.48.3';
        ini_set('user_agent', 'User-Agent: ' . $user_agent); 
        
        // ファイルの中身を取得する
        $file = file_get_contents($filename);
        
        echo $file;
    }
    
    public function action_sample() {
        //取得先URL;
        $filename = 'http://hamusoku.com/archives/8444905.html';     
        
        // ファイルの中身を取得する
        $file = file_get_contents($filename);
        
        echo $file;
    }
    
}