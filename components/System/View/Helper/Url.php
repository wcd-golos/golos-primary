<?php
/**
 * Попытка исправить стандартный Zend_View_Helper_Url
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class System_View_Helper_Url extends Zend_View_Helper_Abstract
{
    /**
     * Generates an url given the name of a route.
     *
     * @access public
     *
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
//        $url = '';
//        $request = Zend_Controller_Front::getInstance()->getRequest();
//        $url .= Zend_Controller_Front::getInstance()->getBaseUrl();
//
//        $section    = $request->getParam('section');
//        $module     = $request->getParam('module');
//        $controller = $request->getParam('controller');
//        $action     = $request->getParam('action');
//        if (isset($urlOptions['section'])){
//        	$section = $urlOptions['section'];
//        	unset($urlOptions['section']);
//        }
//        if (isset($urlOptions['module'])){
//        	$module = $urlOptions['module'];
//        	unset($urlOptions['module']);
//        }
//        if (isset($urlOptions['controller'])){
//        	$controller = $urlOptions['controller'];
//        	unset($urlOptions['controller']);
//        }
//        if (isset($urlOptions['action'])){
//        	$action = $urlOptions['action'];
//        	unset($urlOptions['action']);
//        }
//
//        if ($section != 'frontend'){
//        	$url .= '/' . $section;
//        }
//
//        if ($module != 'default'){
//        	$url .= '/' . $module;
//        }
//        if ($controller != 'index'){
//        	$url .= '/' . $controller;
//        }
//        if ($action != 'index'){
//        	$url .= '/' . $action;
//        }
//
//        foreach ($urlOptions as $paramName => $paramValue){
//        	$url .= '/' . $paramName . '/' . $paramValue;
//        }
//        return $url;

        // Keeper fix (2010-02-08)
        /*if (!isset($urlOptions['section'])) {
            $request = Zend_Controller_Front::getInstance()->getRequest();
            $section = $request->getParam('section');
            if ($section) {
                $urlOptions['section'] = $section;
            }
        }*/

        if (System_Translate::getInstance()->enabled() && strpos($name, 'language')!==false) {
            if (!isset($urlOptions['language'])) {
                $urlOptions['language'] = System_Locale::getLanguage();
            }
        }

    	$router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }
}
