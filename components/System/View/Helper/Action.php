<?php
/**
 * Попытка исправить стандарный Zend_View_Helper_Action
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class System_View_Helper_Action extends Zend_View_Helper_Abstract
{
    /**
     * @var string
     */
    public $defaultModule;

    /**
     * @var Zend_Controller_Dispatcher_Interface
     */
    public $dispatcher;

    /**
     * @var Zend_Controller_Request_Abstract
     */
    public $request;

    /**
     * @var Zend_Controller_Response_Abstract
     */
    public $response;

    public $section;
    public $language;
    public $UserID;
    /**
     * Constructor
     *
     * Grab local copies of various MVC objects
     *
     * @return void
     */
    public function __construct()
    {
        $front   = Zend_Controller_Front::getInstance();
        $modules = $front->getControllerDirectory();
        if (empty($modules)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('Action helper depends on valid front controller instance');
        }

        $request  = $front->getRequest();
        $response = $front->getResponse();

        if (empty($request) || empty($response)) {
            require_once 'Zend/View/Exception.php';
            throw new Zend_View_Exception('Action view helper requires both a registered request and response object in the front controller instance');
        }

        $this->request       = clone $request;
        $this->response      = clone $response;
        $this->dispatcher    = clone $front->getDispatcher();
        $this->defaultModule = $front->getDefaultModule();
        $this->section = $this->request->getParam('section');
        $this->language = $this->request->getParam('language');
        $this->UserID = $this->request->getParam('User_ID', $this->request->getParam('userid'));

    }

    /**
     * Reset object states
     *
     * @return void
     */
    public function resetObjects()
    {
        $params = $this->request->getUserParams();
        foreach (array_keys($params) as $key) {
            $this->request->setParam($key, null);
        }

        $this->response->clearBody();
        $this->response->clearHeaders()
                       ->clearRawHeaders();
    }

    /**
     * Retrieve rendered contents of a controller action
     *
     * If the action results in a forward or redirect, returns empty string.
     *
     * @param  string $action
     * @param  string $controller
     * @param  string $module Defaults to default module
     * @param  array $params
     * @param  boolean $clearParamSources If true, set null for all parameters in request except in $params
     * @return string
     */
    public function action($action, $controller, $module = null, array $params = array(), $clearParamSources = true)
    {
    	$this->resetObjects();
        if (null === $module) {
            $module = $this->defaultModule;
        }
		$this->request = new Zend_Controller_Request_Http();
        // clone the view object to prevent over-writing of view variables
        $viewRendererObj = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::addHelper(clone $viewRendererObj);

        if ($clearParamSources) {
            $this->request->setParamSources(array());
        }

        $this->request->setParam('section', $this->section);
        $this->request->setParam('language', $this->language);
        $this->request->setParam('User_ID', $this->UserID);
        $this->request->setParam('userid', $this->UserID);
        $this->request->setParams($params)
                      ->setModuleName($module)
                      ->setControllerName($controller)
                      ->setActionName($action)
                      ->setDispatched(true);

        $this->dispatcher->dispatch($this->request, $this->response);

        // reset the viewRenderer object to it's original state
        Zend_Controller_Action_HelperBroker::addHelper($viewRendererObj);

        if (!$this->request->isDispatched()
            || $this->response->isRedirect())
        {
            // forwards and redirects render nothing
            return '';
        }

        $return = $this->response->getBody();
        $this->resetObjects();
        return $return;
    }

    /**
     * Clone the current View
     *
     * @return Zend_View_Interface
     */
    public function cloneView()
    {
        $view = clone $this->view;
        $view->clearVars();
        return $view;
    }
}
