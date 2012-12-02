<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ildar
 * Date: 02.12.12
 * Time: 18:58
 * To change this template use File | Settings | File Templates.
 */
class UserController extends App_Controller_Base
{

    public function appusersAction()
    {
        //массив с именем friendsApp нам отдает вконтакт, в нем содержатся список уидов друзей приложения
        $friendsApp = $this->_getParam('friendsApp', null);
        $userFriendsApp=Model_Users::find($this->_userId);
        if ($userFriendsApp->friends != $friendsApp){
            $userFriendsApp->friends = $friendsApp;
            $userFriendsApp->save();
        }
    }

}
