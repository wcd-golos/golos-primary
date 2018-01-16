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
abstract class System_Db_Table extends Zend_Db_Table_Abstract
{
	/**
     * Documentation
     *
     * @var mixed
     */
	protected $_primary = 'ID';

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function getTableName()
	{
		return $this->_name;
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
	public function __construct($config = array())
	{
		if (!isset($config['db'])){
		    $config['db'] = System_Database::getDB();
		}
	    parent::__construct($config);
        $this->setRowClasses();
	}
	
	protected function setRowClasses()
	{
		$strObjectName = $this->getObjectName();
		System_Loader::loadClass($strObjectName);
	    $this->setRowClass($strObjectName);
	    $this->setRowsetClass($strObjectName . '_List');
	}

    /**
    * Documentation
    * @author
    * @return mixed
    */
	protected function getObjectName()
	{
		$classes = explode('_', get_class($this));
		array_pop($classes);
		return implode('_', $classes);
	}

    /**
    * Documentation
    * @author
    * @return System_Db_List
    */
	public function getOptionsList($KeyField = 'ID', $ValueField = 'Name', $Filters = array())
	{
		$select = $this->select();
        $select->from($this,array('key' => $KeyField, 'value' => $ValueField));
        if (is_array($Filters) && count($Filters)){
        	foreach ($Filters as $Filter){
        		$select->where($Filter['cond'], $Filter['value']);
        	}
        }
        return $this->fetchAll($select);
	}

    /**
    * Инициализация таблицы. Очистка и установка дефолтных данных
    * @author
    * @return mixed
    */
	public function initialize()
	{
	    $this->truncate();
	    $this->loadDefaultData();
	}

    /**
    * Установка дефолтных данных
    * @author
    * @return mixed
    */
	public function loadDefaultData()
	{
	    
	}
	
    /**
    * Функция очистки данных в таблице
    * @ntodo: Сделать очистку данных в таблицах
    * @author
    * @return void
    */
	public function truncate()
	{
	    
	}
	
    /**
    * Функция инсталла таблицы (чтение и запуск) install.sql (в папке рядом с этим файлом)
    * @ntodo: Необходим имплемент функции инсталла
    * @author
    * @return void
    */
	public function install()
	{
	    
	}
	
}