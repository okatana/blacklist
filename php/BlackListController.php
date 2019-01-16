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
            $_SESSION['allow_edit'] = $user['allow_edit']; //доступ данного менеджера к редактированию. Если = 1, то может все: редактировать и смотреть-проверять
            echo  $this->checkView();
        } else {
            echo $this->view->renderError('Нет прав.');
        }
    }
    private function mainView($content){
        return $this->view->renderMainView($content);
    }
    private function checkView(){
        $vids = $this->model->getVidsInsurance();
        $checkResults = [];
if(!empty($_POST['submit'])){
    $lastname =  ($_POST && $_POST['lastname']) ? $_POST['lastname'] : '';
    $firstname = ($_POST && $_POST['firstname']) ? $_POST['firstname'] : '';
    $midname =   ($_POST && $_POST['midname']) ? $_POST['midname'] : '';
    $birthday =  ($_POST && $_POST['birthday']) ? $_POST['birthday'] : '';
    $vid =  ($_POST && $_POST['vid']) ? $_POST['vid'] : '';
}else{
    $lastname='';
    $firstname='';
    $midname='';
    $birthday='';
    $vid=0;
}
        $checkResults = $this->getCheckResults($lastname,$firstname,$midname,$birthday,$vid);

        $params=[
            'lastname'=>'иванов',
            'firstname'=>'иван',
            'midname'=>'иванович',
            'birthday'=>'1900-11-21',
            'vid'=>$vid,
            'vids'=>$vids,
            'checkResults'=>$checkResults,
        ];
        $content = $this->view->renderCheckView($params);
        return $this->mainView($content);
    }
   /* private function getCheckResults0(){
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
    }*/
    private function getCheckResults($lastname,$firstname,$midname,$birthday,$vid){
        return $this->model->getCheckResults($lastname,$firstname,$midname,$birthday,$vid);
    }

    private function getAddResults($lastname,$firstname,$midname,$birthday,$vid_id,$comment_info){
        return $this->model->getAddResults($lastname,$firstname,$midname,$birthday,$vid_id, $comment_info);
    }
    private function getLastAddedClient(){
        return $this->model->getLastAddedClient();
    }
    public function add($lastname,$firstname,$midname,$birthday,$vid_id,$comment_info){
        if($lastname!='' && $firstname!='' && $midname!='' && $birthday!=''){
            $addResults = $this->getAddResults($lastname,$firstname,$midname,$birthday,$vid_id,$comment_info);

        }else{
            echo "<span class='blacklist-span-red'>Поля, отмеченные звездочками, являются обязательными.</span>";
            $addResults=[];
            $addedResults =[];
        }
        $addedResults = $this->getLastAddedClient();
        $vids = $this->model->getVidsInsurance();
        $params=[
            'lastname'=>'',
            'firstname'=>'',
            'midname'=>'',
            'birthday'=>'',
            'vid_id'=>'',
            'vids'=>$vids,
            'addedResults'=>$addedResults,
        ];
        $content = $this->view->renderAddView($params);
        echo $this->mainView($content);
    }
    public function check($lastname,$firstname,$midname,$birthday,$vid){
        $vids = $this->model->getVidsInsurance();
        $checkResults = $this->getCheckResults($lastname,$firstname,$midname,$birthday,$vid);
        $params=[
            'lastname'=>$lastname,
            'firstname'=>$firstname,
            'midname'=>$midname,
            'birthday'=>$birthday,
            'vid'=>$vid,
            'vids'=>$vids,
            'checkResults'=>$checkResults,
        ];
        $content = $this->view->renderCheckView($params);
        echo $this->mainView($content);
    }
}