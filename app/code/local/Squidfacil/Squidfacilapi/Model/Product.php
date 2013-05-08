<?php
class Squidfacil_Squidfacilapi_Model_Product extends Varien_Object {
    public function getSku(){
        return $this->_getData('sku');
    }
}
?>
