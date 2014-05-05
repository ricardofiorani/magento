<?php

class SquidFacil_Shipping_Model_Quote_Address_Rate extends Mage_Sales_Model_Quote_Address_Rate {
    public function getCarrierInstance()
    {
        if(Mage::getModel('shipping/config')->getCarrierInstance($code)){
            $code = $this->getCarrier();
            if (!isset(self::$_instances[$code])) {
                self::$_instances[$code] = Mage::getModel('shipping/config')->getCarrierInstance($code);            
            }
            return self::$_instances[$code];
        } else {
            $code = $this->getCarrier();
            if (!isset(self::$_instances[$code])) {        
                self::$_instances[$code] = new SquidFacil_Shipping_Model_Carrier_Abstract();            
            }
            return self::$_instances[$code];
        }
    }
}
