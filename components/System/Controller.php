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
class System_Controller extends System
{
	/**
     * Documentation
     *
     * @var mixed
     */
	protected static $_instance = null;

     /**
     * Zend_Controller_Front Instance
     *
     * @var Zend_Controller_Front
     */
	protected $_front = null;

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
    * @return Zend_Controller_Front
    */
    public function _getController()
    {
    	if ($this->_front === NULL){
    		throw new Zend_Exception('Method init should be called');
    	}
    	return $this->_front;
    }

    /**
    * Documentation
    * @author
    * @return Zend_Controller_Front
    */
    public static function getController()
    {
		return self::getInstance()->_getController();
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _init()
    {
        // load components paths (directories)
        $arrComponentsPaths = Zend_Registry::get('ComponentsPath');
        if (is_string($arrComponentsPaths)) $arrComponentsPaths = array($arrComponentsPaths);
        // check the defined components paths
        foreach ($arrComponentsPaths as $ComponentsPath) {
            if (!is_dir($ComponentsPath . '/')) {
                echo 'The component path "'.$ComponentsPath.'" defined in index.php is not exists';
                exit();
            }
        }

        $front = Zend_Controller_Front::getInstance();
    	$front->setRouter(System_Router::getInstance()->getRouter());

    	if ((bool)$this->_config->noViewRenderer){
    	    $front->setParam('noViewRenderer', TRUE);
    	}
    	if ((bool)$this->_config->useDefaultControllerAlways){
    	    $front->setParam('useDefaultControllerAlways', TRUE);
    	}
    	if ((bool)$this->_config->throwExceptions){
    	    $front->throwExceptions(TRUE);
    	}

		$front->setBaseUrl($this->getBaseUrl());

        $front->returnResponse(true);

        // register plugin ErrorHandler
    	$pluginErrorHandler = new Zend_Controller_Plugin_ErrorHandler();
		$pluginErrorHandler->setErrorHandler(array('module' => 'site', 'controller' => 'error', 'action' => 'error'));
        $front->registerPlugin($pluginErrorHandler);
        // register plugin Dispatch
        $front->registerPlugin(new System_Controller_Plugin_Dispatch());
        // register all other plugins defined in config
        if (isset($this->_config->plugins)) {
            foreach ($this->_config->plugins as $strPlugin) {
                $front->registerPlugin(new $strPlugin());
            }
        }

		$this->_front = $front;

        if (System_Application::getInstance()->getDebugMode()) {
            $front->registerPlugin(new System_Controller_Plugin_DevelopmentBar());
            Zend_Controller_Action_HelperBroker::addHelper(new System_Controller_Helper_Debug());
        }

        // init Context Switch
        $helperContextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
        $contexts = array('table'   => array('suffix' => 'table'),
                          'options' => array('suffix' => 'options'),
                          'ul'      => array('suffix' => 'ul'),
        				  'tab'		=> array('suffix' => 'tab'),
        				  'xls'		=> array('suffix' => 'xls'),
        				  'pdf'		=> array('suffix' => 'pdf')

        );
        $helperContextSwitch->addContexts($contexts);
        $helperContextSwitch->setAutoDisableLayout(false);
        $helperContextSwitch->setAutoJsonSerialization(false);

        // prepare array with components directories
        $arrControllersDirectories = array();
        foreach (System_Components::getInstance()->getComponents() as $nameComponent){
            $pathComponent = str_replace('-', ' ', $nameComponent);
            $pathComponent = ucwords($pathComponent);
            $pathComponent = str_replace(' ', '', $pathComponent);
            foreach ($arrComponentsPaths as $ComponentsPath) {
                $pathControllersDirectory = $ComponentsPath .DIRECTORY_SEPARATOR. $pathComponent .DIRECTORY_SEPARATOR. $this->_config->DirectoryName;
                if (is_dir($pathControllersDirectory)) {
                    break;
                }
            }
            $arrControllersDirectories[$nameComponent] = $pathControllersDirectory;
        }
        // set default module
        foreach ($arrComponentsPaths as $ComponentsPath) {
            $pathControllersDirectory = $ComponentsPath .DIRECTORY_SEPARATOR.
                                        System_Components::convertNameToFolder(System_Components::getInstance()->getDefaultComponent()) .DIRECTORY_SEPARATOR.
                                        $this->_config->DirectoryName;
            //exit($pathControllersDirectory);
            if (is_dir($pathControllersDirectory)) {
                $arrControllersDirectories['default'] = $pathControllersDirectory;
                break;
            }
        }
		$this->_front->setControllerDirectory($arrControllersDirectories);
    }

    /**
    * Запуск диспетчеризации фронт контроллера. Получение и вывод ответа. Проверка на исключение в ответе.
    * @author norbis
    * @return void
    */
    public function _run()
    {
        $this->_front->dispatch();
		$response = $this->_front->getResponse();
		if ($response->isException()) {
//		    if (!(bool)$this->_config->throwExceptions)
//		    {   $exceptions       = $response->getException();
//                $exception        = $exceptions[0];
//                $log = new Zend_Log(
//                    new Zend_Log_Writer_Stream(
//                        Zend_Registry::get('AppFolder') . '/logs/error.log'
//                    )
//                );
//                $log->emerg($exception->getMessage() . "\n" .
//                            $exception->getTraceAsString());
//                $response->clearBody();
//                $response->setRawHeader('HTTP/1.1 501 System Error');
//                return;
//		    }
		}
		$response->sendResponse();
    }


    /**
    * Получает ссылку на статический объект System_Controller
    * @author norbis
    * @return System_Controller
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}