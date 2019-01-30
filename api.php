<?php

if(isset($_POST['command'])){
    $mode = 'apitest';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
  //  print_r($_POST);
}else{
    $mode = 'api';
};

require_once 'config/config.php';
require_once 'php/BlackListController.php';


$error = false;
if(isset($_POST['apilogin'])){$apilogin= $_POST['apilogin']; }else{$apilogin = false;};
if(isset($_POST['apipassword'])){$apipassword= $_POST['apipassword']; }else{$apipassword = false;};
if($apilogin && $apipassword){
    /*if($mode=='apitest'){
        echo '$apilogin' . $apilogin;
        echo '$apipassword' . $apipassword;
    }*/

    $controller = new BlackListController($config, $mode);
    $controller->login($apilogin,$apipassword);
    if($controller->user) {
        /*if($mode=='apitest'){
            echo 'логин подошел';
        }*/


        if(isset($_POST['lastname'])){$lastname = $_POST['lastname']; }else{$lastname = '';};
        if(isset($_POST['firstname'])){$firstname = $_POST['firstname']; }else{$firstname = '';};
        if(isset($_POST['midname'])){$midname = $_POST['midname']; }else{$midname = '';};
        if(isset($_POST['birthday'])){$birthday = $_POST['birthday']; }else{$birthday = '';};
        if(isset($_POST['vid'])){$vid = $_POST['vid']; }else{$vid = '';};

        $result=$controller->check($lastname,$firstname,$midname,$birthday,$vid);


        if($mode=='apitest'){
            echo $result;
        }else{
            return $result;
        }

    } else {
        $error = 'Логин и пароль не подходят';
    }
} else {
    $error = 'Логин и/или пароль не заданы';
}
if ($error) {
    if($mode=='apitest'){
        echo $error;
    }else{
        return $error;
    }
}