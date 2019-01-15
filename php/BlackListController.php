<?php
/**
 * Created by PhpStorm.
 * User: serggin
 * Date: 13.01.19
 * Time: 18:51
 */
require 'MyLogger.php';
require 'BlackListModel.php';
require 'BlackListView.php';
/*
require_once 'vendor/autoload.php';
use MyLogger;
use BlackListModel;
use BlackListView;
*/
class BlackListController
{
    private $config;
    private $model;
    private $view;
    private $logger;
    private $user;
    function __construct($config)
    {
        $this->config = $config;
        $this->logger = new MyLogger($config['logger']);
        $this->model = new BlackListModel($config, $this->logger);
        $this->view = new  BlackListView($config['autoloader']);
    }
    public function login($login, $password)
    {
        $user = $this->model->checkLogin($login, $password);//arr
        if ($user) {
            $this->user = $user;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['allow_edit'] = $user['allow_edit'];
            echo  $this->checkView();
        } else {
            echo $this->view->renderError('Нет прав.'); //проверить, что в твиге!!
        }
    }
    private function mainView($content){
        return $this->view->renderMainView($content);
    }
    private function checkView(){
        $vids = $this->model->getVidsInsurance();
        // $checkResults = [];
        $checkResults = $this->getCheckResults();
        $params=[
            'lastname'=>'иванов',
            'firstname'=>'иван',
            'midname'=>'иванович',
            'birthday'=>'1900-11-21',
            'vids'=>$vids,
            'checkResults'=>$checkResults,
        ];
        $content = $this->view->renderCheckView($params);
        return $this->mainView($content);
    }
    private function getCheckResults0(){
        return [
            ['lastname'=>'иванов1',
                'firstname'=>'иван',
                'midname'=>'иванович',
                'birthday'=>'1900-11-21'
            ],
            ['lastname'=>'иванов2',
                'firstname'=>'иван',
                'midname'=>'иванович',
                'birthday'=>'1900-11-21'
            ]
        ];
    }
    private function getCheckResults($lastname,$firstname,$midname,$birthday){
        return $this->model->getCheckResults($lastname,$firstname,$midname,$birthday);
    }
    public function check($lastname,$firstname,$midname,$birthday){
        $vids = $this->model->getVidsInsurance();
        $checkResults = $this->getCheckResults($lastname,$firstname,$midname,$birthday);
        $params=[
            'lastname'=>$lastname,
            'firstname'=>$firstname,
            'midname'=>$midname,
            'birthday'=>$birthday,
            'vids'=>$vids,
            'checkResults'=>$checkResults,
        ];
        $content = $this->view->renderCheckView($params);
        echo $this->mainView($content);
    }
}