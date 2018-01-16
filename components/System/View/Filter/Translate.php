<?php
/**
 * filter to translate text inside special tag
 * @author keeper
 * @category
 * @package
 * @subpackage
 * @copyright Copyright (c) 2005-2010 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_View_Filter_Translate implements Zend_Filter_Interface
{
    /**
     * translate text inside special tags
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if (!System_Translate::getInstance()->getConfig()->enabled) return $value;

        $startDelimiter = System_View::getInstance()->getConfig()->Filter->Translate->Delimiter->Start;
        $endDelimiter = System_View::getInstance()->getConfig()->Filter->Translate->Delimiter->End;

        $translator = Zend_Registry::get('Zend_Translate');

        $offset = 0;
        while (($posStart = strpos($value, $startDelimiter, $offset)) !== false) {
            $offset = $posStart + strlen($startDelimiter);
            if (($posEnd = strpos($value, $endDelimiter, $offset)) === false) {
                throw new Zend_Exception("No ending tag after position [$offset] found!");
            }
            $translate = substr($value, $offset, $posEnd - $offset);

            $translate = $translator->_($translate);

            $offset = $posEnd + strlen($endDelimiter);
            $value = substr_replace($value, $translate, $posStart, $offset - $posStart);
            $offset = $offset - strlen($startDelimiter) - strlen($endDelimiter);
        }
        return $value;
    }
}