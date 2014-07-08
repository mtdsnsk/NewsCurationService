<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model;

class Setuseragent extends \Model {

    public static function set_iphone() {
        // user_agentを偽装して、iPhoneのフリをする
        $user_agent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) '
                . 'AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 '
                . 'Mobile/9A334 Safari/7534.48.3';
        // user_agentをセット
        ini_set('user_agent', 'User-Agent: ' . $user_agent);
    }

}
