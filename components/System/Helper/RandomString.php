<?php
/**
 * generate random string
 * @author keeper
 * @category
 * @package
 * @subpackage
 * @copyright Copyright (c) 2005-2013 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Helper_RandomString
{

    /**
     * generate random string
     * @author keeper
     * @param int $LenghtMin
     * @param int $LenghtMax
     * @param string $Chars
     * @return string random string
     */
    public function get($LenghtMin=10, $LenghtMax=10, $Chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
        $arrChars = str_split($Chars);
        $intCharsCount = count($arrChars);
        $strResult = '';
        $Lenght = rand($LenghtMin, $LenghtMax);
        for ($i=0;$i<$Lenght;$i++) {
            $strResult .= $arrChars[rand(0,$intCharsCount-1)];
        }
        return $strResult;
    }

    /**
     * Documentation
     *
     * @var mixed
     */
    protected static $_instance;

    /**
    * Return System_Helper_RandomString static instance
    * @author keeper
    * @return System_Helper_RandomString
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}