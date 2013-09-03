<?php

class SquidFacil_Import_Adminhtml_ImportController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        $sku = $this->getRequest()->getParam('sku');
        //$model = Mage::getModel('import/products');
        //$item = $model->getItemByColumnValue('sku', $sku);
        //$this->_title($item->title);
        //$this->_title($this->__('System'))->_title($this->__('My Account'));

        $this->loadLayout();
        $this->_setActiveMenu('import/import');
        $this->_addContent($this->getLayout()->createBlock('import/adminhtml_import'));
        $this->renderLayout();
    }

    public function saveAction() {
        $sku_list = $this->getRequest()->getParam('sku');
        foreach ($sku_list as $sku) {
            $model = Mage::getModel('import/product');
            $item = $model->getBySku($sku);

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
            $productData['msrp'] = $item->suggested_price;
            $productData['weight'] = $item->weight;
            $productData['tax_class_id'] = 0;

            $new_product_id = $api->create('simple', $attribute_sets[0]['set_id'], $item->sku, $productData);

            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->loadByProduct($new_product_id);

            $stockItem->setData('use_config_manage_stock', 1);
            $stockItem->setData('qty', $item->stock);
            $stockItem->setData('min_qty', 0);
            $stockItem->setData('use_config_min_qty', 1);
            $stockItem->setData('min_sale_qty', 0);
            $stockItem->setData('use_config_max_sale_qty', 1);
            $stockItem->setData('max_sale_qty', 0);
            $stockItem->setData('use_config_max_sale_qty', 1);
            $stockItem->setData('is_qty_decimal', 0);
            $stockItem->setData('backorders', 0);
            $stockItem->setData('notify_stock_qty', 0);
            $stockItem->setData('is_in_stock', 1);
            $stockItem->setData('tax_class_id', 0);

            $stockItem->save();

            $image_type = substr(strrchr($item->image, "."), 1);
            $filename = "tmp." . $image_type;
            $path = Mage::getBaseDir('media') . DS . 'import' . DS;
            if (!is_dir($path)) {
                mkdir($path);
            }
            $fullpath = $path . $filename;

            $ch = curl_init($item->image);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            $raw = curl_exec($ch);
            curl_close($ch);
            if (file_exists($fullpath)) {
                unlink($fullpath);
            }
            $fp = fopen($fullpath, 'x');
            fwrite($fp, $raw);
            fclose($fp);

            $product = Mage::getModel('catalog/product')->load($new_product_id);
            $product->setMediaGallery(array('images' => array(), 'values' => array()));
            $product->addImageToMediaGallery($fullpath, array('image', 'small_image', 'thumbnail'), false, false);
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            $product->save();
        }
        $this->_redirect('*/adminhtml_list');
    }

}

?>