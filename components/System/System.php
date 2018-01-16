<?php
abstract class System
{
    /**
     * Config file name
     */
    protected $_filenameConfig = '_config.ini';

    /**
     * Relative path (from AppFolder) in file system
     */
    protected $_folderConfig = '/configs';

	/**
     * Флаг доступности. Устанавливаеться через _config.ini
     *
     * @var bool
     */
    protected $_enabled = null;

	/**
     * Базовый URL (baseURL)
     *
     * @var string
     */
    protected $_BaseUrl;

    /**
     * Инстанс Zend_Config_Ini считанный из файла
     *
     * @var Zend_Config_Ini
     */
	protected $_config  = null;

    /**
     * Конструктор объекта. При создании экземпляра объекта загружаеться конфиг.
     *
     * @return mixed
     */
	public function __construct()
	{
		  $this->loadConfig();
	}

    /**
     * Абстрактная функция "Запуск" для переопределения в потомках.
     *
     * @return mixed
     */
	abstract protected function _run();

    /**
     * Функция "Инициализация" для переопределения в потомках
     * Documentation
     *
     * @return void
     */
	public function init()
	{

		if ($this->_enabled) {
			$this->_init();
		}

	}

    /**
     * Функция старта. Проверка доступности.
     *
     * @return void
     */
	public function run()
	{
		if ($this->_enabled) {
			$this->_run();
		}
	}

    /**
     * Функция пре-деспечеризации. Проверка доступности.
     * @author keeper
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($this->_enabled) {
            $this->_preDispatch($request);
        }
    }
    
    protected function _preLoad()
    {
    	$autoloader = Zend_Loader_Autoloader::getInstance();
    	$autoloader->setFallbackAutoloader(true);
    	
    	$systemloader = System_Autoloader::getInstance();
    	
    	$autoloader->pushAutoloader($systemloader, 'System');
    	$autoloader->pushAutoloader($systemloader, 'User');
    	$autoloader->pushAutoloader($systemloader, 'Article');
    	$autoloader->pushAutoloader($systemloader, 'Settings');
    	$autoloader->pushAutoloader($systemloader, 'LitePay');
    	$autoloader->pushAutoloader($systemloader, 'LiteMail');
    	$autoloader->pushAutoloader($systemloader, 'Location');
    	$autoloader->pushAutoloader($systemloader, 'LogSystem');
    	$autoloader->pushAutoloader($systemloader, 'File');
    	$autoloader->pushAutoloader($systemloader, 'Image');
    	$autoloader->pushAutoloader($systemloader, 'Aaa');
    	$autoloader->pushAutoloader($systemloader, 'Video');
    	$autoloader->pushAutoloader($systemloader, 'Property');
    	$autoloader->pushAutoloader($systemloader, 'Notification');
    	$autoloader->pushAutoloader($systemloader, 'Seo');
    	$autoloader->pushAutoloader($systemloader, 'Pagecreator');
    	$autoloader->pushAutoloader($systemloader, 'Advertiser');
    	$autoloader->pushAutoloader($systemloader, 'Import');
    	$autoloader->pushAutoloader($systemloader, 'Page');
    	$autoloader->pushAutoloader($systemloader, 'Coupon');
    	$autoloader->pushAutoloader($systemloader, 'Statistics');
    	$autoloader->pushAutoloader($systemloader, 'Broker');
    }

    /**
     * Получает имя папки для конфига.
     *
     * @return string
     */
	protected function getConfigDirectory()
	{
	    $ConfigDirectory = Zend_Registry::get('AppFolder') . $this->_folderConfig;
		$ConfigDirectory .= '/';
		$arrayParts = explode('_', get_class($this));
		$ConfigDirectory .= reset($arrayParts);
		return 	$ConfigDirectory;
	}

    /**
     * Возвращает созданный объект конфига (protected)
     * @author norbis
     * @return Zend_Config_Ini
     */
	public function getConfig()
	{
		return $this->_config;
	}

    /**
     * Получает, устаналивает и возвращает BaseUrl
     *
     * @return string
     */
	protected function getBaseUrl()
	{
	    if (!$this->_BaseUrl){
	        $this->_BaseUrl = Zend_Registry::get('baseUrl');
	    }
	    return $this->_BaseUrl;
	}

    /**
     * Загрузка файла конфига для наследков класса System. Для каждого класса используеться подсекция [Class_Name].
     * Проверяет на существование след. файлы: HTTP_HOST + Base_Url + Config_Name, HTTP_HOST + Config_Name, Config_Name
     * @author norbis
     * @return mixed
     */
	public function loadConfig()
	{
        $serverHTTPHost = '';
	    if (isset($_SERVER['HTTP_HOST'])){
            $serverHTTPHost = $_SERVER['HTTP_HOST'];
        }

        $baseUrl = str_replace('/', '_',  $this->getBaseUrl());
	    $filenameConfig = $this->getConfigDirectory() . '/' . $serverHTTPHost . $baseUrl . $this->_filenameConfig;
	    //var_dump($filenameConfig);
	    if (!file_exists($filenameConfig)){
            $filenameConfig = $this->getConfigDirectory() . '/' . $serverHTTPHost . $this->_filenameConfig;
            //var_dump($filenameConfig);
    	    if (!file_exists($filenameConfig)){
                $filenameConfig = $this->getConfigDirectory() . '/'  . $this->_filenameConfig;
    	        if (!file_exists($filenameConfig)){
                    throw new Zend_Exception('File config with filename ' . $filenameConfig . ' not found in the file system');
                }
            }
        }
	    $this->_config = new Zend_Config_Ini($filenameConfig, array(get_class($this)));

		$this->_enabled = $this->_config->get('enabled', true);
	}

    /**
     * Возвращает флаг доступности
     * @author keeper
     * @return boolean
     */
    public function enabled()
    {
        return $this->_enabled;
    }
}