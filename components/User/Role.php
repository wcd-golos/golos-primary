<?php
class User_Role extends System_Db_Object implements Zend_Acl_Role_Interface
{
    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function getRoleId()
	{
	    return $this->Name;
	}
}
