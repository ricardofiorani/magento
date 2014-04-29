<?php

class SquidFacil_Import_Model_Product extends Varien_Object
{

    const SquidFacilUrl = 'http://squidfacil.com.br/produto/';

    public function getSku()
    {
        return $this->_getData('sku');
    }

    public function getSlug()
    {
        return $this->_getData('url_key');
    }

    public function getSquidfacilUrl()
    {
        return self::SquidFacilUrl . $this->getSlug();
    }

    public function getBySku($sku)
    {
        if (!$sku) {
            throw new Exception("Invalid Product SKU");
        }
        $email = Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_email', Mage::app()->getStore());
        $token = Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_token', Mage::app()->getStore());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.squidfacil.com.br/pt_BR/product/" . $sku);
        curl_setopt($ch, CURLOPT_USERPWD, $email . ":" . $token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $jsonResponse = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($jsonResponse);
        $c = 0;
        if ($response->status == 401 || $response->status == 403 || empty($response)) {
            throw new Exception($response->status . ' : ' . $response->detail . ' - Please check your API Token');
        }
        $product = $response;
        $this->addData(array(
            'sku' => (string) $product->sku,
            'category' => (string) $product->category,
            'title' => (string) $product->name,
            'url_key' => (string) $product->slug,
            'stock' => (int) $product->inventory,
            'description' => $this->getProductDescription($product),
            'short_description' => (string) $product->shortDescription,
            'weight' => $product->totalWeight,
            'price' => $product->price,
            'suggested_price' => $product->suggestedPrice,
            'height' => $product->totalHeight,
            'width' => $product->totalWidth,
            'length' => $product->totalLength,
            'image' => $product->image,
            'images' => $product->images,
        ));
        return $this;
    }

    public function getProductDescription($product)
    {
        $return = "";
        if (!empty($product->description)) {
            $return .= "\n» DESCRIÇÃO\n";
            $return .= $product->description . "\n";
        }
        if (!empty($product->technicalDescription)) {
            $return .= "\n» DADOS TÉCNICOS\n";
            $return .= $product->technicalDescription . "\n";
        }

        return (string) $return;
    }

}
