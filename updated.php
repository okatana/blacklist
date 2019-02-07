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
    if($_POST && $_POST['command']){
        $command = $_POST['command'];

    }
    if($_POST && $_POST['clientID']){
        $clientID = $_POST['clientID'];

    }
}finally{};



switch($command){
    case 'updateClientbyID':
        if(isset($_POST['clientID'])){$clientID = $_POST['clientID']; }else{$clientID = '';};
        if(isset($_POST['lastname'])){$lastname = $_POST['lastname']; }else{$lastname = '';};
        if(isset($_POST['firstname'])){$firstname = $_POST['firstname']; }else{$firstname = '';};
        if(isset($_POST['midname'])){$midname = $_POST['midname']; }else{$midname = '';};
        if(isset($_POST['birthday'])){$birthday = $_POST['birthday']; }else{$birthday = '';};
        if(isset($_POST['comment_info'])){$comment_info = $_POST['comment_info']; }else{$comment_info = '';};

        break;
}
$controller->update($clientID,$lastname,$firstname,$midname,$birthday,$comment_info);
?>
