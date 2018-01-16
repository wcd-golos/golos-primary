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
class System_Autoloader implements Zend_Loader_Autoloader_Interface
{
    /**
     * Documentation
     *
     * @var mixed
     */
    //protected static $_instance;
    
    
    /**
     * spl_autoload() suitable implementation for supporting class autoloading.
     *
     * Attach to spl_autoload() using the following:
     * <code>
     * spl_autoload_register(array('Zend_Loader', 'autoload'));
     * </code>
     *
     * @param string $class
     * @return string|false Class name on success; false on failure
     */
    public function autoload($class)
    {
        try {
            $dirs = null;
            Zend_Loader::loadClass('System_Loader');
            System_Loader::loadClass($class, $dirs);
            return $class;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
    * Return Cach_Core static instance
    *
    * @return System_Components
    */
    /*public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }*/
}