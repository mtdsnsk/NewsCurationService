<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Class Controller_Myutil_Getfilelist extends Controller_Rest {
    
    public function action_get() {
        $param = Input::param('title');
        
        require_once ( APPPATH . 'classes/model/getfilelist.php');
        $list = Getfilelist::functionName($param);
        
        return $list;
    }
}