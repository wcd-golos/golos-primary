<?php
class User_Rule_Table extends System_Db_Table
{
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_name = 'users_rules';
	    
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_referenceMap    = array(
        'UserRole' => array(
            'columns'           => 'User_Role_ID',
            'refTableClass'     => 'User_Role_Table',
            'refColumns'        => 'ID'
        ),
        'UserResource' => array(
            'columns'           => 'User_Resource_ID',
            'refTableClass'     => 'User_Resource_Table',
            'refColumns'        => 'ID'
        ));

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
