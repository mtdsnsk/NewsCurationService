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

    /*
     * ニュース表示
     */
    public function action_index() {
        return Response::forge(ViewModel::forge('news/data'));
    }

    /*
     * カテゴリと日付を指定してRSS解析
     */
    public function action_get($no, $date, $array = array()) {
        
        Log::debug("カテゴリ:$no 日付:$date");
        // RSS解析の起動URL
        array_push($array, Uri::base(false) . "myutil/parsexml/execute/$no");
        // つぶやき数取得の起動URL
        array_push($array, Uri::base(false) . "myutil/parsetweet/execute/$no/$date");
        // GRAPH取得の起動URL
        array_push($array, Uri::base(false) . "myutil/parsegraph/execute/$no/$date");

        // マルチスレッドで起動
        Multithreading::execute($array);
    }
    
    /*
     * カテゴリと日付を指定して、URLから画像を取得する
     */
    public function action_getimages($param, $date, $array = array()) {
        
        Log::debug("画像取得 カテゴリ:$param 日付:$date");
        
        array_push($array, Uri::base(false) . "myutil/getimagefromurl/execute/$param/$date");
        Multithreading::execute($array);
    }

}
