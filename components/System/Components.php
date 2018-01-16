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
class System_Components extends System
{
	/**
     * Documentation
     *
     * @var mixed
     */
    protected static $_instance;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_arrayComponents;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_arrayControllers;

    /**
    * Documentation
    * @author
    * @return mixed
    */
	function __construct()
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
    	$this->_arrayComponents = array();
    	$this->_arrayControllers['index'] = 'index';
    	
    	$configArrayComponents = $this->_config->Components->toArray();
    	
        foreach ($configArrayComponents as $configComponent) {
            if (isset($this->_arrayComponents[$configComponent['Name']])) {
            	throw new Zend_Exception('Component with name ' . $configComponent['Name'] . ' already exist in config');
            }
            $this->_arrayComponents[$configComponent['Name']] = $configComponent['Name'];
            
            if (!isset($configComponent['Controllers'])) {
            	continue;
            }
            
			foreach ($configComponent['Controllers'] as $configController) {
			    if (isset($this->_arrayControllers[$configController])) {
			        throw new Zend_Exception('Controller with name ' . $configController . ' already exist in config');
			    }
			    $this->_arrayControllers[$configController] = $configController;
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
        
    }

    /**
    * Documentation
    * @author norbis
    * @return mixed
    */
	public function getComponents()
	{
		return $this->_arrayComponents;
	}

    /**
    * Documentation
    * @author norbis
    * @return mixed
    */
	public function getControllers()
	{
		return $this->_arrayControllers;
	}
	
    /**
     * Return default component name
     * @author keeper
     * @return string
     */
    public function getDefaultComponent()
    {
        if (isset($this->_config->DefaultComponent)) {
            return $this->_config->DefaultComponent;
        }
        return 'Homepage';
    }
	
    /**
     * convert component name to component folder name
     * @author keeper
     * @param string $ComponentName
     * @return string Component folder
     */
	public static function convertNameToFolder($ComponentName)
	{
	    $ComponentFolder = str_replace('-', ' ', $ComponentName);
	    $ComponentFolder = ucwords(strtolower($ComponentFolder));
	    $ComponentFolder = str_replace(' ', '', $ComponentFolder);
	    return trim($ComponentFolder);
	}
    
    /**
     * convert component folder to component name
     * @author keeper
     * @param string $ComponentFolder
     * @return string Component name
     */
    public static function convertFolderToName($ComponentFolder)
    {
        
    }
	
    /**
    * Call the _getComponentConfig in current exemplar of _getComponentConfig
    * @author keeper
    * @return 
    */
    public static function getComponentConfig($paramComponentName)
    {
        return self::getInstance()->_getComponentConfig($paramComponentName);
    }
	
    /**
     * return the config object for asked component, exception on error
     * @author keeper
     * @return Zend_Config_Ini
     */
    public function _getComponentConfig($paramComponentName)
    {
        /*print_r($this->getComponents());
        exit();*/
        if (!$paramComponentName/* || !in_array($paramComponentName, $this->getComponents())*/) {
            throw new Zend_Exception('Can\'t load config, component name is not valid or component is not enailbled for project');
        }
        if (!Zend_Registry::isRegistered('_config_'.$paramComponentName)) {
            $this->_loadComponentConfig($paramComponentName);
        }
        $config = Zend_Registry::get('_config_'.$paramComponentName);
        if (is_null($config)) {
            throw new Zend_Exception('Can\'t load config for component "'.$paramComponentName.'"');
        }
        return $config;
    }
    
    /**
     * Load config for current component.
     * Search for next files: HTTP_HOST + Base_Url + Config_Name, HTTP_HOST + Config_Name, Config_Name
     * @author norbis, keeper
     * @return mixed
     */
    private function _loadComponentConfig($paramComponentName)
    {
        $serverHTTPHost = '';
        if (isset($_SERVER['HTTP_HOST'])) {
            $serverHTTPHost = $_SERVER['HTTP_HOST'];
        }
        $baseUrl = str_replace('/', '_', Zend_Registry::get('baseUrl'));
        $ConfigDirectory = Zend_Registry::get('AppFolder') . $this->_folderConfig . '/' . $paramComponentName;
        
        $filenameConfig = $ConfigDirectory . '/' . $serverHTTPHost . $baseUrl . $this->_filenameConfig;
        //print_r($filenameConfig . '<br/>');
        if (!file_exists($filenameConfig)) {
            $filenameConfig = $ConfigDirectory . '/' . $serverHTTPHost . $this->_filenameConfig;
            //print_r($filenameConfig . '<br/>');
            if (!file_exists($filenameConfig)) {
                $filenameConfig = $ConfigDirectory . '/'  . $this->_filenameConfig;
                //print_r($filenameConfig . '<br/>');
                if (!file_exists($filenameConfig)) {
                    $arrComponentsPaths = Zend_Registry::get('ComponentsPath');
                    if (is_string($arrComponentsPaths)) $arrComponentsPaths = array($arrComponentsPaths);
                    foreach ($arrComponentsPaths as $ComponentsPath) {
                        $filenameConfig = $ComponentsPath . '/' . $paramComponentName . '/' . $this->_filenameConfig;
                        if (file_exists($filenameConfig)) break;
                    }
                    if (!file_exists($filenameConfig)) {
                        //print_r($filenameConfig . '<br/>');
                        //exit();
                        throw new Zend_Exception('Can\'t find config file for component "'.$paramComponentName.'"');
                    }
                }
            }
        }
        $config = new Zend_Config_Ini($filenameConfig);
        Zend_Registry::set('_config_'.$paramComponentName, $config);
    }
	
    /**
    * Return Cach_Core static instance
    *
    * @return System_Components
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
