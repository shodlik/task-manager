<?php namespace vendor;

class Base
{

    public $title;
    public $config;

    const default_action = '/index';
    const default_controller = '/site';

    public function post($key=null){
        return isset($_POST[$key])?$_POST[$key]:$_POST;
    }

    public function get($key=null){
        return isset($_GET[$key])?$_GET[$key]:$_GET;
    }

    public function cureentDomain(){
        return "http://".$_SERVER['SERVER_NAME'];
    }

    public static function isGuest(){
        $session = Session::getInstance();
        return !$session->valid;
    }

    public static function getUsername(){
        $session = Session::getInstance();
        return $session->username;
    }
}