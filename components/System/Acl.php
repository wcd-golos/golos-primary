<?php
/**
 * Documentation
 * @author
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Acl extends System
{
    /**
     * System_Acl static Instance
     *
     * @var Zend_Controller_Front
     */
    protected static $_instance;

    /**
     * array (tree) of User_Role
     *
     * @var array
     */
    protected static $_roles;

    /**
     * array (tree) of User_Resource
     *
     * @var array
     */
    protected static $_resources;

    /**
     * User_Role_Table static Instance
     *
     * @var User_Role_Table
     */
    protected static $_tableUserRoles;

    /**
     * Static Array of Loaded User_Roles instances
     *
     * @var array of User_Role
     */
    protected static $_arrayUserRoles;

    /**
     * User_Resource_Table static Instance
     *
     * @var User_Resource_Table
     */
    protected static $_tableUserResources;

    /**
     * Static Array of Loaded User_Resource instances
     *
     * @var array of User_Resource
     */
    protected static $_arrayUserResources;

    /**
     * User_Resource_Table static Instance
     *
     * @var User_Resource_Table
     */
    protected static $_tableUserRules;

     /**
     * Zend_Acl static instance
     *
     * @var Zend_Acl
     */
    protected static $_acl;

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _init()
    {
        self::$_acl = new Zend_Acl();
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _run()
    {
        /*System_Loader::loadClass('User_Resource_Table');
        System_Loader::loadClass('User_Role_Table');
        System_Loader::loadClass('User_Rule_Table');*/
        self::$_tableUserResources = new User_Resource_Table();
        self::$_tableUserRoles = new User_Role_Table();
        self::$_tableUserRules = new User_Rule_Table();
        $this->loadRoles();
        $this->loadRecources();
        $this->loadRules();
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _preDispatch(Zend_Controller_Request_Abstract $request)
    {
        //$request =  $this->getRequest();
        $roleUserLogged     = System_Session::getParam('LoggedUserRole');

        $resourceSection    = new Zend_Acl_Resource($request->getParam('section', System_Application::getInstance()->getDefaultSection()));
        $resourceModule     = new Zend_Acl_Resource($resourceSection->getResourceId() . '-' . $request->getModuleName());
        $resourceController = new Zend_Acl_Resource($resourceModule->getResourceId() . '-' . $request->getControllerName());
        $resourceAction     = new Zend_Acl_Resource($resourceController->getResourceId() . '-' . $request->getActionName());

        System_Acl::loadRules($roleUserLogged, $resourceSection);
        System_Acl::loadRules($roleUserLogged, $resourceModule);
        System_Acl::loadRules($roleUserLogged, $resourceController);
        System_Acl::loadRules($roleUserLogged, $resourceAction);

        if ($this->getConfig()->ModeDebug) {
            $resource = $resourceSection;
        } else {
            $resource = $resourceAction;
        }
        //var_dump($resource);
        if (!System_Acl::isAllowed($roleUserLogged, $resource)) {
            $request->setParam('section', System_Application::getInstance()->getDefaultSection());
            throw new Zend_Exception('Access Denied From Role:' . $roleUserLogged->getRoleId().
                                                        ' to Resource:' . $resourceSection->getResourceId()."\n", 403);
        }
    }


    /**
     * Return SaveNewRecords status defined in config
     * @author keeper
     * @return int
     */
    public function isSaveNewRecords()
    {
        if (isset($this->_config->SaveNewRecords)) {
            return (int)$this->_config->SaveNewRecords;
        } else {
            return 1;
        }
    }

    protected function loadRoles()
    {
        $listUserRoles = self::$_tableUserRoles->fetchAll();
        $arrayUserRoles = array();
        foreach ($listUserRoles as $objectUserRole){
            $arrayUserRoles[$objectUserRole->ID] = $objectUserRole;
        }
        $result = false;
        while ($result == false){
            $result = true;
            foreach ($listUserRoles as $objectUserRole){
                if(self::$_acl->hasRole($objectUserRole)){
                    continue;
                }
                if (!$objectUserRole->ParentID){
                    self::$_acl->addRole($objectUserRole);
                }else{
                    if (!self::$_acl->hasRole($arrayUserRoles[$objectUserRole->ParentID])){
                        $result = false;
                        continue;
                    }
                    if (!isset($arrayUserRoles[$objectUserRole->ParentID])){
                        throw new Zend_Exception('Parent of User Role with ID = ' . $objectUserRole->ParentID . ' not found in database');
                    }
                    self::$_acl->addRole($objectUserRole, $arrayUserRoles[$objectUserRole->ParentID]);
                }

            }
        }
        self::$_arrayUserRoles = $arrayUserRoles;
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    protected function loadRecources()
    {
        $listUserRecources = self::$_tableUserResources->fetchAll();

        $arrayUserRecources = array();
        foreach ($listUserRecources as $objectUserRecource){
            $arrayUserRecources[$objectUserRecource->ID] = $objectUserRecource;
        }

        $result = false;
        while ($result == false){
            $result = true;
            foreach ($listUserRecources as $objectUserRecource){
                if(self::$_acl->has($objectUserRecource)){
                    continue;
                }
                if (!$objectUserRecource->ParentID){
                    self::$_acl->add($objectUserRecource);
                }else{
                    if (!self::$_acl->has($arrayUserRecources[$objectUserRecource->ParentID])){
                        $result = false;
                        continue;
                    }
                    if (!isset($arrayUserRecources[$objectUserRecource->ParentID])){
                        throw new Zend_Exception('Parent of User Resource with ID = ' . $objectUserRecource->ParentID . ' not found in database');
                    }
                    self::$_acl->add($objectUserRecource, $arrayUserRecources[$objectUserRecource->ParentID]);
                }
            }
        }
        self::$_arrayUserResources = $arrayUserRecources;
    }

	/**
     * @param  Zend_Acl_Role_Interface|string     $role
     * @param  Zend_Acl_Resource_Interface|string $resource
     * @param  string                             $privilege
     * @uses   Zend_Acl::get()
     * @uses   Zend_Acl_Role_Registry::get()
     * @return boolean
     */
    public static function isAllowed($role = null, $resource = null, $privilege = null)
    {
        self::loadRules($role, $resource);
        if (!self::$_acl->hasRole($role) || !self::$_acl->has($resource)) {
            return false;
        }
        if (!self::$_acl->isAllowed($role, $resource, $privilege)){
            return false;
        }
        return true;
    }

    /**
     * @param  Zend_Acl_Role_Interface|string     $role
     * @return void
     */
    public static function saveRole($role = null)
    {
        if (self::getInstance()->isSaveNewRecords()) {
            $objectUserRole = self::$_tableUserRoles->fetchNew();
            $objectUserRole->Name = $role->getRoleId();
            $objectUserRole->save();
            self::$_arrayUserRoles[$objectUserRole->ID] = $objectUserRole;
            self::$_acl->addRole($objectUserRole);
        }
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function saveResource($resource = null)
    {
        if (self::getInstance()->isSaveNewRecords()) {
            $objectUserResource = self::$_tableUserResources->fetchNew();
            $objectUserResource->Name = $resource->getResourceId();
            $objectUserResource->save();
            self::$_arrayUserResources[$objectUserResource->ID] = $objectUserResource;
            self::$_acl->add($objectUserResource);
        } else {
            //$request->setParam('section', System_Application::getInstance()->getDefaultSection());
            throw new Zend_Exception('Page not found', 404);
        }
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function loadRules($role = null, $resource = null)
    {
        $selectUserRules = self::$_tableUserRules->select();
        if ($role) {
            if (!self::$_acl->hasRole($role)) {
                self::saveRole($role);
                return false;
            }
            if (self::$_acl->hasRole($role)) {
                $userRole = self::$_acl->getRole($role);
                $selectUserRules->where('User_Role_ID = ?', $userRole->ID);
            } else {
                return;
            }
        }

        if ($resource) {
            if (!self::$_acl->has($resource)) {
                self::saveResource($resource);
                return false;
            }
            if (self::$_acl->has($resource)) {
                $userResource = self::$_acl->get($resource);
                $selectUserRules->orWhere('User_Resource_ID = ?', $userResource->ID);
            } else {
                return;
            }
        }
        $listUserRules = self::$_tableUserRules->fetchAll($selectUserRules);
        foreach ($listUserRules as $objectUserRule) {
            self::addRule($objectUserRule);
        }
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public static function addRule($objectUserRule)
    {
        $objectUserResource = NULL;
        $objectUserRole = NULL;
        if ($objectUserRule->User_Resource_ID){
            if (isset(self::$_arrayUserResources[$objectUserRule->User_Resource_ID])){
                $objectUserResource = self::$_arrayUserResources[$objectUserRule->User_Resource_ID];
            }
        }

        if ($objectUserRule->User_Role_ID){
            if (isset(self::$_arrayUserRoles[$objectUserRule->User_Role_ID])){
                $objectUserRole = self::$_arrayUserRoles[$objectUserRule->User_Role_ID];
            }
        }
        self::$_acl->allow($objectUserRole, $objectUserResource);
    }

    /**
    * Return Cach_Core static instance
    *
    * @return System_Acl
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
