<?php
/**
 * Documentation
 * @todo: Необходим имплемент.
 * @author
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
class System_Form_Element_DbSelect extends Zend_Form_Element_Select
{
	/**
     * Documentation
     *
     * @var mixed
     */
  private $_dbAdapter;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
  private $_dbSelect;
	    
	/**
     * Documentation
     *
     * @var mixed
     */
  private $_identityColumn = 'id';
	    
	/**
     * Documentation
     *
     * @var mixed
     */
  private $_valueColumn = '';
 
  /**
   * Set the database adapter used
   * @param Zend_Db_Adapter_Abstract $adapter
   */
  public function setDbAdapter(Zend_Db_Adapter_Abstract $adapter) {
    $this->_dbAdapter = $adapter;
  }
 
  /**
   * Set the query used to fetch the data
   * @param string|Zend_Db_Select $select
   */
  public function setDbSelect($select) {
    $this->_dbSelect = $select;
  }
 
  /**
   * Set the column where the identifiers for the options are fetched
   * @param string $name
   */
  public function setIdentityColumn($name) {
    $this->_identityColumn = $name;
  }
 
  /**
   * Set the column where the visible values in the options are fetched
   * @param string $name
   */
  public function setValueColumn($name) {
    $this->_valueColumn = $name;
  }

    /**
    * Documentation
    * @author
    * @return mixed
    */
  public function render(Zend_View_Interface $view = null) {
    $this->_performSelect();
    return parent::render($view);
  }
  
    private function _performSelect() {
      if(!$this->_dbAdapter)
        $this->_dbAdapter = Zend_Db_Table::getDefaultAdapter();
     
      $stmt = $this->_dbAdapter->query($this->_dbSelect);
      $results = $stmt->fetchAll(Zend_Db::FETCH_ASSOC);
      $options = array();
     
      foreach($results as $r) {
        if(!isset($r[$this->_identityColumn])) {
          throw new Zend_Form_Element_Exception(
            'Identity column is not present in the result');
        }
     
        if(!isset($r[$this->_valueColumn])) {
          throw new Zend_Form_Element_Exception(
            'Value column is not present in the result');
        }
     
        $options[$r[$this->_identityColumn]] = $r[$this->_valueColumn];
      }
     
      $this->setMultiOptions($options);
    }
}
