<?php
class Market_Table extends System_Db_Table
{

    protected static $_instance = null;
	    
	protected $_name = 'market';
	    
    protected $_referenceMap    = array();

    /**
    * @return Market_Table
    */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * @return array
     */
    public function getProductsList()
    {
        $products = array();
        
        $objRows = $this->fetchAll();
        
        if($objRows->count()){
            foreach ($objRows as $row){
                $products[$row->Alias] = array(
                  'name' => $row->Name,      
                  'image' => $row->Image,      
                  'desc' => $row->Desc,      
                  'price' => number_format($row->Price, 2)      
                );
            }
        }
        
        return $products;
    }
}