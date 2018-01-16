<?php
/**
 * Попытка исправить стандартный Zend_View_Helper_Url
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class System_View_Helper_GetBaseUrl extends Zend_View_Helper_Abstract
{

    public function getBaseUrl()
    {
        $paramProtocol = System_Application::getInstance()->getProtocol();
        return $paramProtocol . '://' . $_SERVER['HTTP_HOST'] . Zend_Registry::get('baseUrl');
    }
}
