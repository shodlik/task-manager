<?php

require  __DIR__."/../vendor/autoload.php";

$config = require __DIR__ . '/../config/main.php';

$route = new \vendor\Route($_SERVER['REQUEST_URI'],$config);

?>