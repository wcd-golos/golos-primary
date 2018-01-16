<?php
/**
 * Documentation
 * @author
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2011 Pilgrim Consulting, Inc. (http://pilgrimconsulting.com/)
 * @license
 */
abstract class System_Db_Object extends Zend_Db_Table_Row_Abstract
{
    /**
    * Проверка поля и заполнение
    * @author norbis
    * @return bool
    */
    public function checkSEID($Field='Name')
    {
        if ($this->SEID) {
            $i = 0;
            $SEID = $this->SEID;
            while ($this->isexistSEID()) {
                $this->SEID = $SEID . '-' . $i;
                $i++;
            }
        } else {
            $this->fillSEID($Field);
        }
        return true;
    }

    /**
    * Проверяет поле SEID на уникальность.
    * @author norbis
    * @return bool
    */
    public function isexistSEID()
    {
        $selectDouble = $this->getTable()->select();
        $selectDouble->where('SEID = ?', $this->SEID);
        if ($this->ID) $selectDouble->where('ID <> ?', $this->ID);
        $selectDouble->limit(1);
        return is_object($this->getTable()->fetchRow($selectDouble));
    }

    /**
    * Заполняет поле SEID
    * @author norbis
    * @return void
    */
    public function fillSEID($Field='Name')
    {
        $FilterTransliteration = new System_Filter_Transliteration();
        $this->SEID = $FilterTransliteration->filter($this->$Field);
        $this->SEID = preg_replace('([^a-z0-9_-])', ' ', strtolower($this->SEID));
        $this->SEID = trim($this->SEID);
        while (strpos($this->SEID, '  ') !== false) {
            $this->SEID = str_replace('  ', ' ', $this->SEID);
        }
        $this->SEID = str_replace(' ', '-', $this->SEID);
        while (strpos($this->SEID, '--') !== false) {
            $this->SEID = str_replace('--', '-', $this->SEID);
        }
        $this->SEID = trim($this->SEID, '-');
        $this->SEID = substr($this->SEID, 0, 245);

        $i = 0;
        $SEID = $this->SEID;
        while ($this->isexistSEID()) {
            $this->SEID = $SEID . '-' . $i;
            $i++;
        }
    }

    /**
    * Заполняет поле на измение
    * @author norbis
    * @return bool
    */
    public function isChanged($strField)
    {
        if (isset($this->_cleanData[$strField]) && $this->_cleanData[$strField] == $this->$strField){
            return FALSE;
        }
        return TRUE;
    }


    /**
     * fix the original insert (mysql 5.x) when null field tries to insert in table where it can't be null
     * @author keeper
     */
    public function save()
    {
    	$arrMetadata = $this->getTable()->info(System_Db_Table::METADATA);
        if (is_array($arrMetadata) && count($arrMetadata)) {
            foreach ($arrMetadata as $strFieldName=>$arrFieldMetadata) {
            	if ($arrFieldMetadata['NULLABLE']) continue;
                // leave primary index field without changes
                if ($arrFieldMetadata['PRIMARY']) continue;

                // continue working with the null fields only
                if (isset($this->$strFieldName) && !is_null($this->$strFieldName)) continue;
                if (!isset($this->$strFieldName) && $arrFieldMetadata['DEFAULT']) continue;

                // set default value if its not null
                if (strlen($arrFieldMetadata['DEFAULT']) || abs($arrFieldMetadata['DEFAULT'])>0) {
                    //if (in_array($arrFieldMetadata['DATA_TYPE'], array('text', 'varchar', 'blob', 'float', 'int', 'tinyint'))) {
                	   //$this->$strFieldName = $arrFieldMetadata['DEFAULT'];
                    //}
                    /*if ($arrFieldMetadata['DATA_TYPE'] == 'timestamp') {
                       $this->$strFieldName = new Zend_Db_Expr('CONVERT(\''.$arrFieldMetadata['DEFAULT'].'\', DATETIME)');
               		}*/
                    $this->$strFieldName = new Zend_Db_Expr('DEFAULT');
                	continue;
                }
                switch ($arrFieldMetadata['DATA_TYPE']) {
                    case 'text':
                    case 'varchar':
                    case 'blob':
                        $this->$strFieldName = ''; break;
                    case 'float':
                    case 'int':
                    case 'tinyint':
                        $this->$strFieldName = 0; break;
                    default: continue;
                }
                //$this->$strFieldName = $arrFieldMetadata['DEFAULT'];
            }
        }

        //file_put_contents('d:/test.txt', print_r($this->toArray(), true));
        //var_dump($arrMetadata);
        //var_dump($this->toArray());
        //exit();
        return parent::save();
    }
}