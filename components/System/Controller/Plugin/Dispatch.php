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
class System_Controller_Plugin_Dispatch extends Zend_Controller_Plugin_Abstract
{
	/**
    * Documentation
    * @author
    * @return mixed
    */
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {

    }

	/**
    * Documentation
    * @author
    * @return mixed
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        //$request = $this->getRequest();
        System_Locale::getInstance()->preDispatch($request);
        System_Translate::getInstance()->preDispatch($request);
        System_View::getInstance()->preDispatch($request);
        System_User::getInstance()->preDispatch($request);
        System_Acl::getInstance()->preDispatch($request);
        $config = System_Controller::getInstance()->getConfig();
        if (isset($config->preDispatch)) foreach ($config->preDispatch as $class) {
            call_user_func($class .'::getInstance')->preDispatch($request);
        }
    }
}
