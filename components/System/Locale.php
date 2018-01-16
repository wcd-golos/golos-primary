<?php
/**
 * Системный класс, для установки и настройки мультиязычности
 * @author norbis, keeper
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2011 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Locale extends System
{

    /**
     * @var Zend_Locale
     */
    private $objectZendLocale = null;

	/**
     * Documentation
     *
     * @var System_Locale
     */
    protected static $_instance = null;

    /**
     * Has the Syste_Locale been preDispatched?
     * @var boolean
     */
    protected $_dispatched = false;

    /**
    * Функция инициализации
    * @author norbis
    * @return void
    */
    public function _init()
    {

    }

    /**
    * Функция запуска
    * @author
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
        $arrLanguages = $this->getLanguages();

        // search Language in request
        $paramLanguage = $request->getParam('language');
        if (!in_array($paramLanguage, $arrLanguages)) {
            $paramLanguage = null;
        }

        // search Language in session
        if (!$paramLanguage) {
            if ($this->getConfig()->SaveInSession) {
                if (System_Session::isSetParam('System_Locale_Language')) {
                    $paramLanguage = System_Session::getParam('System_Locale_Language');
                    if (!in_array($paramLanguage, $arrLanguages)) {
                        $paramLanguage = null;
                    }
                }
            }
        }

        // search Language in Zend_Locale
        /*if (!$paramLanguage) {
            $paramLanguage = Zend_Locale::getDefault();
            if (!in_array($paramLanguage, $arrLanguages)) {
                $paramLanguage = null;
            }
        }*/

        // get Language defined in config as default
        if (!$paramLanguage) {
            $paramLanguage = System_Locale::getInstance()->getDefaultLang();
        }

        /*
        $arrRoutes = array_keys(System_Router::getInstance()->getRouter()->getRoutes());
        foreach ($arrRoutes as $strRouteName){
            if ($strRouteName == 'default'){
                continue;
            }
            $objRoute = System_Router::getInstance()->getRouter()->getRoute($strRouteName);
            //var_dump($objRoute);
            if ($objRoute instanceof Zend_Controller_Router_Route) {
                if ($objRoute->getDefault('language')) {
                    $objRoute->setDefault('language', $paramLanguage);
                    echo '1';
                }
            }
            if ($objRoute instanceof Zend_Controller_Router_Route_Chain) {
                foreach ($objRoute->_routes as $objRoute2) {
                    if ($objRoute2->getDefault('language')) {
                        $objRoute2->setDefault('language', $paramLanguage);
                        echo '1';
                    }
                }
            }
        }*/

        $this->objectZendLocale = new Zend_Locale($paramLanguage);

        // save Language in session
        if ($this->getConfig()->SaveInSession) {
            System_Session::setParam('System_Locale_Language', $paramLanguage);
        }

        $this->setDispatched(true);
    }

    /**
     * Set flag indicating whether or not Syste_Locale has been dispatched
     * @param boolean $flag
     * @return Zend_Controller_Request_Abstract
     */
    public function setDispatched($flag = true)
    {
        $this->_dispatched = $flag ? true : false;
        return $this;
    }

    /**
     * Determine if the Syste_Locale has been preDispatched
     *
     * @return boolean
     */
    public function isDispatched()
    {
        return $this->_dispatched;
    }


    /**
    * Возвращает массив языков из конфига
    * @author norbis
    * @return array
    */
    public function getLanguages()
    {
        return $this->_config->languages->toArray();
    }

    /**
    * Возвращает строку, язык по-умолчанию из конфига
    * @author norbis
    * @return string
    */
    public function getDefaultLang()
    {
        return $this->_config->default;
    }


    /**
    * Возвращает строку, текущий язык
    * @author keeper
    * @return string
    */
    public static function getLanguage()
    {
        return self::getZendLocale()->getLanguage();
    }

    /**
    * return registered Zend_Locale exemplar
    * @author keeper
    * @return Zend_Locale
    */
    public function _getZendLocale()
    {
        if (!is_object($this->objectZendLocale)) {
            $this->objectZendLocale = new Zend_Locale($this->getDefaultLang());
        }
        return $this->objectZendLocale;
    }
    /**
    * return registered Zend_Locale exemplar
    * @author keeper
    * @return Zend_Locale
    */
    public static function getZendLocale()
    {
        return self::getInstance()->_getZendLocale();
    }



    /**
    * Реализация функции для SingleTone паттерна
    * @author norbis
    * @return System_Locale
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}