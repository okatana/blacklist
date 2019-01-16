<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20.03.2017
 * Time: 17:10
 */

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r"));
echo "<h6>", date("H:i:s"), "</h6>";


/*require 'php/MyLogger.php';*/
require 'config/config.php';
require 'php/BlackListController.php';
//print_r($_POST);
/*$logger=new \guideh\MyLogger('test logger');
$logger->info('info test logger');*/
session_start();
// контроллер
$controller = new BlackListController($config);
try{
    if($_POST && $_POST['command']){
        $command = $_POST['command'];
    }else{
        echo '<h2 style=" width: 100%;padding-top:50px; text-align: center;"><img src="http://gip.jscguideh.ru/friday_news/img/errors.jpg" width="445" height="314"  ></h2><h2 style=" width: 100%;padding-top:50px; text-align: center;">Для того, чтобы зайти в Личный кабинет, Вам нужно пройти авторизацию. <br/>Перейдите по <a href="/index.html">ссылке</a>, введите свой логин и пароль, нажмите кнопку Принять.</h2>';
        return;
    }
}finally{};
switch ($command){
    case 'login':
        $controller->login($_POST['login'], $_POST['password']);
        break;
    case 'add':
        if(isset($_POST['lastname'])){$lastname = $_POST['lastname']; }else{$lastname = '';};
        if(isset($_POST['firstname'])){$firstname = $_POST['firstname']; }else{$firstname = '';};
        if(isset($_POST['midname'])){$midname = $_POST['midname']; }else{$midname = '';};
        if(isset($_POST['birthday'])){$birthday = $_POST['birthday']; }else{$birthday = '';};
        if(isset($_POST['vid'])){$vid = $_POST['vid']; }else{$vid = '';};
        $controller->add($lastname,$firstname,$midname,$birthday,$vid);
        break;
    case 'check':
        if(isset($_POST['lastname'])){$lastname = $_POST['lastname']; }else{$lastname = '';};
        if(isset($_POST['firstname'])){$firstname = $_POST['firstname']; }else{$firstname = '';};
        if(isset($_POST['midname'])){$midname = $_POST['midname']; }else{$midname = '';};
        if(isset($_POST['birthday'])){$birthday = $_POST['birthday']; }else{$birthday = '';};
        if(isset($_POST['vid'])){$vid = $_POST['vid']; }else{$vid = '';};

         $controller->check($lastname,$firstname,$midname,$birthday,$vid);
        break;
    default:
        echo 'неизвестная команда '. $command;
}