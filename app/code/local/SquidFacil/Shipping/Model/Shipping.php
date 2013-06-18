<?php

class SquidFacil_Shipping_Model_Shipping extends Mage_Shipping_Model_Shipping {

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        parent::collectRates($request);
        //var_dump($request);
        $parametros = array(
            'email' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_email', Mage::app()->getStore()),
            'token' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_token', Mage::app()->getStore()),
            'cep' => $request->getDestPostcode(),
        );
        $items = $request->getData('all_items');
        foreach ($items as $item) {
            $parametros['products'][] = array(
                'sku' => $item->getSku(),
                'quantity' => $item->getQty()
            );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.squidfacil.com.br/webservice/freight/freight.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));
        $response = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xml) {
            $root = $xml->children();
            foreach ($root->carrier as $externalCarrier) {
                $carrier = new SquidFacil_Shipping_Model_Carrier_Abstract();
                $carrier->setCode('squidfacil_' . strtolower($externalCarrier->name));
                foreach ($externalCarrier->service as $service) {
                    $method = new Mage_Shipping_Model_Rate_Result_Method();
                    $method->setCarrier((string) $externalCarrier->name);
                    $method->setCarrierTitle((string) $externalCarrier->name);
                    $method->setMethod(strtolower((string) $service->name));
                    $method->setMethodTitle((string) $service->name);
                    $method->setPrice((float) $service->value);
                    $carrier->setRate($method);
                }
                $this->getResult()->append($carrier->collectRates($request));
            }
        }
        return $this;
    }

    public function getResult() {
        if (empty($this->_result)) {
            $this->_result = Mage::getModel('shipping/rate_result');
        }
        return $this->_result;
    }

}

?>
