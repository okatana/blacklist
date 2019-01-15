<?php
/**
 * Created by PhpStorm.
 * User: serggin
 * Date: 13.01.19
 * Time: 19:26
 */

class BlackListView
{
    private $twig;

    function __construct($autoloader)
    {
        require_once $autoloader;
        $loader = new Twig_Loader_Filesystem('php/templates');
        $this->twig = new Twig_Environment($loader);
    }


    public function renderError($message){
        return $this->twig->render('error.twig', array('message' => $message));
    }

    public function renderMainView($content){
        return $this->twig->render('main.twig', array('content'=>$content, 'allow_edit'=>$_SESSION['allow_edit']));
    }

    public function renderCheckView($params){
        return $this->twig->render('check.twig', $params);
    }


}