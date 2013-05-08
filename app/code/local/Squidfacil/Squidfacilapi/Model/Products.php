<?php
class Squidfacil_Squidfacilapi_Model_Products extends Varien_Data_Collection
{
    protected $products;
    
    public function __construct(){
        $parametros = array(
            'email' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_email',Mage::app()->getStore()),
            'token' => Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_token',Mage::app()->getStore()),
//            'limite' => $pagesize,
//            'pagina' => $pagenumber
        );

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
        $this->products = $produtos;
    }
    
    public function loadData($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }

        // calculate totals
        $this->_totalRecords = $this->products->count();
        $this->_setIsLoaded();

        // paginate and add items
        $from = ($this->getCurPage() - 1) * $this->getPageSize();
        $to = $from + $this->getPageSize() - 1;
        $isPaginated = $this->getPageSize() > 0;

        $cnt = 0;
        $sku_list = array();
        $collection = Mage::getModel('catalog/product')->getCollection();
        foreach($collection as $product){
            $sku_list[] = $product->getSku();
        }

        foreach ($this->products as $produto) {
            if(in_array($produto->sku, $sku_list)){
                continue;
            }
            $cnt++;
            if ($isPaginated && ($cnt < $from || $cnt > $to)) {
                continue;
            }
            $object = new Squidfacil_Squidfacilapi_Model_Product();
            $object->addData(array(
                'sku' => (string)$produto->sku,
                'category' => (string)$produto->categoria,
                'title' => (string)$produto->nome,
                'stock' => (int)$produto->estoque,
                'description' => (string)$produto->descricao,
                'short_description' => 'vazio',
                'weight' => $produto->peso+$produto->peso_embalagem,
                'price' => $produto->preco,
                'suggested_price' => $produto->preco_sugerido,
                'height' => $produto->altura_embalagem,
                'width' => $produto->largura_embalagem,
                'depth' => $produto->profundidade_embalagem,
                'image' => $produto->imagem_1
            ));
            $this->addItem($object);
        }

        return $this;
    }
    
    public function addFieldToFilter($field, $cond, $type = 'and'){
        if (!is_array($cond)) {
            return $this->addCallbackFilter($field, $cond, $type, array($this, 'filterCallbackEq'));
        }
    }
    

    /**
     * Set a custom filter with callback
     * The callback must take 3 params:
     *     string $field       - field key,
     *     mixed  $filterValue - value to filter by,
     *     array  $row         - a generated row (before generaring varien objects)
     *
     * @param string $field
     * @param mixed $value
     * @param string $type 'and'|'or'
     * @param callback $callback
     * @param bool $isInverted
     * @return Varien_Data_Collection_Filesystem
     */
    public function addCallbackFilter($field, $value, $type, $callback, $isInverted = false)
    {
        $this->_filters[$this->_filterIncrement] = array(
            'field'       => $field,
            'value'       => $value,
            'is_and'      => 'and' === $type,
            'callback'    => $callback,
            'is_inverted' => $isInverted
        );
        $this->_filterIncrement++;
        return $this;
    }
    
    /**
     * Callback method for 'eq' fancy filter
     *
     * @param string $field
     * @param mixed $filterValue
     * @param array $row
     * @return bool
     * @see addFieldToFilter()
     * @see addCallbackFilter()
     */
    public function filterCallbackEq($field, $filterValue, $row)
    {
        return $filterValue == $row[$field];
    }
}
?>
