<?php

class Squidfacil_Squidfacilapi_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action {

    public function indexAction(){
        $sku = $this->getRequest()->getParam('sku');
        $model = Mage::getModel('squidfacilapi/products');
        $item = $model->getItemByColumnValue('sku', $sku);
        
        $this->_title($item->title);
        $this->_title($this->__('System'))->_title($this->__('My Account'));

        $this->loadLayout();
        $this->_setActiveMenu('squidfacilapi/import');
        $this->_addContent($this->getLayout()->createBlock('squidfacilapi/adminhtml_import'));
        $this->renderLayout();
    }
    
    public function saveAction(){
        $sku = $this->getRequest()->getParam('sku');
        $model = Mage::getModel('squidfacilapi/products');
        $item = $model->getItemByColumnValue('sku', $sku);

        $api = new Mage_Catalog_Model_Product_Api();

        $attribute_api = new Mage_Catalog_Model_Product_Attribute_Set_Api();
        $attribute_sets = $attribute_api->items();

        $productData = array();
        $productData['website_ids'] = array(1);
        $productData['categories'] = array($this->getRequest()->getParam('category'));

        $productData['status'] = 1;

        $productData['name'] = $item->title;
        $productData['description'] = $item->description;
        $productData['short_description'] = $item->short_description;

        $productData['price'] = $item->suggested_price;
        $productData['weight'] = $item->weight;
        $productData['tax_class_id'] = 0;

        $new_product_id = $api->create('simple', $attribute_sets[0]['set_id'], $item->sku, $productData);

        $image_type = substr(strrchr($item->image, "."), 1);
        $filename = "tmp." . $image_type;
        $path = Mage::getBaseDir('media') . DS . 'import' . DS;
        if(!is_dir($path)){
            mkdir($path);
        }
        $fullpath =  $path . $filename;
        file_put_contents($fullpath, fopen($item->image, 'r'));

        $product = Mage::getModel('catalog/product')->load($new_product_id);
        $product->setMediaGallery(array('images' => array(), 'values' => array()));
        $product->addImageToMediaGallery($fullpath, array('image', 'small_image', 'thumbnail'), false, false);
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $product->save();
        $this->_redirect('*/adminhtml_list');
    }

}

?>
