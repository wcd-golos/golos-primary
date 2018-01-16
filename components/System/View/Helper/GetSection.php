<?php
/**
 * Попытка исправить стандартный Zend_View_Helper_Url
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class System_View_Helper_GetSection extends Zend_View_Helper_Abstract
{
    
    public function getSection()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        return $request->getParam('section');
    }
}
