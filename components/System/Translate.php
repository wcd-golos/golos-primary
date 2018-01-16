<?php
class System_Translate extends System
{

	/**
     * Documentation
     *
     * @var System_Locale
     */
    protected static $_instance = null;

    /**
    * Функция инициализации
    * @author keeper
    * @return void
    */
    public function _init()
    {

    }

    /**
    * Функция запуска
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
        if (!$this->_enabled) return;
        $paramLanguage = System_Locale::getLanguage();

        $writer = new Zend_Log_Writer_Stream(Zend_Registry::get('AppFolder') . '/logs/untranslated.log');
        $log    = new Zend_Log($writer);

        $translationFile = Zend_Registry::get('AppFolder') . '/languages' . DIRECTORY_SEPARATOR . $paramLanguage . '.csv';

        //$translate = new Zend_Translate($this->getConfig()->Adapter, $translationFile, $paramLanguage);
        $translate = new Zend_Translate(array('adapter'=>$this->getConfig()->Adapter, 'content'=>$translationFile, 'locale'=>$paramLanguage, 'clear'=>true));
        $translate->setOptions(array(
            'log'             => $log,
            'logMessage'      => "'%locale%'-'%message%'",
            'logUntranslated' => true));

        Zend_Registry::set('Zend_Translate', $translate);
        //$translate->getAdapter()->translate();
    }


    /**
     * Translate a message
     * You can give multiple params or an array of params.
     * If you want to output another locale just set it as last single parameter
     * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
     * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
     *
     * @param  string $messageid Id of the message to be translated
     * @return string Translated message
     */
    public static function translate($messageid = null)
    {
        if (!Zend_Registry::isRegistered('Zend_Translate')) {
            return $messageid;
        }
        $translate = Zend_Registry::get('Zend_Translate');
        if ($translate === null) {
            return $messageid;
        }

        $options = func_get_args();
        array_shift($options);

        $count  = count($options);
        $locale = null;
        if ($count > 0) {
            if (Zend_Locale::isLocale($options[($count - 1)], null, false) !== false) {
                $locale = array_pop($options);
            }
        }

        if ((count($options) === 1) and (is_array($options[0]) === true)) {
            $options = $options[0];
        }

        $message = $translate->translate($messageid, $locale);
        if (count($options) === 0) {
            return $message;
        }

        return vsprintf($message, $options);
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