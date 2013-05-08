<?php

class Squidfacil_Squidfacilapi_Adminhtml_ListController extends Mage_Adminhtml_Controller_Action {
    
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('squidfacilapi/list')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
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