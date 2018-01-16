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
abstract class System_Db_List extends Zend_Db_Table_Rowset_Abstract
{
    /**
    * Documentation
    * @author
    * @return mixed
    */
	function __construct($config)
	{
		parent::__construct($config);
	}

    function getID()
	{
	    $arrValues = array();
	    foreach ($this as $objPage){
            $arrValues[] = $objPage->ID;
	    }
	    return $arrValues;
	}

	function getInSQL()
	{
	    return implode(',', $this->getID());
	}
}