<?php
class Squidfacil_Squidfacilapi_Block_Adminhtml_Squidfacilapi extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_squidfacilapi';
    $this->_blockGroup = 'squidfacilapi';
    $this->_headerText = Mage::helper('squidfacilapi')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('squidfacilapi')->__('Add Item');
    parent::__construct();
    $this->removeButton('add');
  }
}