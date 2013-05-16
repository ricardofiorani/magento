<?php

class SquidFacil_Import_Block_Adminhtml_Import_Options_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Choose Category')));

        $fieldset->addField('category', 'select', array(
                'name'  => 'category',
                'label' => Mage::helper('adminhtml')->__('Category'),
                'title' => Mage::helper('adminhtml')->__('Category'),
                'required' => true,
                'values' => $this->toOptionArray()
            )
        );
        
        $fieldset->addField('sku', 'hidden', array(
                'name'  => 'sku',
                'label' => Mage::helper('adminhtml')->__('Category'),
                'title' => Mage::helper('adminhtml')->__('Category'),
                'required' => true,
                'value' => $this->getRequest()->get('sku')
            )
        );
        
        $form->setAction($this->getUrl('*/adminhtml_import/save'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    public function toOptionArray($addEmpty = true)
    {
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addAttributeToSelect('name')->addIsActiveFilter();
        $options = array();
        if ($addEmpty) {
            $options[] = array(
                'label' => Mage::helper('adminhtml')->__('-- Please Select --'),
                'value' => ''
            );
        }
        foreach ($collection as $category) {
            if ($category->getName() != "") { // to skip blank category name
                $options[] = array(
                   'label' => $category->getName(),
                   'value' => $category->getId()
                );
            }
        }

        return $options;
    }
}
?>
