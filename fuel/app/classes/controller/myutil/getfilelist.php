<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use \Model\Getfilename;

Class Controller_Myutil_Getfilelist extends Controller_Rest {
    
    public function action_get() {
        $param = Input::param('title');
        $list = Getfilename::getlist($param);
        return $list;
    }

    public function action_apppath() {

        echo APPPATH;
    }

}
