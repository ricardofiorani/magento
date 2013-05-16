<?php
class Squidfacil_Import_Model_Product extends Varien_Object {
    public function getSku(){
        return $this->_getData('sku');
    }
}
?>
