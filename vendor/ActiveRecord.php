<?php
/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 31.07.2021
 * Time: 15:50
 */

namespace vendor;


use function Couchbase\defaultDecoder;
use models\Task;

class ActiveRecord extends Db
{
    public $error_message;
    public $id;

    const EROR_REQURIED = -10;
    const EROR_EMAIL = -20;
    const EROR_SAVED = -30;

    public function __construct()
    {
        $columns =  $this->getColumns();
        foreach ($columns as $items){
            $this->$items="";
        }
    }

    public function rules(){
        return [];
    }

    public function labels(){
        return [];
    }

    public function load($data){
        if(empty($data))
            return false;
        $columns =  $this->getColumns();
        foreach ($columns as $name){
            if(isset($data[$name]))
                $this->$name = $data[$name];
        }
        return true;
    }

    public function save($validation=true){
        if($validation==true){
            if($this->validation()){
                return false;
            }
        }
        if($this->isNewRecord()){
           return $this->insert();
        } else{
            return $this->updates();
        }
    }

    public static function update($params,$value){
        $conection = Db::getConnection();
        $table = get_called_class();
        $sql = "UPDATE {$table::tablename()} SET ".implode(",",$value)."
                WHERE ".implode("and",$params).";";
        if ($conection->query($sql) === TRUE) {
            $conection->close();
            return true;
        } else {
            $conection->close();
            View::setAlert('danger',$conection->error);
            return false;
        }
    }

    public function insert(){
        $conection = Db::getConnection();
        $columns = $this->getColumns();
        $keys=[];
        $values=[];
        foreach ($columns as $col){
            $value = trim($this->$col);
            $value = self::xss_clean($value);
            if($col!="id") {
                $keys[] = "`{$col}`";
                $values[] = "'{$value}'";
            }
        }
        $table = get_class($this);
        $sql = "INSERT INTO {$table::tablename()} (".implode(",",$keys).")
                VALUES (".implode(",",$values).");";
        if ($conection->query($sql) === TRUE) {
            $conection->close();
            return true;
        } else {
            $conection->close();
            $this->setErors($conection->error,self::EROR_SAVED);
            return false;
        }
    }

    public function updates(){
        $conection = Db::getConnection();
        $columns = $this->getColumns();
        $values=[];
        $query = new Task();
        $oldData = $query->findOne(['id'=>$this->id]);
        foreach ($columns as $col){

            if($col=="description"){
                if($this->description!=$oldData['description']){
                    $this->status = Task::STATUS_EDITED;
                }
            }
            $value = trim($this->$col);
            $value = self::xss_clean($value);
            $values[]=$col."='".$value."'";
        }
        $table = get_class($this);
        $id = $this->id;
        $sql = "UPDATE {$table::tablename()} SET ".implode(",",$values)."
                WHERE id = {$id};";
        if ($conection->query($sql) === TRUE) {
            $conection->close();
            return true;
        } else {
            $conection->close();
            $this->setErors($conection->error,self::EROR_SAVED);
            return false;
        }
    }

    public function isNewRecord(){
        return ($this->id==null)?true:false;
    }

    public function validation(){
        $rules = $this->rules();
        if(isset($rules['required'])){
            foreach ($rules['required'] as $col){
                if($this->$col==""){
                    $this->setErors($col,self::EROR_REQURIED);
                }
            }
        }
        if(isset($rules['email'])){
            foreach ($rules['email'] as $col){
                if(!filter_var($this->$col, FILTER_VALIDATE_EMAIL))
                    $this->setErors($col,self::EROR_EMAIL);
            }
        }
        if($this->error_message=="")
            return false;
        else
            return true;
    }

    public function setErors($col,$typeEror){
        $text = [
          self::EROR_REQURIED=>"Обязательно заполнить столбец \"{$col}\"",
          self::EROR_EMAIL=>"Неправильный формат электронной почты \"{$col}\"",
          self::EROR_SAVED=>"Ошибка сохранения данных: {$col}",
        ];
        $this->error_message[]= $text[$typeEror];
    }

    public function getHtmlErors(){
        $messages= "";
        if($this->error_message!=null)
            foreach ($this->error_message as $key=>$value){
                $messages .= "<div> - {$value}</div>";
            }
        if($messages=="")
            return "";
        else
            return '<div class="alert alert-warning">'. $messages."</div>";
    }

    public function findOne($params){
        $connections = Db::getConnection();
        $class = get_called_class();
        $where ="";
        $whereKey=[];
        foreach ($params as $key=>$value){
            $whereKey []= $key."='".$value."'";
        }
        if(!empty($whereKey)){
            $where ="where ". implode(" and ",$whereKey);
        }

        $sql = "select * from {$class::tablename()} {$where} LIMIT 1";
        $result = $connections->query($sql);
        if ($result->num_rows > 0) {
            $data = [];
            while($row = $result->fetch_assoc()) {
                foreach ($row as $key=>$value){
                    $this->$key = $value;
                    $items[$key]=$value;
                }
                $data[]=$items;
            }
            return $data[0];
        } else {
            return [];
        }
    }

    public function findSearch($params=[]){
        $limit = isset($params['limit'])?$params['limit']:3;
        $page = isset($params['page'])?$params['page']:1;
        $page_first_result = ($page-1) * $limit;
        $connections = Db::getConnection();
        $class = get_called_class();
        $sql = "select * from {$class::tablename()}";
        $number_of_result  = $connections->query($sql)->num_rows;
        $number_of_page = ceil ($number_of_result / $limit);
        $sortOrder="";
        if(isset($params['s']) && isset($params['d']) && isset($params['d'])!=""){
            $columns = $this->getColumns();
            if(in_array($params['s'],$columns) && in_array($params['d'],['ASC','DESC','']) ){
                $sortOrder = "ORDER BY {$params['s']} {$params['d']}";
            }
        }

        $sql = "select * from {$class::tablename()} {$sortOrder} LIMIT ".$page_first_result.",".$limit;
        $result = $connections->query($sql);
        if ($result->num_rows > 0) {
            $data = [];
            while($row = $result->fetch_assoc()) {
                foreach ($row as $key=>$value){
                    $items[$key]=$value;
                }
                $data[]=$items;
            }
            return [
                'page'=>$number_of_page,
                'data'=>$data,
                'count'=>$number_of_result
            ];
        } else {
            return ['page'=>null,'data'=>[],'count'=>0];
        }
    }

    public function pagination($page){
        $result = "";
        $k=0;
        $params = $_SERVER['REQUEST_URI'];
        $url = parse_url($params);
        if(!isset($url['query']))
            $url['query']="";
        parse_str($url['query'],$query);
        if($url['path']=="/")
            $url['path'] = self::default_controller.self::default_action;
        $current_page = isset($query['page'])?$query['page']:1;
        if($page==1)
            return"";
        for ($i=0; $i<$page; $i++){$k++;
            $active_class= "btn-light";

            $query['page'] = $k;
            $url_result = $url['path']."?".http_build_query($query);
            if($current_page==$k)
                $active_class = "btn-primary";
            $result.='<a href="'.$url_result.'" class="btn '.$active_class. '">'. $k.'</a> ';
        }
        return $result;
    }

    public function sortOrder($col){
        $labels = $this->labels();
        $labels = isset($labels[$col])?$labels[$col]:$col;
        $params = $_SERVER['REQUEST_URI'];
        $url = parse_url($params);
        if(!isset($url['query']))
            $url['query']="";
        parse_str($url['query'],$query);
        $query['s'] = $col;
        if(isset($query['d'])){
            switch ($query['d']){
                case "ASC":
                    $query['d']="DESC";
                    break;
                case "DESC":
                    $query['d']="ASC";
                    break;
                default:
                    $query['d']="ASC";
                    break;
            }
        } else {
            $query['d']="ASC";
        }
        if($url['path']=="/")
            $url['path'] = self::default_controller.self::default_action;
        $url = $url['path']."?".http_build_query($query);
        return "<a href='{$url}'>{$labels}</a>";
    }

    public function getColumns(){
        $columns = $this->rules();
        $col = [];
        foreach ($columns as $key=>$value){
            if($key!="required"){
                foreach ($value as $items){
                    $col[]=$items;
                }
            }
        }
        return $col;
    }

    public static function xss_clean($data)
    {
// Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

// Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

// Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

// Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);

// we are done...
        return $data;
    }
}