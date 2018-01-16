<?php
class System_User extends System
{

    /**
     * exemplar of class User for logged in user
     * @var User
     */
    private $objectUser = null;

    /**
     * exemplar of class User_Role for logged in user
     * @var User_Role
     */
    private $objectUserRole = null;

    /**
     * Documentation
     *
     * @var mixed
     */
    protected static $_instance = null;

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _init()
    {

    }

    /**
    *
    * @author keeper
    * @return void
    */
    public function _run()
    {

    }

    /**
    * Documentation
    * @author keeper
    * @return mixed
    */
    public function _preDispatch(Zend_Controller_Request_Abstract $request)
    {
        // set current user
        if (!System_Session::isSetParam('LoggedUser')) {
            $tableUser = new User_Table();
            $tableUserRole = new User_Role_Table();
            /*if (isset($this->getConfig()->RememberMe)) {
                if ($this->getConfig()->RememberMe->Enabled && $this->getConfig()->RememberMe->Varname) {
                }
            }*/
            $objectUser = $tableUser->find(self::getGuestID())->current();
            $objectUserRole = $tableUserRole->find(self::getGuestRoleID())->current();
            System_Session::setParam('LoggedUser', $objectUser);
            System_Session::setParam('LoggedUserRole', $objectUserRole);
        } else {
            $objectUser = System_Session::getParam('LoggedUser');
            $objectUserRole = System_Session::getParam('LoggedUserRole');
        }

        $this->objectUser = $objectUser;
        $this->objectUserRole = $objectUserRole;

        $this->selftest();

        $paramUserID = $request->getParam('User_ID', $request->getParam('userid'));
        $request->setParam('User_ID', $objectUser['ID']);
        $request->setParam('userid', $objectUser['ID']);

        if ($objectUser['RoleID'] == self::getAdminRoleID() && $paramUserID){
            $request->setParam('User_ID', $paramUserID);
            $request->setParam('userid', $paramUserID);
        }
    }

    /**
     * method that keep class's vars in valid state
     * @author keeper
     */
    private function selftest()
    {
        if (!is_object($this->objectUser)) {
            $tableUser = new User_Table();
            $this->objectUser = $tableUser->find( self::getGuestID() )->current();
        }
        if (!is_object($this->objectUserRole)) {
            $tableUserRole = new User_Role_Table();
            if (isset($this->objectUser->RoleID)) {
                $this->objectUserRole = $tableUserRole->find( $this->objectUser->RoleID )->current();
            } else {
                $this->objectUserRole = $tableUserRole->find( self::getGuestRoleID() )->current();
            }
        }
        if (!is_object($this->objectUser) || !is_object($this->objectUserRole)) {
            throw new Zend_Exception('System User Critical Error. Please contact support to resolve the problem.');
        }
    }

	/**
    * function for user login; can be called after dispatch (works with object request)
    * @author keeper
    * @return mixed
    */
    public function _login($User_ID)
    {
        $tableUser = new User_Table();
        $tableUserRole = new User_Role_Table();

        $objectUser = $tableUser->find( $User_ID )->current();
        $objectUserRole = $tableUserRole->find( $objectUser->RoleID )->current();

        System_Session::setParam('LoggedUser', $objectUser);
        System_Session::setParam('LoggedUserRole', $objectUserRole);

        $this->objectUser = $objectUser;
        $this->objectUserRole = $objectUserRole;

        $this->selftest();

        $request = Zend_Controller_Front::getInstance()->getRequest();

        $paramUserID = $request->getParam('User_ID', $request->getParam('userid'));
        $request->setParam('User_ID', $objectUser['ID']);
        $request->setParam('userid', $objectUser['ID']);

        if ($objectUser['RoleID'] == 1 && $paramUserID) {
            $request->setParam('User_ID', $paramUserID);
            $request->setParam('userid', $paramUserID);
        }
    }

    /**
    * function for user login; can be called after dispatch (works with object request)
    * @author keeper
    * @return mixed
    */
    public static function login($User_ID)
    {
        return self::getInstance()->_login($User_ID);
    }


    /**
    * function for user logout; can be called after dispatch (works with object request)
    * @author keeper
    * @return mixed
    */
    public function _logout()
    {
        $this->_login( self::getGuestID() );
    }

    /**
    * function for user logout; can be called after dispatch (works with object request)
    * @author keeper
    * @return mixed
    */
    public static function logout()
    {
        return self::getInstance()->_logout();
    }



    /**
     * get Logged in user ID
     * @author keeper
     * @return int User_ID
     */
    public function _getID()
    {
        $this->selftest();
        return $this->objectUser->ID;
    }
    /**
     * get Logged in user ID
     * @author keeper
     * @return int User_ID
     */
    public static function getID()
    {
        return self::getInstance()->_getID();
    }

    /**
     * get Logged in user object
     * @author keeper
     * @return User
     */
    public function _getUser()
    {
        $this->selftest();
        return $this->objectUser;
    }
    /**
     * get Logged in user object
     * @author keeper
     * @return User
     */
    public static function getUser()
    {
        return self::getInstance()->_getUser();
    }

    /**
     * get Logged in user RoleID
     * @author keeper
     * @return int RoleID
     */
    public function _getRoleID()
    {
        $this->selftest();
        return $this->objectUser->RoleID;
    }
    /**
     * get Logged in user RoleID
     * @author keeper
     * @return int RoleID
     */
    public static function getRoleID()
    {
        return self::getInstance()->_getRoleID();
    }

    /**
     * get Logged in user role object
     * @author keeper
     * @return User_Role
     */
    public function _getUserRole()
    {
        $this->selftest();
        return $this->objectUserRole;
    }
    /**
     * get Logged in user role object
     * @author keeper
     * @return User_Role
     */
    public static function getUserRole()
    {
        return self::getInstance()->_getUserRole();
    }


    /**
     * get Guest user ID
     * @author keeper
     * @return int User_ID
     */
    public static function getGuestID()
    {
        $GuestUserID = self::getInstance()->getConfig()->Guest->UserID;
        if (!$GuestUserID) {
            $GuestRoleID = self::getGuestRoleID();
            $tableUser = new User_Table();
            $objectUser = $tableUser->findByRole($GuestRoleID);
            $GuestUserID = is_object($objectUser) ? $objectUser->ID : 2;
        }
        return $GuestUserID;
    }

    /**
     * get Guest user Role ID
     * @author keeper
     * @return int User_ID
     */
    public static function getGuestRoleID()
    {
        $GuestRoleID = self::getInstance()->getConfig()->Guest->RoleID;
        if (!$GuestRoleID) {
            $tableUserRole = new User_Role_Table();
            $objectUserRole = $tableUserRole->fetchRow( $tableUserRole->select()->where('Name = ?', 'Guest') );
            $GuestRoleID = is_object($objectUserRole) ? $objectUserRole->ID : 4;
        }
        return $GuestRoleID;
    }


    /**
     * get Admin user ID
     * @author keeper
     * @return int User_ID
     */
    public static function getAdminID()
    {
        $AdminUserID = self::getInstance()->getConfig()->Admin->UserID;
        if (!$AdminUserID) {
            $AdminRoleID = self::getAdminRoleID();
            $tableUser = new User_Table();
            $objectUser = $tableUser->findByRole($AdminRoleID);
            $AdminUserID = is_object($objectUser) ? $objectUser->ID : 1;
        }
        return $AdminUserID;
    }

    /**
     * get Admin user Role ID
     * @author keeper
     * @return int User_ID
     */
    public static function getAdminRoleID()
    {
        $AdminRoleID = self::getInstance()->getConfig()->Admin->RoleID;
        if (!$AdminRoleID) {
            $tableUserRole = new User_Role_Table();
            $objectUserRole = $tableUserRole->fetchRow( $tableUserRole->select()->where('Name = ?', 'Admin') );
            $AdminRoleID = is_object($objectUserRole) ? $objectUserRole->ID : 1;
        }
        return $AdminRoleID;
    }




    /**
    * get link to System_User exemplar
    * @author keeper
    * @return System_User
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}