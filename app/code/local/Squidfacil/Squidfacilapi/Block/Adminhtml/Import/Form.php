<?php

class Squidfacil_Squidfacilapi_Block_Adminhtml_Import_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $category = Mage::getModel('catalog/category')->load();
        
        var_dump($category);

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('adminhtml')->__('Account Information')));

        $fieldset->addField('category', 'text', array(
                'name'  => 'category',
                'label' => Mage::helper('adminhtml')->__('Category'),
                'title' => Mage::helper('adminhtml')->__('Category'),
                'required' => true,
            )
        );

        $form->setValues($category->getData());
        $form->setAction($this->getUrl('*/system_account/save'));
        $form->setMethod('post');
        $form->setUseContainer(true);
        $form->setId('edit_form');

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
?>
