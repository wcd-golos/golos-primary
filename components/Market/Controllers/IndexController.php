<?php
class IndexController extends System_Controller_Base
{
	public function indexAction()
    {
        $products = Market_Table::getInstance()->getProductsList();
        $this->view->products = $products;
    }
    
    
    public function productAction()
    {
        $productCode = $this->getRequest()->getParam('name');
        
        if(!$productCode){
            $this->redirect('/');
        }
        
        $products = Market_Table::getInstance()->getProductsList();
        $rows = array();
        
        $objRows = Market_Reviews_Table::getInstance()->getRowsByProduct($productCode);;
        
        foreach ($objRows as $row){
            $data = array('author' => $row->Author, 'permlink' => $row->Permlink);
            array_push($rows, $data);
        }
        
        $this->view->jsonData = Zend_Json::encode($rows);
        $this->view->count = $objRows->count();
        
        $this->view->products = $products;
        $this->view->product = $products[$productCode];
        $this->view->productCode = $productCode;
    }
    
    public function postAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $author = $this->getRequest()->getParam('author');
        $permlink = $this->getRequest()->getParam('permlink');
        $product = $this->getRequest()->getParam('product');
        
        $arrResponse = array('error'=>null);
        
        if($author && $permlink && $product){
            $objReview = Market_Reviews_Table::getInstance()->fetchNew();
            $objReview->Author = $author;
            $objReview->Permlink = $permlink;
            $objReview->Product = $product;
            $objReview->save();
        }else{
            $arrResponse['error'] = 'Parameters not valid';
        }
        
        return $this->getResponse()->setBody(Zend_Json::encode($arrResponse));
    }
}
