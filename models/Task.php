<?php namespace models;
use vendor\ActiveRecord;

/**
 * Class Task
 * @property string $username
 * @property string $email
 * @property string $description
 *
 */

class Task extends ActiveRecord
{

    const STATUS_NEW = 0;
    const STATUS_VIWED = 1;
    const STATUS_EDITED = 5;
    const STATUS_ACCEPTED = 10;

    public static function tablename(){
        return "task";
    }

    public function rules()
    {
        return [
               'required'=>['username','email','description'],
               'string'=>['username','description'],
               'integer'=>['status','id'],
               'email'=>['email'],
           ];
    }

    public static function getStatusHtml($status){
        $result = [
            self::STATUS_NEW=>'<div class="badge badge-primary">Новые</div>',
            self::STATUS_VIWED=>'<div class="badge badge-info">Просмотрено</div>',
            self::STATUS_EDITED=>'<div class="badge badge-warning">Отредактировано</div>',
            self::STATUS_ACCEPTED=>'<div class="badge badge-success">Выполнен</div>',
        ];
        return isset($result[$status])?$result[$status]:'-';
    }

    public function labels(){
        return[
          'username'=>'имя пользователя',
          'email'=>'е-mail',
          'description'=>'текст задачи',
          'status'=>'статус',
        ];
    }
}