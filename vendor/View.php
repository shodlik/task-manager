<?php
/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 31.07.2021
 * Time: 12:10
 */

namespace vendor;


class View extends Base
{

    public function beginPage()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    public function content(){

    }

    public function endPage()
    {
        ob_end_flush();
    }

    public static function setAlert($type,$message){
        $session = Session::getInstance();
        $session->alert_type = $type;
        $session->alert_message = $message;
        $session->alert_viewed = 0;
    }

    public static function Alert(){
        $session = Session::getInstance();
        if($session->alert_viewed==0){
            $type = $session->alert_type;
            $message =$session->alert_message;
            $session->alert_viewed = 1;
            return "<div class='alert alert-{$type}'>{$message}</div>";
        }  else{
            return "";
        }

    }
}