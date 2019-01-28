
<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 20.03.2017
 * Time: 17:10
 */

/*require 'php/MyLogger.php';*/
require_once 'config/config.php';
require_once 'php/BlackListController.php';


//echo 'session_id()='. session_id();

$logger=new MyLogger('test logger');
//$logger->info('info test logger');

//$logger->info($config['dsn']);
// контроллер
$controller = new BlackListController($config);
$controller->login('admin','admin');


try{
    if($_POST && $_POST['command']){
        $command = $_POST['command'];
    }else{
       // echo '<h2 style=" width: 100%;padding-top:50px; text-align: center;"><img src="images/errors.jpg" width="445" height="314"  ></h2><h2 style=" width: 100%;padding-top:50px; text-align: center;">Для того, чтобы зайти в Личный кабинет, Вам нужно пройти авторизацию. <br/>Перейдите по <a href="/index.html">ссылке</a>, введите свой логин и пароль, нажмите кнопку Принять.</h2>';
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
        if(isset($_POST['vid_id'])){$vid_id = $_POST['vid_id']; }else{$vid_id = '';};
        if(isset($_POST['comment_info'])){$comment_info = $_POST['comment_info']; }else{$comment_info = '';};
        $controller->add($lastname,$firstname,$midname,$birthday,$vid_id,$comment_info);
        break;
    case 'addFromFile':
        $controller->addFromFile();
        break;
    case 'getFile':
        $controller->getFile($_FILES['userfile']['size']);
        break;
    case 'excel':
    case 'toExcel':
        if(isset($_POST['vzr'])){$vzr = $_POST['vzr']; }else{$vzr = '';};
        $controller->toExcel([$vzr]);
        break;
    case 'check':
        if(isset($_POST['lastname'])){$lastname = $_POST['lastname']; }else{$lastname = '';};
        if(isset($_POST['firstname'])){$firstname = $_POST['firstname']; }else{$firstname = '';};
        if(isset($_POST['midname'])){$midname = $_POST['midname']; }else{$midname = '';};
        if(isset($_POST['birthday'])){$birthday = $_POST['birthday']; }else{$birthday = '';};
        if(isset($_POST['vid'])){$vid = $_POST['vid']; }else{$vid = '';};
        if(isset($_POST['comment_info'])){$comment_info = $_POST['comment_info']; }else{$comment_info = '';};
        if(isset($_POST['api'])){$api= $_POST['api']; }else{$api = 0;};

         $controller->check($lastname,$firstname,$midname,$birthday,$vid,$api);


        break;

    default:
        echo 'неизвестная команда '. $command;
}


/****/
require_once 'UsersApi.php';

try {
    $api = new usersApi();
    echo $api->run();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
?>
