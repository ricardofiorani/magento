<?php

class SquidFacil_Import_Model_Products extends Varien_Data_Collection
{

    protected $products;

    public function __construct($param)
    {
        $this->_curPage = (int) $param['page'];

        $sku_list = array();
        $collection = Mage::getModel('catalog/product')->getCollection();
        foreach ($collection as $product) {
            $sku_list[] = $product->getSku();
        }

        $username = Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_email', Mage::app()->getStore());
        $password = Mage::getStoreConfig('squidfacil/squidfacil_group/squidfacil_token', Mage::app()->getStore());

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://api.squidfacil.com.br/pt_BR/product?page=" . $this->_curPage);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $jsonResponse = curl_exec($ch);
        $response = json_decode($jsonResponse);
        if ($jsonResponse === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        if ($response->status == 401 || $response->status == 403 || empty($response)) {
            throw new Exception($response->status . ' : ' . $response->detail . ' - Please check your API Token');
        }
        curl_close($ch);

        $produtos = $response->_embedded->product;
        $this->products = $produtos;
        $this->_totalRecords = $response->total_items;
        $this->_pageSize = $response->page_size;
    }

    public function loadData($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }

        $this->_setIsLoaded();
        foreach ($this->products as $produto) {
            $object = new SquidFacil_Import_Model_Product();
            $object->addData(array(
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
            $this->addItem($object);
        }
        return $this;
    }

    public function addFieldToFilter($field, $cond, $type = 'and')
    {
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
            'field' => $field,
            'value' => $value,
            'is_and' => 'and' === $type,
            'callback' => $callback,
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

    public function debug($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        die();
    }

}
