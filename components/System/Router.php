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
class System_Router extends System
{
	/**
     * Documentation
     *
     * @var System_Router
     */
	protected static $_instance;

	/**
     * Экземпляр класса Zend_Controller_Router_Rewrite
     *
     * @var Zend_Controller_Router_Rewrite
     */
	protected $_router;

    /**
    * Documentation
    * @author norbis
    * @return void
    */
	protected function _init()
	{
		$this->_router = new Zend_Controller_Router_Rewrite();
		$this->_router->addDefaultRoutes();
	    if (isset($this->_config->routes)){
            $this->_router->addConfig($this->_config, 'routes');
        }
        $arrRoutes = array_keys($this->_router->getRoutes());

		$regexpLangs = '(' . implode( '|', System_Locale::getInstance()->getLanguages() ) . ')';
		$regexpSections = '(' . implode('|', System_Application::getInstance()->getSiteSections()) . ')';

		$routeDefault = System_Router::getInstance()->getRouter()->getRoute('default');
		//$routeHome = new Zend_Controller_Router_Route_Static('', array('module' => 'default', 'controller' => 'index', 'action' => 'index'));

        $routeLanguage = new Zend_Controller_Router_Route(
            ':language',
            array('language' => System_Locale::getInstance()->getDefaultLang(),
                  'module' => 'default', 'controller' => 'index', 'action' => 'index'),
            array('language' => $regexpLangs)
        );

		$routeSection = new Zend_Controller_Router_Route(
            ':section',
            array('section' => System_Application::getInstance()->getDefaultSection(),
            	  'module' => 'default', 'controller' => 'index', 'action' => 'index'),
            array('section' => $regexpSections)
        );

        System_Router::getInstance()->getRouter()->addRoute('section-index', $routeSection);
        System_Router::getInstance()->getRouter()->addRoute('section-default', $routeSection->chain($routeDefault));
        foreach ($arrRoutes as $strRouteName){
            if ($strRouteName == 'default'){
                continue;
            }
            $objRoute = System_Router::getInstance()->getRouter()->getRoute($strRouteName);
            System_Router::getInstance()->getRouter()->addRoute('section-' . $strRouteName, $routeSection->chain($objRoute));
        }


        System_Router::getInstance()->getRouter()->addRoute('language-index', $routeLanguage);
        System_Router::getInstance()->getRouter()->addRoute('language-default', $routeLanguage->chain($routeDefault));
        foreach ($arrRoutes as $strRouteName){
            if ($strRouteName == 'default'){
                continue;
            }
            $objRoute = System_Router::getInstance()->getRouter()->getRoute($strRouteName);
            System_Router::getInstance()->getRouter()->addRoute('language-' . $strRouteName, $routeLanguage->chain($objRoute));
        }

        System_Router::getInstance()->getRouter()->addRoute('language-section-index', $routeLanguage->chain($routeSection));
        System_Router::getInstance()->getRouter()->addRoute('language-section-default', $routeLanguage->chain($routeSection)->chain($routeDefault));
        foreach ($arrRoutes as $strRouteName){
            if ($strRouteName == 'default'){
                continue;
            }
            $objRoute = System_Router::getInstance()->getRouter()->getRoute($strRouteName);
            System_Router::getInstance()->getRouter()->addRoute('language-section-' . $strRouteName, $routeLanguage->chain($routeSection)->chain($objRoute));
        }

//        System_Router::getInstance()->getRouter()->addRoute('section-index', $routeSection);


//		$routeLanguage = $routeLanguage->chain($routeSection)
//		                               ->chain($routeDefault);
//        System_Router::getInstance()->getRouter()->addRoute('default', $routeLanguage->chain($routeDefault));


//        System_Router::getInstance()->getRouter()->addRoute('language-default', $routeLanguage->chain($routeDefault));
//        System_Router::getInstance()->getRouter()->addRoute('section-default', $routeSection->chain($routeDefault));
//        System_Router::getInstance()->getRouter()->addRoute('language-section-index',  $routeLanguage->chain($routeSection));
//        System_Router::getInstance()->getRouter()->addRoute('language-section-default',  $routeLanguage->chain($routeSection)->chain($routeDefault));
//
//        foreach ($arrRoutes as $strRouteName){
//            if ($strRouteName == 'default'){
//                continue;
//            }
//            $objRoute = System_Router::getInstance()->getRouter()->getRoute($strRouteName);
//            System_Router::getInstance()->getRouter()->addRoute('language-' . $strRouteName, $routeLanguage->chain($objRoute));
//            System_Router::getInstance()->getRouter()->addRoute('section-' . $strRouteName, $routeSection->chain($objRoute));
//            System_Router::getInstance()->getRouter()->addRoute('language-section-' . $strRouteName,  $routeLanguage->chain($objRoute));
//        }
//        $routeArticle = System_Router::getInstance()->getRouter()->getRoute('language-section-articlese');
//        echo $routeArticle->assemble(array('SEID' => 'search-systems'));
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
	protected function _run()
	{

	}

    /**
    * Возвращает ссылку на статический обьект роутера
    * @author norbis
    * @return Zend_Controller_Router_Rewrite
    */
    public function getRouter()
    {
    	if ($this->_router === NULL){
    		throw new Zend_Exception('Methods init and run should be called');
    	}
    	return $this->_router;
    }

    /**
    * Documentation
    * @author norbis
    * @return System_Router
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

