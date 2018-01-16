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
class System_Database extends System
{
	/**
     * Documentation
     *
     * @var Zend_Db
     */
    protected $_db = null;

	/**
     * Documentation
     *
     * @var System_Database
     */
    protected static $_instance = null;

    /**
    * Конструктор
    * @author
    * @return System_Database
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Documentation
    * @author
    * @return Zend_Db
    */
    public function _getDB()
    {
    	if ($this->_db === NULL){
    		throw new Zend_Exception('Method init should be called');
    	}
    	return $this->_db;
    }

    /**
    * Возвращает ссылку на статический инстанс Zend_Db
    * @author norbis
    * @return Zend_Db
    */
    public static function getDB()
    {
		return self::getInstance()->_getDB();
    }

    /**
    * Documentation
    * @author
    * @return void
    */
    public function _init()
    {
    	$this->_db = Zend_Db::factory($this->_config->local->adapter,
        							  $this->_config->local->params);
        $this->_db->getConnection();
        $this->_db->query('SET NAMES utf8');

        if (System_Application::getInstance()->getDebugMode()){
            $objectDbProfiler = new Zend_Db_Profiler(true);
            $this->_db->setProfiler($objectDbProfiler);
        }
    }

    /**
    * Documentation
    * @author
    * @return void
    */
    public function _run()
    {

    }

    /**
    * Documentation
    * @author
    * @return System_Database
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}