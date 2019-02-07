<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache">
    <title>Проверка клиентов</title>
    <link rel='stylesheet' href='css/blacklist.css' type='text/css' media='all' />
    <link rel='stylesheet' href='css/blacklistIndexHtml.css' type='text/css' media='all' />

    <link rel="shortcut icon" type="image/x-icon" href="images/1548252846.ico">
</head>
<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20.03.2017
 * Time: 17:10
 */

header("Cache-Control: no-store, no-cache, must-revalidate");
header('Content-type: text/html; charset=UTF-8');
header("Expires: " . date("r"));
//echo "<h4>", date("H:i:s"), "</h4>";


/*require 'php/MyLogger.php';*/
require_once 'config/config.php';
require_once 'php/BlackListController.php';


//echo 'session_id()='. session_id();

$logger=new MyLogger('test logger');
$logger->info('info test logger');


$logger->info($config['dsn']);
// контроллер
$controller = new BlackListController($config);



try{
    if($_GET && $_GET['id']){
        $id = $_GET['id'];
    }else{
        echo '<h2 style=" width: 100%;padding-top:50px; text-align: center;">
<img src="images/errors.jpg" width="445" height="314"  ></h2>
<h2 style=" width: 100%;padding-top:50px; text-align: center;">Для того, чтобы зайти в Личный кабинет, Вам нужно пройти авторизацию. <br/>Перейдите по <a href="/index.html">ссылке</a>, введите свой логин и пароль, нажмите кнопку Принять.</h2>';
        return;
    }
}finally{};





$controller->edit($id);


?>
