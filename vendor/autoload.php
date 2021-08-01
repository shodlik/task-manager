<?php
$models = scandir(__DIR__."/../models");
require  __DIR__."/../vendor/Base.php";
require  __DIR__."/../vendor/Session.php";
require  __DIR__."/../vendor/Db.php";
require  __DIR__."/../vendor/ActiveRecord.php";
foreach ($models as $key=>$value){
    if($key>1){
        require  __DIR__."/../models/{$value}";
    }
}
require  __DIR__."/../vendor/View.php";
require  __DIR__."/../vendor/Controller.php";
require  __DIR__."/../vendor/Route.php";




require  __DIR__."/../controller/SiteController.php";
