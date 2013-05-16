<?php

class Squidfacil_Import_Block_Adminhtml_List extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        parent::__construct();
        $this->_controller = 'adminhtml_list';
        $this->_blockGroup = 'import';
        $this->_headerText = Mage::helper('import')->__('Item Manager');
        $this->removeButton('add');
    }

}