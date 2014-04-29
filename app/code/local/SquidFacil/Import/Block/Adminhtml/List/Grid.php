<?php

class SquidFacil_Import_Block_Adminhtml_List_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('importGrid');
        $this->setDefaultSort('sku');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setFilterVisibility(false);
        $this->setDefaultLimit(25);
    }

    protected function _prepareMassactionColumn()
    {
        $columnId = 'massaction';
        $massactionColumn = $this->getLayout()->createBlock('adminhtml/widget_grid_column')->setData(
                array(
                    'index' => $this->getMassactionIdField(),
                    'filter_index' => $this->getMassactionIdFilter(),
                    'type' => 'massaction',
                    'name' => $this->getMassactionBlock()->getFormFieldName(),
                    'align' => 'center',
                    'is_system' => true,
                    'renderer' => 'SquidFacil_Import_Block_Adminhtml_List_Renderer_Checkbox',
                )
        );

        if ($this->getNoFilterMassactionColumn()) {
            $massactionColumn->setData('filter', false);
        }

        $massactionColumn->setSelected($this->getMassactionBlock()->getSelected())
                ->setGrid($this)
                ->setId($columnId);

        $oldColumns = $this->_columns;
        $this->_columns = array();
        $this->_columns[$columnId] = $massactionColumn;
        $this->_columns = array_merge($this->_columns, $oldColumns);
        return $this;
    }

    protected function _prepareCollection()
    {
        try {
            $page = ($this->getRequest()->getParam('page')) > 0 ? $this->getRequest()->getParam('page') : 1;
            $collection = Mage::getModel('import/products', array('page' => $page));
            $this->setCollection($collection);
            parent::_prepareCollection();

            return $this;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return false;
        }
    }

    protected function _prepareColumns()
    {

        $this->addColumn('image', array(
            'header' => Mage::helper('import')->__('Image'),
            'align' => 'center',
            'width' => '50px',
            'index' => 'image',
            'renderer' => 'SquidFacil_Import_Block_Adminhtml_List_Renderer_Image',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('sku', array(
            'header' => Mage::helper('import')->__('SKU'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'sku',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('import')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('category', array(
            'header' => Mage::helper('import')->__('Category'),
            'align' => 'left',
            'index' => 'category',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('stock', array(
            'header' => Mage::helper('import')->__('Stock'),
            'align' => 'left',
            'index' => 'stock',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('importLink', array(
            'header' => Mage::helper('import')->__('Action'),
            'getter' => 'getSku',
            'renderer' => 'SquidFacil_Import_Block_Adminhtml_List_Renderer_Importer',
            'filter' => false,
            'sortable' => false,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('sku');
        $this->getMassactionBlock()->setFormFieldName('import');
        $this->getMassactionBlock()->setUseSelectAll(false);
        $this->getMassactionBlock()->addItem('import', array(
            'label' => Mage::helper('import')->__('Import'),
            'url' => $this->getUrl('*/*/massImport'),
            'confirm' => Mage::helper('import')->__('Are you sure?'),
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
//        $collection = Mage::getModel('catalog/product')->getCollection();
        return $row->getSquidfacilUrl();
//        foreach ($collection as $product) {
//            if ($product->getSku() == $row->getSku()) {
//                return false;
//            }
//        }
        //return $this->getUrl('*/adminhtml_import/', array('sku' => $row->getSku()));
    }

    public function getRowspan($item, $column)
    {
        parent::getRowspan($item, $column);
    }

}
