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
class Controller_Welcome extends Controller {

    public function action_index() {
        return Response::forge(ViewModel::forge('welcome/sample2'));
    }

    public function action_hello() {
        return Response::forge(ViewModel::forge('welcome/hello'));
    }
    
    public function action_sample() {
        return Response::forge(View::forge('test/sample'));
    }
    
    public function action_sample1() {
        return Response::forge(ViewModel::forge('test/sample1'));
    }
    
    public function action_sample2() {
        //return Response::forge(view::forge('welcome/index'));
        return Response::forge(ViewModel::forge('welcome/sample2'));
    }

    public function action_rss() {
        return Response::forge(ViewModel::forge('welcome/rss'));
    }

    public function action_getrss() {
        $view = ViewModel::forge('welcome/getrss');
        return $view;
    }
    
    /**
     * The 404 action for the application.
     *
     * @access  public
     * @return  Response
     */
    public function action_404() {
        return Response::forge(ViewModel::forge('welcome/404'), 404);
    }

}
