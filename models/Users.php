<?php
/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 31.07.2021
 * Time: 19:21
 */

namespace models;


use vendor\ActiveRecord;
use vendor\Session;
use vendor\View;

class Users extends ActiveRecord
{
    public static function tablename(){
        return "users";
    }

    public function rules()
    {
        return [
            'required'=>['username','email','password'],
            'string'=>['username'],
            'email'=>['email'],
            'password'=>['password'],
        ];
    }

    public function login($data){

        if($data['username']=="" || $data['password']==""){
            View::setAlert("warning","Заполните логин и пароль");
            return false;
        }
        $model = $this->findOne(['username'=>$data['username']]);

        if(!empty($model)){
            if($model['password']==md5($data['password'])){
                $session = Session::getInstance();
                $session->username =  $data['username'];
                $session->valid = true;
                $session->time = time();
                View::setAlert("success","Вход успешно выполнено");
                return true;
            } else {
                View::setAlert("danger","Логин или пароль неправильно");
                return false;
            }
        } else{
            View::setAlert("danger","Логин или пароль неправильно");
            return false;
        }
    }

    public static function logout(){
        $session = Session::getInstance();
        $session->valid=false;
    }

    public function labels(){
        return[
            'username'=>'имени пользователя',
            'email'=>'е-mail',
            'password'=>'парол',
        ];
    }
}