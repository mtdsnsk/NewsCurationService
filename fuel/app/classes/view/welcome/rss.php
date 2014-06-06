<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View_Welcome_Rss extends ViewModel {

    public function view() {
        //$this->maincontents = 'test1';
        //$this->mainmenu = 'test2';
        //Response::forge(ViewModel::forge('welcome/list/advertisement'));
        //$this->maincontents = Response::forge(ViewModel::forge('welcome/getrss'));      
        $this->mainmenu = Response::forge(ViewModel::forge('welcome/getrss'));
    }

}
