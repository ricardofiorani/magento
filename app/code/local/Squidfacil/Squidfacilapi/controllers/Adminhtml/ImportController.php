<?php

class Squidfacil_Squidfacilapi_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action {

    public function indexAction(){
        $sku = $this->getRequest()->getParam('sku');
        $model = Mage::getModel('squidfacilapi/products');
        $item = $model->getItemByColumnValue('sku', $sku);
//        $product = $this->_initProduct();
//
//        if ($productId && !$product->getId()) {
//            $this->_getSession()->addError(Mage::helper('catalog')->__('This product no longer exists.'));
//            $this->_redirect('*/*/');
//            return;
//        }
        
        $this->_title($item->title);
        $this->_title($this->__('System'))->_title($this->__('My Account'));

        $this->loadLayout();
        $this->_setActiveMenu('squidfacilapi/import');
        $this->_addContent($this->getLayout()->createBlock('squidfacilapi/adminhtml_import_form'));
        $this->renderLayout();
    }

}

?>
