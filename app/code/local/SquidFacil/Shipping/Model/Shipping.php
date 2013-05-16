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
            $parametros['produtos'][] = array(
                'sku' => $item->getSku(),
                'quantidade' => $item->getQty()
            );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.squidfacil.com.br/webservice/frete/frete.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));
        $response = curl_exec($ch);
        curl_close($ch);

        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        $root = $xml->children();
        $transportadoras = $root[1];
        foreach ($transportadoras as $transportadora) {
            $carrier = new SquidFacil_Shipping_Model_Carrier_Abstract();
            $carrier->setCode('squidfacil_'.strtolower($transportadora->nome));
            foreach($transportadora->servicos->children() as $service){
                //print_r($service);
                $method = new Mage_Shipping_Model_Rate_Result_Method();
                $method->setCarrier((string)$transportadora->nome);
                $method->setCarrierTitle((string)$transportadora->nome);
                $method->setMethod(strtolower((string)$service->nome));
                $method->setMethodTitle((string)$service->nome);
                $method->setPrice((float)$service->valor);
                $carrier->setRate($method);
            }
            $this->getResult()->append($carrier->collectRates($request));
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
