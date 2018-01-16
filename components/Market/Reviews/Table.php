<?php
class Market_Reviews_Table extends System_Db_Table
{

    protected static $_instance = null;

	protected $_name = 'reviews';

    protected $_referenceMap    = array();

    /**
    * @return Market_Reviews_Table
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * @return Market_Reviews_List
     */
    public function getRowsByProduct($product)
    {
        return $this->fetchAll(
            $this->select()->where('Product = ?', $product)
        );
    }
}