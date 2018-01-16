<?php
class User_Resource_Table extends System_Db_Table
{
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_name = 'users_resources';
	    
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_dependentTables = array('User_Rule_Table');

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