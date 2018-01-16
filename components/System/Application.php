<?php
/**
 * Documentation
 * @author
 * @category
 * @todo Написание документации.
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Application extends System
{
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
     * Return Debug Mode status defined in config
     *
     * @return int
     */
	public function getDebugMode()
	{
		return (int)$this->_config->ModeDebug;
	}


    /**
     * Return default site section name
     *
     * @return string
     */
    public function getDefaultSection()
    {
        return $this->_config->DefaultSection;
    }

    /**
     * Return site sections array
     *
     * @return array
     */
	public function getSiteSections()
	{
		return $this->_config->SiteSections->toArray();
	}

    /**
     * Return section name which will be used by default for render view scripts
     *
     * @return array
     */
	public function getBaseThemeSection()
	{
		return $this->_config->BaseThemeSection;
	}
	
    /**
     * Return section name 
     *
     * @return string
     */
	public function getAdminThemeSection()
	{
		return $this->_config->AdminThemeSection;
	}

    /**
     * Return default web protocol
     *
     * @return array
     */
    public function getProtocol()
    {
        if (isset($this->_config->Protocol)) {
            $paramProtocol = strtolower($this->_config->Protocol);
            if (in_array($paramProtocol, array('http','https'))) {
                return $paramProtocol;
            }
        }
        return 'http';
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
	protected function _init()
	{

	    try {
			System_Components::getInstance()->init();
			System_Cache::getInstance()->init();
			System_Session::getInstance()->init();
			System_Locale::getInstance()->init();
            System_Translate::getInstance()->init();
            System_Router::getInstance()->init();
			System_Database::getInstance()->init();
			System_Acl::getInstance()->init();
			System_View::getInstance()->init();
			System_Controller::getInstance()->init();
			
			//System_Autoloader::getInstance()->init();
		} catch (Zend_Exception $exception) {

		    $vars = array('module', 'controller', 'action', 'User_ID');
		    $requestUrl = $_SERVER['REQUEST_URI'];
		    
			if (!(bool)$this->_config->throwExceptions) {
                $log = new Zend_Log(
                    new Zend_Log_Writer_Stream(
                        Zend_Registry::get('AppFolder') . '/logs/error.log'
                    )
                );
                $log->emerg('System Application Init Exception:'."\n".
                            $exception->getMessage() . "\n" .
                            'Url: '.$requestUrl . "\n" .
                            $exception->getTraceAsString());
                $response = new Zend_Controller_Response_Http();
                $response->setBody('HTTP/1.1 501 Server error. Exception in initializaing system engines');
                $response->sendResponse();
                exit();
		    } else {
    		    echo 'System Application Init Exception:';
    			echo '<pre>';
    		    echo "Caught exception: " . get_class($exception) . "\n";
    		    echo "Message: " . $exception->getMessage() . "\n";
    		    echo "Url: ".$requestUrl . "\n";
    		    echo  $exception->getTraceAsString() . "\n";
    		    echo '<pre>';
		    }
		}

	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function _run()
	{
		try {
			Zend_Layout::startMvc();
			System_Cache::getInstance()->run();
			System_Session::getInstance()->run();
			System_Database::getInstance()->run();
			System_Acl::getInstance()->run();
			System_Locale::getInstance()->run();
			System_Translate::getInstance()->run();
			System_View::getInstance()->run();
			System_Controller::getInstance()->run();
		} catch (Zend_Exception $exception) {
		    $vars = array('module', 'controller', 'action', 'User_ID');
		    $requestUrl = $_SERVER['REQUEST_URI'];
		    
		    if (!System_Controller::getInstance()->getConfig()->throwExceptions) {
		        $log = new Zend_Log(
                    new Zend_Log_Writer_Stream(
                        Zend_Registry::get('AppFolder') . '/logs/error.log'
                    )
                );
                $log->emerg('System Application Run Exception:' . "\n" .
                            $exception->getMessage() . "\n" .
                            'Url: '.$requestUrl . "\n" .
                            $exception->getTraceAsString());
                $response = new Zend_Controller_Response_Http();
                $response->setBody('HTTP/1.1 501 Server error. Exception in runnig system engines');
                $response->sendResponse();
                exit();
		    } else {
    		    echo 'System Application Run Exception:';
    			echo '<pre>';
    		    echo "Caught exception: " . get_class($exception) . "\n";
    		    echo "Message: " . $exception->getMessage() . "\n";
    		    echo "Url: ".$requestUrl . "\n";
    		    echo  $exception->getTraceAsString() . "\n";
    		    echo '<pre>';
		    }
		}
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function runCron($action, $controller, $module)
    {
        try {
            Zend_Layout::startMvc();
            System_Cache::getInstance()->run();
            //System_Session::getInstance()->run();
            System_Database::getInstance()->run();
            System_Acl::getInstance()->run();
            System_Locale::getInstance()->run();
            System_Translate::getInstance()->run();
            System_View::getInstance()->run();

            //$front = Zend_Controller_Front::getInstance();
            $front = System_Controller::getController();

            $objectRequest = new Zend_Controller_Request_Http('http://localhost/'.trim(Zend_Registry::get('baseUrl'), '/').'/'.$this->getDefaultSection().'/'.$module.'/'.$controller.'/'.$action);
            $objectResponse = new Zend_Controller_Response_Cli();

            $front->setRequest($objectRequest);

            $front->dispatch($objectRequest, $objectResponse);
            $objectResponse = $front->getResponse();
            $objectResponse->sendResponse();

        } catch (Zend_Exception $exception) {
            //if (!System_Controller::getInstance()->getConfig()->throwExceptions) {
                $log = new Zend_Log(
                    new Zend_Log_Writer_Stream(
                        Zend_Registry::get('AppFolder') . '/logs/cron.log'
                    )
                );
                $log->emerg($exception->getMessage() . "\n" .
                            $exception->getTraceAsString());
                //$response = new Zend_Controller_Response_Http();
                //$response->setRawHeader('HTTP/1.1 501 Server error');
                //$response->setBody('HTTP/1.1 501 Server error. Exception in runnig system engines');
                //$response->sendResponse();
                echo 'System Application Run Exception:';
                echo '<pre>';
                echo "Caught exception: " . get_class($exception) . "\n";
                echo "Message: " . $exception->getMessage() . "\n";
                echo  $exception->getTraceAsString() . "\n";
                echo '<pre>';
            /*} else {
                echo 'System Application Run Exception:';
                echo '<pre>';
                echo "Caught exception: " . get_class($exception) . "\n";
                echo "Message: " . $exception->getMessage() . "\n";
                echo  $exception->getTraceAsString() . "\n";
                echo '<pre>';
            }*/
        }
    }

    /**
     * Return System_Application instance
     *
     * @return System_Application
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}