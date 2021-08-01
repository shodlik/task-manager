<?php
/**
 * Created by PhpStorm.
 * User: Jasmina
 * Date: 31.07.2021
 * Time: 13:51
 */

namespace vendor;


class Db extends Base
{
    public static function getConnection($config=null){
        if($config==null)
            $config = require __DIR__ . '/../config/main.php';
        $connection = new \mysqli(
                $config['db']['host'],
                $config['db']['username'],
                $config['db']['password'],
                $config['db']['db_name']
        );
        if($connection->connect_error)
            die("Connection failed: " . $connection->connect_error);
        return $connection;
    }
}