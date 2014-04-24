<?php

class SquidFacil_Import_Block_Adminhtml_List_Renderer_Checkbox extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract implements Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Interface
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
                return '';
            }
        }
        return '<input type="checkbox" name="import" value="' . $row->getData('sku') . '" class="massaction-checkbox">';
    }

    public function setColumn($column)
    {
        return parent::setColumn($column);
    }

}
