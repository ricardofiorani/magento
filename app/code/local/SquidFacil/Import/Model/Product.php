<?php
class SquidFacil_Import_Model_Product extends Varien_Object {
    public function getSku(){
        return $this->_getData('sku');
    }
    
    public function getBySku($sku){
        $parametros = array(
            'email' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_email',Mage::app()->getStore()),
            'token' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_token',Mage::app()->getStore()),
            'sku' => $sku
        );
        
        var_dump($parametros);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://squidfacil/webservice/produtos/produtos.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametros));
        $response = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        //$xml = new SimpleXMLElement($response, LIBXML_NOCDATA);
        $root = $xml->children();
        $produtos = $root[1];
        $c = 0;
        if((int)$root[0]->codigo != 0){
            throw new Exception("Invalid Credentials");
        }
        $produto = $produtos->produto;
        $this->addData(array(
                'sku' => (string)$produto->sku,
                'category' => (string)$produto->categoria,
                'title' => (string)$produto->nome,
                'stock' => (int)$produto->estoque,
                'description' =>  htmlspecialchars_decode((string)$produto->descricao),
                'short_description' => htmlspecialchars_decode((string)$produto->resumo_descricao),
                'weight' => $produto->peso+$produto->peso_embalagem,
                'price' => $produto->preco,
                'suggested_price' => $produto->preco_sugerido,
                'height' => $produto->altura_embalagem,
                'width' => $produto->largura_embalagem,
                'depth' => $produto->profundidade_embalagem,
                'image' => $produto->imagem_1
        ));
        return true;
    }
}
?>
