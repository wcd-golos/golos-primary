<?php
class User_Role_Table extends System_Db_Table
{
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_name = 'users_roles';
	    
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_dependentTables = array('User_Table', 'User_Rule_Table');

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function __construct()
	{
		parent::__construct();
	}
	
}


