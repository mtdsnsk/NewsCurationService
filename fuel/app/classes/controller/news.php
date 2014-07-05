<?php

/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
use \Model\Multithreading;

class Controller_News extends Controller {

    public function action_index() {
        return Response::forge(ViewModel::forge('news/data'));
    }

    public function action_get($param, $date, $array = array()) {
        
        Log::debug("カテゴリ:$param 日付:$date");
        
        array_push($array, Uri::base(false) . "myutil/parsexml/execute/$param");
        array_push($array, Uri::base(false) . "myutil/parsetweet/execute/$param/$date");
        array_push($array, Uri::base(false) . "myutil/parsegraph/execute/$param/$date");
        array_push($array, Uri::base(false) . "myutil/getimagefromurl/execute/$param/$date");

        Multithreading::execute($array);
    }

}
