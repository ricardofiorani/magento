<?php

class Squidfacil_Squidfacilapi_Block_Adminhtml_Import extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_import';
        $this->_blockGroup = 'squidfacilapi';
        $this->_headerText = Mage::helper('squidfacilapi')->__('Squid Fácil');
        $this->_addButtonLabel = Mage::helper('squidfacilapi')->__('Add Item');
        $this->_mode = 'options';
        $this->removeButton('reset');
        $this->removeButton('back');
    }

}