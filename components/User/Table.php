<?php
class User_Table extends System_Db_Table
{
	/**
     * Documentation
     *
     * @var mixed
     */
    protected static $_instance = null;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_name = 'users';
	    
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_referenceMap    = array(
        'RoleID' => array(
            'columns'           => 'RoleID',
            'refTableClass'     => 'User_Role_Table',
            'refColumns'        => 'ID'
    ));

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}