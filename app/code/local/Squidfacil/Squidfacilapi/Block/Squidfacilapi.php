<?php
class Squidfacil_Squidfacilapi_Block_Squidfacilapi extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getSquidfacilapi()     
     { 
        if (!$this->hasData('squidfacilapi')) {
            $this->setData('squidfacilapi', Mage::registry('squidfacilapi'));
        }
        return $this->getData('squidfacilapi');
        
    }
}