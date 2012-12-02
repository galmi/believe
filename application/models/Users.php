<?php
class Model_Users extends Model_Base
{
    protected static $_collection = 'users';

    protected static $_requirements = array(
        'uid' => 'Validator:Int',
        'friends' => 'Array',
        'stories' => 'Array'
    );

    /**
     * @static
     * @return Model_Users
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * Поиск юзера по uid
     * @static
     * @param $uid
     * @param array $fields
     * @return Shanty_Mongo_Document
     */
    public static function find($uid, array $fields = array())
    {
        $query = array('uid' => $uid);
        return static::one($query, $fields);
    }

}