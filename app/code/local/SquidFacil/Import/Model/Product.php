<?php

class SquidFacil_Import_Model_Product extends Varien_Object
{

    public function getSku()
    {
        return $this->_getData('sku');
    }

    public function getBySku($sku)
    {
        if (!$sku) {
            throw new Exception("Invalid Product SKU");
        }
        $params = array(
            'email' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_email', Mage::app()->getStore()),
            'token' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_token', Mage::app()->getStore()),
            'sku' => $sku
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.squidfacil.com.br/pt_BR/product/" . $sku);
        curl_setopt($ch, CURLOPT_USERPWD, $params['email'] . ":" . $params['token']);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $jsonResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($jsonResponse);
        $c = 0;
        if ($response->status == 401 || $response->status == 403 || empty($response)) {
            throw new Exception($response->status . ' : ' . $response->detail . ' - Please check your API Token');
        }
        $produto = $response;
        $this->addData(array(
            'sku' => (string) $produto->sku,
            'category' => (string) $produto->category,
            'title' => (string) $produto->name,
            'url_key' => (string) $produto->slug,
            'stock' => (int) $produto->inventory,
            'description' => (string) $produto->description,
            'short_description' => (string) $produto->shortDescription,
            'weight' => $produto->totalWeight,
            'price' => $produto->price,
            'suggested_price' => $produto->suggestedPrice,
            'height' => $produto->totalHeight,
            'width' => $produto->totalWidth,
            'length' => $produto->totalLength,
            'image' => $produto->image
        ));
        return $this;
    }

}
