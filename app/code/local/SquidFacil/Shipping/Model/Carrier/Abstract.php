<?php

class SquidFacil_Shipping_Model_Carrier_Abstract extends Mage_Shipping_Model_Carrier_Abstract {
    
    protected $_rates = null;
    protected $_code;
    protected $_result;
    
    function __construct() {
        $this->_result = new Mage_Shipping_Model_Rate_Result();
    }
    

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {        
        return $this->_result;
    }
    
    public function getCode() {
        return $this->_code;
    }

    public function setCode($_code) {
        $this->_code = $_code;
    }
        
    public function getRates() {
        return $this->_rates;
    }

    public function setRate($rate) {
        $this->_result->append($rate);
    }

    public function getResult() {
        return $this->_result;
    }

    public function setResult($_result) {
        $this->_result = $_result;
    }

    public function getAllowedMethods() {
        
    }    
}
