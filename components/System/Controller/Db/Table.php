<?php
/**
 * Контроллер для управления данными в одной таблице БД.
 * @author norbis
 * @category
 * @package
 * @subpackage
 * @copyright  Copyright (c) 2005-2009 Wecandevelopit Inc. (http://www.wecandevelopit.com)
 * @license
 */
class System_Controller_Db_Table extends System_Controller_Base
{
     /**
     * Model to controller
     *
     * @var System_Db_Table
     */
    protected $_model = null;

     /**
     * Model name
     *
     * @var string
     */
    protected $_modelName = null;

     /**
     * Meta data from model db table
     *
     * @var array
     */
    protected $_modelMetaData = null;

     /**
     * Select from model db table
     *
     * @var Zend_Db_Table_Select
     */
    protected $_select = null;

     /**
     * Select from model db table, using in getlist action for obtain
     * objects count
     * @var Zend_Db_Table_Select
     */
    protected $_selectCount = null;

     /**
     * Documentation
     * @var Zend_Db_Table_Row
     */
    protected $_object = null;

     /**
     * Documentation
     * @var Zend_Db_Table_Rowset
     */
    protected $_list = null;

     /**
     * Documentation
     * @var Zend_Form
     */
    protected $_objectForm = null;

    /**
    * Initialization function
    * @author norbis
    * @return void
    */
    public function init()
    {
        parent::init();
        $helperContextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
        $helperContextSwitch->addActionContext('get',                 array('xml', 'json', 'table', 'options', 'ul'));
        $helperContextSwitch->addActionContext('getlist',             array('xml', 'json', 'table', 'options', 'ul', 'tab'));
        $helperContextSwitch->addActionContext('getlistreferenced',   array('xml', 'json', 'table', 'options', 'ul'));
        $helperContextSwitch->initContext();

        $this->_modelName       = $this->getModelName();
        $this->_model           = new $this->_modelName;
        $this->_modelMetaData   = $this->_model->info(System_Db_Table::METADATA);
        $this->_select          = $this->_model->select()->reset()->from($this->_model->getTableName());
        $this->_selectCount     = $this->_model->select()->reset();
        $this->_selectCount->from($this->_model->getTableName(), array('count'=>new Zend_Db_Expr('COUNT(*)')));
        $this->view->metadata   = $this->_modelMetaData;
         /*
          * setIntegrity
         */
    }


    /**
    * Return Table class name
    * @author norbis
    * @return string
    */
    protected function getModelName()
    {
        return $this->getClassName() . '_Table';
    }


    /**
    * Get and return Form instance
    * @author
    * @return Zend_Form
    */
    public function getForm($Action = '', $strName = 'Edit')
    {
        $nameClassForm = $this->getFormClassName($strName);
        $objectForm = new $nameClassForm;
        $objectForm->setAction($Action);
        return $objectForm;
    }


    /**
    * Return Form class name
    * @author norbis
    * @return string
    */
    public function getFormClassName($strName = 'Edit')
    {
        return $this->getClassName() . '_Form_' . $strName;
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function indexAction()
    {
        $this->_forward('getlist');
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function getAction()
    {
        $paramID = $this->getRequest()->getParam('ID', $this->getRequest()->getParam('id'));
        $paramTemplate  = $this->getRequest()->getParam('template');
        if ($paramID === NULL){
            throw new Zend_Exception('Param ID should be defined for getAction', 404);
        }

        if(intval($paramID)){
        	$this->_select->where($this->_model->getTableName() . '.ID = ?' , $paramID);
        }else{
        	$this->_select->where($this->_model->getTableName() . '.Code = ?' , $paramID);
        }
        $object = $this->_model->fetchRow($this->_select);
        if (!is_object($object)){
            throw new Zend_Exception('Object with ID = ' . $paramID . ' not found in DB', 404);
        }
        $this->_object = $object;
        $this->view->row = $object->toArray();
        if ($paramTemplate){
            $this->render('get-' . $paramTemplate);
        }
    }


    /**
    * Documentation
    * @author norbis, keeper
    * @return mixed
    */
    function getlistAction()
    {
        $paramTemplate  = $this->getRequest()->getParam('template');

        $objFilter = $this->getFilter();

        //$objFilter->paramSort = $this->getRequest()->getParam('sort', isset($objFilter->paramSort) ? $objFilter->paramSort : $this->_model->getTableName().'.ID');
        $objFilter->paramSort = $this->getRequest()->getParam('sort', isset($objFilter->paramSort) ? $objFilter->paramSort : 'ID');
        $objFilter->paramDir = $this->getRequest()->getParam('dir', isset($objFilter->paramDir) ? $objFilter->paramDir : 'ASC');
        $objFilter->paramStart = (int)$this->getRequest()->getParam('start', isset($objFilter->paramStart) ? $objFilter->paramStart : 0);
        $objFilter->paramPage = (int)$this->getRequest()->getParam('page', isset($objFilter->paramPage) ? $objFilter->paramPage : 1);
        $objFilter->paramResults = (int)$this->getRequest()->getParam('results', isset($objFilter->paramResults) ? $objFilter->paramResults : 10);


        $allRowsCount = $this->_model->fetchRow($this->_selectCount);
        if (is_object($allRowsCount)) {
            $allRowsCount = (int)$allRowsCount->count;
        } else {
            $allRowsCount = 0;
        }

        $pagesCount = ceil($allRowsCount / $objFilter->paramResults);
        if ($objFilter->paramPage < 1 || $objFilter->paramPage > $pagesCount){
            $objFilter->paramPage = 1;
        }
        $objFilter->paramStart = ($objFilter->paramPage - 1) * $objFilter->paramResults;

        $this->_select->limit($objFilter->paramResults, $objFilter->paramStart);
        if (is_array($objFilter->paramSort)) {
            $this->_select->order($objFilter->paramSort);
        } else {
            $this->_select->order(strval($objFilter->paramSort) . ' ' . $objFilter->paramDir);
        }

        $listObjects = $this->_model->fetchAll($this->_select);

        $this->_list = $listObjects;

        $this->view->rows   = $listObjects->toArray();
        $this->view->Filter = $objFilter;
        $this->view->page   = $objFilter->paramPage;
        $this->view->results = $objFilter->paramResults;
        $this->view->sort   = $objFilter->paramSort;
        $this->view->dir    = $objFilter->paramDir;
        $this->view->pagesCount = $pagesCount;
        $this->view->totalCount = $allRowsCount;

        if ($paramTemplate) {
            $this->render('getlist-' . $paramTemplate);
        }

    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function editAction()
    {
        $paramID = $this->getRequest()->getParam('ID', $this->getRequest()->getParam('id'));

        if (!$paramID){
            $this->_object = $this->_model->fetchNew();
        }else{
            $this->_object = $this->_model->find($paramID)->current();
            if (!is_object($this->_object)){
                throw new Zend_Exception('Object with ID = ' . $paramID . ' not found in DB');
            }
        }
        $this->_objectForm = $this->getForm();
        $this->_objectForm->setDefaults($this->_object->toArray());
        if ($this->getRequest()->isPost()) {
            if ($this->_objectForm->isValid($_POST)){
                $arrayElements = $this->_objectForm->getElements();
                foreach ($arrayElements as $nameElement => $objectElement){
                   if ($nameElement == 'ID'){
                       continue;
                   }

                   if (isset($this->_modelMetaData[$nameElement])){
                       if ($this->_objectForm->getValue($nameElement) !== NULL){
                           $this->_object->$nameElement = $this->_objectForm->getValue($nameElement);
                       }
                   }
                }
                if (isset($this->_modelMetaData['User_ID'])){
                    $this->_object->User_ID = (int)$this->getRequest()->getParam('User_ID');
                }
                try{
                    $this->_object->save();
                    $this->addOkMessage($this->_objectForm->getObjectName() . ' successfully saved');
                }catch (Zend_Exception $e){
                    $this->addErrorMessage($this->_objectForm->getObjectName() . ' not saved. Reason:' . $e->getMessage());
                }
            }else{
                $this->addErrorMessage('Please, fill the form correctly');
            }
        }
        $this->view->object = $this->_object;
        $this->view->form = $this->_objectForm;
    }


    /**
    * Функция удаления объектов
    * @author norbis
    * @return void
    */
    function deleteAction()
    {
        $paramID = $this->getRequest()->getParam('ID', $this->getRequest()->getParam('id'));
        if ($paramID === NULL){
            throw new Zend_Exception('Param ID should be defined for deleteAction');
        }

        if (!is_array($paramID)){
            $arrID = explode(',', $paramID);
            if (count($arrID) > 0){
                $paramID = $arrID;
            }
        }

        foreach ($paramID as $objectID){
            $object = $this->_model->find($objectID)->current();
            if (!is_object($object)){
                $this->addErrorMessage('Item with ID '. $objectID.' already deleted');
                continue;
            }
            $object->delete();
        }
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function changeAction()
    {
        $paramID    = $this->getRequest()->getParam('ID', $this->getRequest()->getParam('id'));
        $paramField = $this->getRequest()->getParam('field');
        $paramValue = $this->getRequest()->getParam('value');

        if (!$paramID){
            throw new Zend_Exception('Parametr ' . $this->getClassName() . ' ID should be defined');
        }

        if (!is_array($paramID)){
            $arrID = explode(',', $paramID);
            if (count($arrID) > 0){
                $paramID = $arrID;
            }
        }

        foreach ($paramID as $objectID){
            $object = $this->_model->find($objectID)->current();
            if (!is_object($object)){
                throw new Zend_Exception('Object ' . $this->getClassName() . ' with ID = ' . $objectID . ' not found in DB');
            }
            $object->$paramField = $paramValue;
            $object->save();
        }

        $this->_helper->viewRenderer->setNoRender(true);
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function manageAction()
    {

    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function buttonsAction()
    {
        $paramType = $this->getRequest()->getParam('type', 'manage');
        $this->render('buttons-' . $paramType);
    }


    /**
    * Documentation
    * @author
    * @return mixed
    */
    function testAction()
    {
        foreach ($this->_modelMetaData as $nameField => $arrayField){
            if (!$arrayField['NULLABLE'] && $arrayField['DEFAULT'] === NULL && !$arrayField['IDENTITY'] ){
                if ($nameField == 'Name'){
                    continue;
                }
                throw new Zend_Exception('Field ' . $arrayField['COLUMN_NAME'] . ' in ' . $this->getModelName() . '  doesnt have default value');
            }
        }
        parent::testAction();
    }

}