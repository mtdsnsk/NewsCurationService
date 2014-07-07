<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Fuel\Tasks;
 
class Hello
{
    public function run($name = 'World')
    {
        echo 'Hello '.$name.'!';
    }
}