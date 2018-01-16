<?php
/**
 * Documentation
 * @author
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Wecandevelopit Inc. (http://www.wecandevelopit.com)
 * @license
 */
class System_Cache extends System
{
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_cache = null;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_arrayOptionsFrontend;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
    protected $_arrayOptionsBackend;
	    
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
    * Documentation
    * @author
    * @return mixed
    */
    public function _getCache()
    {
        if ($this->_cache === NULL){
            throw new Zend_Exception('Method init should be called');
        }
        return $this->_cache;
    }
      
	/**
	* Return Cach_Core static instance
	*
	* @return Zend_Cache_Core
	*/
    public static function getCache()
    {
        return self::getInstance()->_getCache();
    }

    /**
    * Documentation
    * @author norbis, keeper
    * @return mixed
    */
    public function _init()
    {
		$this->_arrayOptionsFrontend = array(
		   'lifetime' => 7200, // время жизни кэша - 2 часа
		   'automatic_serialization' => true
		);
		
		$this->_arrayOptionsBackend = array(
            'cache_dir' => Zend_Registry::get('AppFolder') . '/cache',
            'cache_file_perm' => 0660);
		                             
    }

    /**
    * Documentation
    * @author
    * @return mixed
    */
    public function _run()
    {
        $this->_cache = Zend_Cache::factory('Core', 'File', $this->_arrayOptionsFrontend, $this->_arrayOptionsBackend);
        Zend_Db_Table_Abstract::setDefaultMetadataCache($this->_cache);
    }
    
    /**
    * Return Cach_Core static instance
    *
    * @return System_Cache
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}
