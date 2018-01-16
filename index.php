<?php
#Magic quotes for incoming GET/POST/Cookie data.
ini_set('magic_quotes_gpc', 0);
#Magic quotes for runtime-generated data, e.g. data from SQL, from exec(), etc.
ini_set('magic_quotes_runtime', 0);

$AppFolder = dirname(__FILE__);
$ComponentsPath = array($AppFolder.'/components');
$BaseUrl = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
set_include_path(implode(PATH_SEPARATOR, $ComponentsPath));
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('System');
$autoloader->setFallbackAutoloader(true);
$autoloaderSystem = new System_Autoloader();
$autoloader->pushAutoloader($autoloaderSystem, 'System');
$autoloader->pushAutoloader($autoloaderSystem, 'User');
$autoloader->pushAutoloader($autoloaderSystem, 'Market');

Zend_Registry::set('AppFolder', $AppFolder);
Zend_Registry::set('ComponentsPath', $ComponentsPath);
Zend_Registry::set('baseUrl', $BaseUrl);
define('HTTPS', 0);
//Debug Mode
if (System_Application::getInstance()->getDebugMode()){
	System_Profiler::enable();
}

System_Application::getInstance()->init();
System_Application::getInstance()->run();