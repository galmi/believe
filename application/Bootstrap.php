<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(
            array(
                'namespace' => '',
                'basePath'  => APPLICATION_PATH
            )
        );
        return $moduleLoader;
    }

    function _initMongodb()
    {
        require_once 'Shanty/Mongo.php';

        $mongodb = $this->getOption('mongodb');
        $connections = array(
            'master' => array(
                'host' => $mongodb['host'],
                'port' => $mongodb['port']
            )
        );
        if (!empty($mongodb['username']) && !empty($mongodb['password']))
        {
            $connections['master']['username'] = $mongodb['username'];
            $connections['master']['password'] = $mongodb['password'];
        }
        Shanty_Mongo::addConnections($connections);
        Model_Base::setDbName($mongodb['db']);
    }

    function _initVkontakte() {
        $options = $this->getOption('vkontakte');
        Model_Vkontakte::init($options);
    }

}

