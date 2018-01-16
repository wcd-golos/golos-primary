<?php
/**
 * Documentation
 * @author
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Wecandevelopit Inc. (http://www.wecandevelopit.com)
 * @license
 */
class System_Controller_Helper_Debug extends Zend_Controller_Action_Helper_Abstract
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
     * Hook into action controller preDispatch() workflow
     *
     * @return void
     */
    public function preDispatch()
    {
    	$name = $this->getRequest()->getModuleName().'_'.$this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName();
    	System_Profiler::start($name);
    }

    /**
     * Hook into action controller postDispatch() workflow
     *
     * @return void
     */
    public function postDispatch()
    {
        $name = $this->getRequest()->getModuleName().'_'.$this->getRequest()->getControllerName().'_'.$this->getRequest()->getActionName();
        System_Profiler::stop($name);
    }
}
