<?php

class IndexController extends App_Controller_Base
{

    public function indexAction()
    {
        // action body
    }

    public function authorsAction() {
        $authors = Zend_Registry::get('authors');
        $this->view->authors=$authors;
        if(in_array($this->_userId, $authors)){
            $this->view->authorsCheck=TRUE;
        }else{
            $this->view->authorsCheck=FALSE;
        }
    }

}

