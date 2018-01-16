<?php
/**
 * Плагин для фронтконтроллера для подгрузки файла c переводами
 * @author norbis
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Controller_Plugin_Language extends Zend_Controller_Plugin_Abstract
{
	/**
    * Documentation
    * @author
    * @return mixed
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $paramLanguage = $request->getParam('language');
        if (!$paramLanguage || $paramLanguage == System_Locale::getInstance()->getDefaultLang()) {
            $paramLanguage = System_Locale::getInstance()->getDefaultLang();
        }else{
            //System_Router::getInstance()->getRouter()->setGlobalParam('language', $paramLanguage);
        }
        $locale = new Zend_Locale($paramLanguage);
        Zend_Registry::set('Zend_Locale', $locale);

        $writer = new Zend_Log_Writer_Stream(Zend_Registry::get('AppFolder') . '/logs/untranslated.log');
        $log    = new Zend_Log($writer);
        $translationFile = Zend_Registry::get('AppFolder') . '/languages' . DIRECTORY_SEPARATOR . $paramLanguage . '.csv';

        $translate = new Zend_Translate('System_Translate_Adapter_Csv', $translationFile, $paramLanguage);
        // for new version: $translate = new Zend_Translate(array('adapter'=>'csv', 'content'=>$translationFile, 'locale'=>$paramLanguage, 'clear' => true));
        $translate->setOptions(array(
            'log'             => $log,
            'logMessage'      => "'%locale%'-'%message%'",
            'logUntranslated' => true));
        Zend_Registry::set('Zend_Translate', $translate);
    }
}
