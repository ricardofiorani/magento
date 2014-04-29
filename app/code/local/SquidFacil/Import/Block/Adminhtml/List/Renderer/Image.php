<?php

class SquidFacil_Import_Block_Adminhtml_List_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract implements Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Interface
{

    public function getColumn()
    {
        return parent::getColumn();
    }

    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        $imageLink = str_replace('grande', 'pequena', $value);
        return '<a target="_blank" href="' . $row->getSquidfacilUrl() . '"><img height="40" src="' . $imageLink . '"/></a>';
    }

    public function setColumn($column)
    {
        return parent::setColumn($column);
    }

}
