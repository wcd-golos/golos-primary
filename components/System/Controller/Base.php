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
abstract class System_Controller_Base extends Zend_Controller_Action
{
    /**
     * FlashMessenger
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;

	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_ComponentName = null;

    /**
    * Documentation
    * @author
    * @return mixed
    */
    function indexAction()
    {

    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function init()
    {
        if ($this->getRequest()->isXmlHttpRequest()){
            $this->_helper->layout->disableLayout();
        }
        // init base parameters
        $this->_ComponentName = $this->getComponentName();
        // init view
    	$this->initView();

    	// assign user_id and user into view
    	$this->view->User_ID = System_User::getID();
        $this->view->User = System_User::getUser()->toArray();
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    protected function getComponentName()
    {
    	$arrayParts = explode('_', get_class($this));
    	if (count($arrayParts) < 2) { // mean the component - is default
    	    return System_Components::convertNameToFolder(System_Components::getInstance()->getDefaultComponent());
    	}
        return reset($arrayParts);
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    protected function getClassName()
    {
        $controllerName = $this->getRequest()->getControllerName();
        $controllerPieces = explode('-', $controllerName);
        if (count($controllerPieces)){
        	foreach ($controllerPieces as $key => $piece){
        		$controllerPieces[$key] = ucfirst($piece);
        	}
        	$controllerName = implode('_', $controllerPieces);
        }else{
        	$controllerName = ucfirst($controllerName);
        }

        return $this->_ComponentName . ($controllerName != 'Index' ? '_' . $controllerName : '');
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function testAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    	$this->addOkMessage('Component ' .  $this->getClassName() . ' tested successfully<br />');
    }

    /**
    * Функция для добавления в компоненту Site_Map всех страниц компоненты-наследника.
    * Для переопределения программистами.
    * @author norbis
    * @return void
    */
    public function addsitemapAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    }

    public function addSitemapItem($strRoute = 'default', $arrParams = array(), $strFreq = 'weekly', $strPriority = '0.8')
    {
        $reset = true;
	    $encode = true;
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $strURL = 'http://' . $_SERVER['HTTP_HOST'] . $router->assemble($arrParams, $strRoute, $reset, $encode);
        $tableSiteMap = new Site_Map_Table();
        $objectSitemap = $tableSiteMap->fetchRow($tableSiteMap->select()->where('URL = ?', $strURL));
        if (!is_object($objectSitemap)){
            $objectSitemap = $tableSiteMap->fetchNew();
        }
        $objectSitemap->URL = $strURL;
        $objectSitemap->Freq = $strFreq;
        $objectSitemap->Priority = $strPriority;
        $objectSitemap->save();
    }

    /**
    * Функция установки для компоненты-наследника.
    * Для переопределения программистами.
    * @author norbis
    * @return void
    */
    public function installAction()
    {
    	$this->_helper->viewRenderer->setNoRender(true);
    }

    /**
    * Add global error message
    * @author vladimir
    * @param string|array $message
    */
    protected function addErrorMessage($message)
    {
        if (!$message) return;
        $Container = new Zend_Session_Namespace('pageError');
        $messages = $Container->Messages;
        if (!is_array($messages)) $messages = array();

        if (is_array($message)) {
            foreach ($message as $str) if (strlen($str)) $messages[] = $str;
        } else {
            $messages[] = $message;
        }

        $Container->Messages = $messages;
    }

    /**
    * Get global error messages
    * @author vladimir
    * @return array
    */
    protected function getErrorMessages($clean=false)
    {
        $Container = new Zend_Session_Namespace('pageError');
        $Messages = $Container->Messages;
        if ($clean) {
            $Container->unsetAll();
        }
        return $Messages;
    }
    /**
    * Check is error messages exists
    * @author vladimir
    * @return boolean
    */
    protected function isErrorMessages()
    {
        return count($this->getErrorMessages()) ? true : false;
    }
    /**
    * Add global OK message
    * @author vladimir
    * @param string|array $message
    */
    protected function addOkMessage($message)
    {
        if (!$message) return;
        $Container = new Zend_Session_Namespace('pageOk');
        $messages = $Container->Messages;
        if (!is_array($messages)) $messages = array();

        if (is_array($message)) {
            foreach ($message as $str) if (strlen($str)) $messages[] = $str;
        } else {
            $messages[] = $message;
        }

        $Container->Messages = $messages;
    }
    /**
    * get all global OK messages
    * @author vladimir
    * @return array
    */
    protected function getOkMessages($clean=false)
    {
        $Container = new Zend_Session_Namespace('pageOk');
        $Messages = $Container->Messages;
        if ($clean) {
            $Container->unsetAll();
        }
        return $Messages;
    }
    /**
    * Check is OK messages exists
    * @author vladimir
    * @return boolean
    */
    protected function isOkMessages()
    {
        return count($this->getOkMessages()) ? true : false;
    }


    /**
     * return the config object for current component, exception on error
     * @author keeper
     * @return Zend_Config_Ini
     */
    protected function getConfig()
    {
        return System_Components::getComponentConfig($this->getComponentName());
    }

    /**
     * call other action with current request object and place result to current responce object
     * @author keeper
     * @param string $action
     * @param string|null $controller
     * @param string|null $module
     * @param array|null $params
     * @return null
     */
    function shift($action, $controller=null, $module=null, $params=null)
    {
        if (!$controller) $controller = $this->getRequest()->getParam('controller');
        if (!$module) $module = $this->getRequest()->getParam('module');

        if (is_array($params) && count($params)) {
            $this->getRequest()->setParams($params);
        }

        // clone the view object to prevent over-writing of view variables
        $viewRendererObj = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        Zend_Controller_Action_HelperBroker::addHelper(clone $viewRendererObj);

        $request = clone $this->getRequest();
        $request->setModuleName($module);
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setDispatched(true);

        $response = Zend_Controller_Front::getInstance()->getResponse();
        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();

        $dispatcher->dispatch($request, $response);

        // reset the viewRenderer object to it's original state
        Zend_Controller_Action_HelperBroker::addHelper($viewRendererObj);

        $this->_helper->viewRenderer->setNoRender(true);
        return;
    }

    /**
     * Returns the Zend_Session_Namespace object based on current request
     * @return Zend_Session_Namespace
     */
    function getFilter($paramNameSpace=null)
    {
        if (!$paramNameSpace) {
            $paramNameSpace =   'filter_' .
                                $this->getRequest()->getModuleName() . '_' .
                                $this->getRequest()->getControllerName() . '_' .
                                $this->getRequest()->getActionName() . '_' .
                                $this->getRequest()->getParam('template');
            $paramNameSpace = trim($paramNameSpace, '_');
            //echo $paramNameSpace.'<br/>';
        }

        if ($this->getRequest()->getParam('nosession')) {
            $Filter = new Zend_Session_Namespace('empty_filter');
            $Filter->unsetAll();
        } else {
            $Filter = new Zend_Session_Namespace($paramNameSpace);
            if ($this->getRequest()->getParam('clearsession')) {
                $this->getRequest()->setParam('clearsession', 0);
                $Filter->unsetAll();
            }
        }

        return $Filter;
    }
}