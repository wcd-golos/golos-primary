<?php
class System_View extends System
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
     * Return Site Themes array
     *
     * @return array
     */
    public function getThemes()
    {
        return $this->_config->Themes->toArray();
    }
    
    /**
     * Return full hard drive path to the themes
     *
     * @return string
     */
    public function getPathThemes()
    {
        return Zend_Registry::get('AppFolder') . $this->_config->ThemesPath;
    }
    
    /**
     * Return relative hard drive path to the default theme
     *
     * @return array
     */
    public function getDefaultTheme()
    {
        return $this->_config->DefaultTheme;
    }
    
    /**
     * Return Site Themes array
     *
     * @return array
     */
    public function getFilters()
    {
        if (isset($this->_config->Filters)) return $this->_config->Filters->toArray();
        return array();
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _init()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->setViewScriptPathSpec(':module/:controller/:action.:suffix');
        $viewRenderer->setViewSuffix('tpl');
    }


    /**
    * 
    * @author keeper
    * @return void
    */
    public function _run()
    {
        
    }
    
    
    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $pathThemes             = $this->getPathThemes();
        $siteThemes             = $this->getThemes();
        $defaultTheme           = $this->getDefaultTheme();
        $defaultSection         = System_Application::getInstance()->getDefaultSection();
        $defaultThemeSection    = System_Application::getInstance()->getBaseThemeSection();
        
        $paramSection = $request->getParam('section', $defaultSection);
        
        $doctypeHelper = new Zend_View_Helper_Doctype();
        $doctypeHelper->doctype('XHTML1_STRICT');
        
        $helperViewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $helperViewRenderer->setViewScriptPathSpec(':module/:controller/:action.:suffix');
        $helperViewRenderer->setViewSuffix('tpl');
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        
        $objectView = new Zend_View(array('encoding' => 'UTF-8'));
        
        $objectView->setScriptPath($pathThemes . $defaultTheme . DIRECTORY_SEPARATOR . $defaultThemeSection);
        
        // setup helpers
        $objectView->addHelperPath('Zend/Dojo/View/Helper', 'Zend_Dojo_View_Helper');
        $objectView->addHelperPath('System/View/Helper/', 'System_View_Helper');
        
        // setup filters
        $objectView->addFilterPath('System/View/Filter/', 'System_View_Filter_');
        $arrFilters = $this->getFilters();
        foreach ($arrFilters as $strFilter) $objectView->addFilter($strFilter);
        
        foreach ($siteThemes as $siteTheme) {
            if ($siteTheme == $defaultTheme) {
                continue;
            }
            //echo $pathThemes . $siteTheme . DIRECTORY_SEPARATOR . $defaultThemeSection . '<br/>';
            $objectView->addScriptPath($pathThemes . $siteTheme . DIRECTORY_SEPARATOR . $defaultThemeSection);
        }

        if ($paramSection != $defaultThemeSection) {
            foreach ($siteThemes as $siteTheme) {
                //echo $pathThemes . $siteTheme . DIRECTORY_SEPARATOR . $paramSection . '<br/>';
                $objectView->addScriptPath($pathThemes . $siteTheme . DIRECTORY_SEPARATOR . $paramSection);
            }
        }
        $objectView->assign('BaseUrl', Zend_Controller_Front::getInstance()->getBaseUrl());
        $helperViewRenderer->setView($objectView);

        $helperLayout = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
        $helperLayout->setViewScriptPath($pathThemes . $defaultTheme . DIRECTORY_SEPARATOR . $paramSection);
        //echo $pathThemes . $defaultTheme . DIRECTORY_SEPARATOR . $paramSection . '<br/>';
    }
    
    

    /**
    * get link to System_View exemplar
    * @author keeper
    * @return System_View
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}