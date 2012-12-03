<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ildar
 * Date: 02.12.12
 * Time: 2:48
 * To change this template use File | Settings | File Templates.
 */
abstract class App_Controller_Base extends Zend_Controller_Action
{
    protected $_userId;

    public function init()
    {
        $this->view->app_id = Model_Vkontakte::getAppID();
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->_userId = $auth->getIdentity();
            $user = Model_Users::find($this->_userId);
        } elseif (Model_Vkontakte::checkAuthKey($this->_getAllParams())) {
            Zend_Session::rememberMe(1209600);
            $session = $auth->getStorage();
            $viewer_id = $this->_getParam('viewer_id');
            $session->write($viewer_id);
            $this->_userId = $viewer_id;
            $user = Model_Users::find($viewer_id);
            if (!$user) {
                $user = Model_Users::getInstance();
                $user->uid = $viewer_id;
                $user->save();
            }
        }
        if (isset($user) && !is_null($user)) {
            $user->ts = time();
            $user->save();
        }
        $authors = Zend_Registry::get('authors');
        if (in_array($this->_userId, $authors)) {
            $this->view->admin = true;
        } else {
            $this->view->admin = false;
        }
        $this->view->server_name = $_SERVER["SERVER_NAME"];
        $this->view->headLink()->appendStylesheet($this->linkToStatic('/style.css'),'screen,print');
        $this->view->headScript()->appendFile($this->linkToStatic('/js/Common.js'), 'text/javascript');
        $this->view->headScript()->appendFile($this->linkToStatic('/js/Feed.js'), 'text/javascript');
    }

    private function linkToStatic($file, $prefix='') {
        $localfile = APPLICATION_PATH.'/../php/'.$file;
        $mtime = @filemtime($localfile);
        if ($mtime) {
            return "{$prefix}$file?_dc=$mtime";
        }
        return "{$prefix}$file";
    }

}
