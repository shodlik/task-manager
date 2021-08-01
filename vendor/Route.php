<?php
/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 31.07.2021
 * Time: 12:14
 */

namespace vendor;


class Route extends Controller
{
    protected $request = array();

    public function __construct($url,$config)
    {
        $this->config = $config;
        $content = $this->SetRoute($url!='/' ? $url : self::default_controller);
        echo $content;
    }

    public function __get($name)
    {
        if (method_exists($this, 'Get' . $name))
            return $this->{'Get' . $name}();
        else
            return null;
    }

    public function SetRoute($route)
    {
        $route = rtrim($route, '/');
        $this->request = explode('/', $route);
        $action = isset($this->request[2])?$this->request[2]:'index';
        $action = explode("?",$action);
        $action = "action".str_replace('?',"",$action[0]);
        $controller = 'controller\\'.ucfirst($this->request[1])."Controller";
        if(class_exists($controller)){
            $class = new $controller();
            if(method_exists($class,$action)){
                return $class->$action();
            } else {
                die("Bunday action mavjud emas");
            }
        } else {
            die("Bunday sahifa mavjud emas");
        }
    }
}