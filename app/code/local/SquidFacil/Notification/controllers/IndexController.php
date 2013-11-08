<?php
class SquidFacil_Notification_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $externalProduct = Mage::getModel('import/Product');
        $externalProduct->getBySku($this->getRequest()->getParam('sku'));
        $product = Mage::getModel('catalog/product');
        $id = $product->getIdBySku($this->getRequest()->getParam('sku'));
        if($id && $externalProduct){
            Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
            $product->load($id);
            $product->setName($externalProduct->title);
            if($product->getPrice() <= $externalProduct->price){
                $product->setPrice($externalProduct->suggested_price);
            }
            $product->setWeight($externalProduct->weight);
            $product->setMsrp($externalProduct->suggested_price);
            $product->setDescription($externalProduct->description);
            $product->setShortDescription($externalProduct->short_description);
            $product->save();
            
            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->loadByProduct($id);
            
            $stockItem->setQty($externalProduct->getStock());
            $stockItem->save();
        } else {
            Mage::log("ignorando produto ".$this->getRequest()->getParam('sku'));
        }
    }
}