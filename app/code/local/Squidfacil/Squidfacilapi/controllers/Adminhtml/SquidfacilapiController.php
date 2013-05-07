<?php

class Squidfacil_Squidfacilapi_Adminhtml_SquidfacilapiController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('squidfacilapi/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function import_Action() {
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
        $this->_initAction()
                ->renderLayout();
    }

    public function importAction() {
        $sku = $this->getRequest()->getParam('sku');
        $model = Mage::getModel('squidfacilapi/products');
        $item = $model->getItemByColumnValue('sku', $sku);

        $api = new Mage_Catalog_Model_Product_Api();

        $attribute_api = new Mage_Catalog_Model_Product_Attribute_Set_Api();
        $attribute_sets = $attribute_api->items();

        $productData = array();
        $productData['website_ids'] = array(1);

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
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        /*
        $squidfacilapiIds = $this->getRequest()->getParam('squidfacilapi');
        if (!is_array($squidfacilapiIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($squidfacilapiIds as $squidfacilapiId) {
                    $squidfacilapi = Mage::getModel('squidfacilapi/squidfacilapi')->load($squidfacilapiId);
                    $squidfacilapi->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('adminhtml')->__(
                                'Total of %d record(s) were successfully deleted', count($squidfacilapiIds)
                        )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
         * 
         */
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        /*
        $squidfacilapiIds = $this->getRequest()->getParam('squidfacilapi');
        if (!is_array($squidfacilapiIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($squidfacilapiIds as $squidfacilapiId) {
                    $squidfacilapi = Mage::getSingleton('squidfacilapi/squidfacilapi')
                            ->load($squidfacilapiId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d record(s) were successfully updated', count($squidfacilapiIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
         * 
         */
        $this->_redirect('*/*/index');
    }

}