<?php

class SquidFacil_Import_Block_Adminhtml_List_Renderer_Importer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract implements Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Interface
{

    public function getColumn()
    {
        return parent::getColumn();
    }

    public function render(Varien_Object $row)
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        foreach ($collection as $product) {
            if ($product->getSku() == $row->getSku()) {
                return '<span style="color:green">Already Imported</span>';
            }
        }
        return '<a href="' . $this->getUrl('*/adminhtml_import', array('sku' => $row->getSku())) . '">Import</a>';
    }

    public function setColumn($column)
    {
        return parent::setColumn($column);
    }

}
