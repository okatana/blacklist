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

    public function renderMainView($content,$allow_edit,$user_id){
        return $this->twig->render('main.twig', array('content'=>$content, 'allow_edit'=>$allow_edit, 'user_id'=>$user_id));
    }

    public function renderCheckView($params){
        return $this->twig->render('check.twig', $params);
    }

    public function renderAddView($params){
        return $this->twig->render('add.twig', $params);
    }

    public function renderAddFromFileView($params){
        return $this->twig->render('addFromFile.twig', $params);
    }

    public function renderToExcelView(){
        return $this->twig->render('toExcel.twig');
    }
    public function renderToDocumentationView(){
        return $this->twig->render('documentation.twig');
    }
    public function renderEditView($params){
        return $this->twig->render('edit.twig', $params);

    }
    public function renderUpdateView($params){
        return $this->twig->render('updated.twig', $params);

    }
    public  function renderDeleteView($params){
        return $this->twig->render('deleted.twig', $params);
    }

}