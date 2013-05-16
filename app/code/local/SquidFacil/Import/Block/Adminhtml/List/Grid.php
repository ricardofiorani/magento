<?php

class SquidFacil_Import_Block_Adminhtml_List_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('importGrid');
        $this->setDefaultSort('sku');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection() {
        try{
            $collection = Mage::getModel('import/products');
            $this->setCollection($collection);
            return parent::_prepareCollection();
        } catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return false;
        }
    }

    protected function _prepareColumns() {
        $this->addColumn('sku', array(
            'header' => Mage::helper('import')->__('SKU'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'sku',
            'filter' => false
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('import')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'filter' => false
        ));

        $this->addColumn('category', array(
            'header' => Mage::helper('import')->__('Category'),
            'align' => 'left',
            'index' => 'category',
            'filter' => false
        ));
        
        $this->addColumn('stock', array(
            'header' => Mage::helper('import')->__('Stock'),
            'align' => 'left',
            'index' => 'stock',
            'filter' => false
        ));

        $this->addColumn('action', array(
            'header' => Mage::helper('import')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getSku',
            'actions' => array(
                array(
                    'caption' => Mage::helper('import')->__('Import'),
                    'url' => array('base' => '*/adminhtml_import/'),
                    'field' => 'sku'
                ),
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));


        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('import_sku');
        $this->getMassactionBlock()->setFormFieldName('import');

        $this->getMassactionBlock()->addItem('export', array(
            'label' => Mage::helper('import')->__('Export'),
            'url' => $this->getUrl('*/*/massExport'),
            'confirm' => Mage::helper('import')->__('Are you sure?')
        ));

        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/import', array('sku' => $row->getSku()));
    }

}