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
require 'BlackListExcel.php';

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
    public $user;
    private $mode;

    function __construct($config, $mode = 'view')
    {
        $this->config = $config;
        $this->mode = $mode;
        if ($mode == 'view') {
            $this->logger = new MyLogger($config['logger']);
        } else {
            $this->logger = null;
        }

        $this->model = new BlackListModel($config, $this->logger);
        if ($this->mode == 'view') {
            $this->view = new BlackListView($config['autoloader']);
        }

    }

    public function getModel()
    {
        return $this->model;
    }

    public function login($login, $password)
    {
        $user = $this->model->checkLogin($login, $password);//arr

        if ($user) {

            $this->user = $user;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['allow_edit'] = $user['allow_edit'];//доступ данного менеджера к редактированию. Если = 1, то может все: редактировать и смотреть-проверять

            if ($this->mode == 'view') {
                echo $this->checkView();
            }
        } else {
            if ($this->mode == 'view') {
                echo $this->view->renderError('Нет прав.');
            }
        }
    }

    private function mainView($content, $allow_edit)
    {
        return $this->view->renderMainView($content, $allow_edit);
    }

    private function checkView()
    {
        $vids = $this->model->getVidsInsurance();
        $checkResults = [];
        if (!empty($_POST['submit'])) {
            $lastname = ($_POST && $_POST['lastname']) ? $_POST['lastname'] : '';
            $firstname = ($_POST && $_POST['firstname']) ? $_POST['firstname'] : '';
            $midname = ($_POST && $_POST['midname']) ? $_POST['midname'] : '';
            $birthday = ($_POST && $_POST['birthday']) ? $_POST['birthday'] : '';
            $vid = ($_POST && $_POST['vid']) ? $_POST['vid'] : '';
        } else {
            $lastname = '';
            $firstname = '';
            $midname = '';
            $birthday = '';
            $vid = 0;
        }
        $checkResults = $this->getCheckResults($lastname, $firstname, $midname, $birthday, $vid);

        $params = [
            'lastname' => 'иванов',
            'firstname' => 'иван',
            'midname' => 'иванович',
            'birthday' => '1900-11-21',
            'vid' => $vid,
            'vids' => $vids,
            'checkResults' => $checkResults,
        ];

        $content = $this->view->renderCheckView($params);
        return $this->mainView($content, $_SESSION['allow_edit']);
    }


    public function add($lastname, $firstname, $midname, $birthday, $vid_id, $comment_info)
    {
        if ($lastname != '' && $firstname != '' && $midname != '' && $birthday != '' && $vid_id != '') {

            $addClient = $this->addClient($lastname, $firstname, $midname, $birthday, $vid_id, $comment_info);

        } else {
            // "<span class='blacklist-span-red'></span>";
            echo "<script>alert(\"Поля, отмеченные звездочками, являются обязательными.\");</script>";
            $addClient = [];
            $addedResults = [];
        }
        $addedResults = $this->getLastAddedClient();
        $vids = $this->model->getVidsInsurance();
        $params = [
            'lastname' => '',
            'firstname' => '',
            'midname' => '',
            'birthday' => '',
            'vid_id' => '',
            'vids' => $vids,
            'addedResults' => $addedResults,
        ];
        $content = $this->view->renderAddView($params);
        echo $this->mainView($content, $_SESSION['allow_edit']);
    }

    public function check($lastname, $firstname, $midname, $birthday, $vid)
    {
        $vids = $this->model->getVidsInsurance();
        $checkResults = $this->getCheckResults($lastname, $firstname, $midname, $birthday, $vid);

        if ($this->mode == 'api' || $this->mode == 'apitest') {
            if ($this->mode == 'apitest') {
                print_r($checkResults);
            }
            $result = new stdClass();

            if (count($checkResults) >= 1) {
                $resultObj = $checkResults[0];

                $result->comment = $resultObj->comment;
                $result->email = $resultObj->email;
            }
            $result=json_encode($result,  JSON_HEX_QUOT);

            //if($this->mode=='api'){
            $logfile = 'log/debug.log';
            $message = 'check()  lastname = ' . $lastname;
            $message .=  '  '.$firstname;
            $message .=  '  '.$midname;
            $message .=  '  '.$birthday;
            $message .=  '$vid=  '.$vid. PHP_EOL;
            file_put_contents($logfile, $message, FILE_APPEND);

            $message = 'check()  result = ' . $result. PHP_EOL;;
            file_put_contents($logfile, $message, FILE_APPEND);
            // }


            if ($this->mode == 'apitest') {
                echo $result;
            } else {
                echo $result;
                return;
            }

        } else {
            $params = [
                'lastname' => $lastname,
                'firstname' => $firstname,
                'midname' => $midname,
                'birthday' => $birthday,
                'vid' => $vid,
                'vids' => $vids,
                'checkResults' => $checkResults,
                'allow_edit' => $_SESSION['allow_edit'],
            ];
            $content = $this->view->renderCheckView($params);
            echo $this->mainView($content, $_SESSION['allow_edit']);
        }


    }

    public function addFromFile()
    {
        $vids = $this->model->getVidsInsurance();
        $params = [
            'allow_edit' => $_SESSION['allow_edit'],
            'vids' => $vids,
        ];

        $content = $this->view->renderAddFromFileView($params, $_SESSION['allow_edit']);
        echo $this->mainView($content, $_SESSION['allow_edit']);
    }

    public function getFile($size)
    {
        echo 12;

    }

    public function toExcel()
    {

        $content = $this->view->renderToExcelView();
        echo $this->mainView($content, $_SESSION['allow_edit']);
    }

    public function excel($vids = '')
    {
        $tmpdir = $this->config['tmpdir'];
        // $this->logger->info('model = '. print_r($this->model->getDepartmentForDate($fordate)));
        (new BlackListExcel($tmpdir))->toExcel($this->model, $vids);
    }


    private function getCheckResults($lastname, $firstname, $midname, $birthday, $vid)
    {
        return $this->model->getCheckResults($lastname, $firstname, $midname, $birthday, $vid);
    }

    private function addClient($lastname, $firstname, $midname, $birthday, $vid_id, $comment_info)
    {

        return $this->model->addClient($lastname, $firstname, $midname, $birthday, $vid_id, $comment_info);
    }

    private function getLastAddedClient()
    {
        return $this->model->getLastAddedClient();
    }

}