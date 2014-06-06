<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View_Welcome_Test extends ViewModel {

    public function view() {
        //$this->mainmenu = 'View Welcome Test 実行';
        $this->mainmenu1 = Response::forge(ViewModel::forge('welcome/getrss'));
        $this->mainmenu2 = Response::forge(ViewModel::forge('welcome/getrss'));
    }

}
