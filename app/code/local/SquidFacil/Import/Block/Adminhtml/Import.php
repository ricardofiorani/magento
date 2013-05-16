<?php

class SquidFacil_Import_Block_Adminhtml_Import extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_import';
        $this->_blockGroup = 'import';
        $this->_headerText = Mage::helper('import')->__('Squid Fácil');
        $this->_addButtonLabel = Mage::helper('import')->__('Add Item');
        $this->_mode = 'options';
        $this->removeButton('reset');
        $this->removeButton('back');
    }

}