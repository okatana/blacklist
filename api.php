<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/config.php';
require_once 'php/BlackListController.php';

echo 'test api';

print_r($_POST);
$error = false;
if(isset($_POST['apilogin'])){$apilogin= $_POST['apilogin']; }else{$apilogin = false;};
if(isset($_POST['apipassword'])){$apipassword= $_POST['apipassword']; }else{$apipassword = false;};
if($apilogin && $apipassword){
   /* echo '$apilogin' . $apilogin;
    echo '$apipassword' . $apipassword;*/
    $controller = new BlackListController($config, 'api');
    $controller->login($apilogin,$apipassword);
    if($controller->user) {
echo 'логин подошел';

        if(isset($_POST['lastname'])){$lastname = $_POST['lastname']; }else{$lastname = '';};
        if(isset($_POST['firstname'])){$firstname = $_POST['firstname']; }else{$firstname = '';};
        if(isset($_POST['midname'])){$midname = $_POST['midname']; }else{$midname = '';};
        if(isset($_POST['birthday'])){$birthday = $_POST['birthday']; }else{$birthday = '';};
        if(isset($_POST['vid'])){$vid = $_POST['vid']; }else{$vid = '';};

        $controller->check($lastname,$firstname,$midname,$birthday,$vid);




    } else {
        $error = 'Логин и пароль не подходят';
    }
} else {
    $error = 'Логин и/или пароль не заданы';
}
if ($error) {
    echo $error;
}