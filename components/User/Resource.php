<?php
class User_Resource extends System_Db_Object implements Zend_Acl_Resource_Interface
{
    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function getResourceId()
    {
        return $this->Name;
    }
}