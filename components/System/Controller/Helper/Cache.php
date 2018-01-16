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
class System_Controller_Helper_Cache extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Hook into action controller initialization
     *
     * @return void
     */
    public function init()
    {
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    protected function getCacheName()
    {
        $request = $this->getRequest();
        $stringCacheName = '';
        $stringCacheName .= $request->getModuleName();
        $stringCacheName .= '_' . $request->getControllerName();
        $stringCacheName .= '_' . $request->getActionName();
        $arrayParamsAll = $request->getParams();
        foreach ($arrayParamsAll as $stringParamName => $stringParamValue){
            if ($stringParamName == 'module' || $stringParamName == 'controller' || $stringParamName == 'action'){
                continue;
            }
            $stringCacheName .= '_' . $stringParamName . '_' . $stringParamValue;
        }
        $stringCacheName = str_replace('-', '', $stringCacheName);
        return $stringCacheName;
    }
    
    /**
     * Hook into action controller preDispatch() workflow
     *
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	$writer = new Zend_Log_Writer_Stream('C:/www/log.txt');
		$logger = new Zend_Log($writer);
		$logger->info('preDispatch:'. $this->getCacheName());
//        $systemCache = System_Cache::getCache();
//        $stringResponseBody = $systemCache->load($this->getCacheName());
//
//        if ($stringResponseBody){
//	        $this->getResponse()->appendBody('cached:');
//	        $this->getResponse()->appendBody($stringResponseBody);
//	        $this->getRequest()->setDispatched(false);
//        }
    }

    /**
     * Hook into action controller postDispatch() workflow
     *
     * @return void
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
    	
    	$writer = new Zend_Log_Writer_Stream('C:/www/log.txt');
		$logger = new Zend_Log($writer);
		$logger->info('postDispatch:'. $this->getCacheName());
//
//		$this->getRequest()->setDispatched(true);
//		$systemCache = System_Cache::getCache();
//		$systemCache->save($this->getResponse()->getBody(), $this->getCacheName());
//
//    	$stringResponseBody = $systemCache->load($this->getCacheName());
//        if (!$stringResponseBody){
//        	$systemCache->save($this->getResponse()->getBody(), $this->getCacheName());
//        }
//        $this->getResponse()->appendBody(':PostDispatch:' . $this->getRequest()->getControllerName());
    }
}
