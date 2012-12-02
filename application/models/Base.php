<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ildar
 * Date: 02.12.12
 * Time: 2:42
 * To change this template use File | Settings | File Templates.
 */
abstract class Model_Base extends Shanty_Mongo_Document
{
    protected static $_db;
    protected static $_instances = array();

    public static function setDbName($dbName)
    {
        self::$_db = $dbName;
    }

    /**
     * @static
     * @abstract
     * @return Shanty_Mongo_Document
     */
    public static function getInstance()
    {
        $class = get_called_class();
        if (array_key_exists($class, self::$_instances) === false)
            self::$_instances[$class] = new $class();
        return self::$_instances[$class];
    }

}
