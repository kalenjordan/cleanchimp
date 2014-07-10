<?php

class Clean_Mailchimp_Model_Api_OrderAdd extends Clean_Mailchimp_Model_Api_Abstract
{
    /** @var Mage_Sales_Model_Order */
    protected $_order;

    protected function _getApiRoute()
    {
        return 'ecomm/order-add';
    }

    protected function _buildRequest()
    {
        $order = $this->getOrder();

        $orderData = array(
            'id'            => $order->getIncrementId(),
            'email'         => $order->getCustomerEmail(),
            'total'         => $order->getGrandTotal(),
            'order_date'    => $order->getCreatedAt(),
            'shipping'      => $order->getShippingAmount(),
            'tax'           => $order->getTaxAmount(),
            'store_id'      => Mage::app()->getStore()->getCode(),
            'store_name'    => Mage::app()->getStore()->getName(),
            'items'         => $this->_getItems(),
        );

        if ($this->_getCampaignId()) {
            $orderData['campaign_id'] = $this->_getCampaignId();
        }

        if ($this->_getEmailId()) {
            $orderData['email_id'] = $this->_getEmailId();
        }

        return array('order' => $orderData);
    }

    protected function _getCampaignId()
    {
        return $this->getOrder()->getData('cleanchimp_campaign_id');
    }

    // The email ID field is a specific query string parameter that mailchimp
    // passes in when ecommerce 360 is enabled.
    protected function _getEmailId()
    {
        return $this->getOrder()->getData('cleanchimp_email_id');
    }

    protected function _defaultCategoryId()
    {
        return -1;
    }

    protected function _defaultCategoryName()
    {
        return '(No Category)';
    }

    /**
     * @param $item Mage_Sales_Model_Order_Item
     * @todo Should probably optimize this, maybe join the category in the initial item query
     */
    protected function _getCategoryId($item)
    {
        $product = $item->getProduct();
        if (!$product || ! $product->getId()) {
            return $this->_defaultCategoryId();
        }

        /** @var Mage_Catalog_Model_Category $firstCategory */
        $firstCategory = $product->getCategoryCollection()->getFirstItem();
        if (! $firstCategory || ! $firstCategory->getId()) {
            return $this->_defaultCategoryId();
        }

        return $firstCategory->getId();
    }

    /**
     * @param $item Mage_Sales_Model_Order_Item
     * @todo Should probably optimize this, maybe join the category in the initial item query
     */
    protected function _getCategoryName($item)
    {
        $product = $item->getProduct();
        if (!$product || ! $product->getId()) {
            return $this->_defaultCategoryName();
        }

        /** @var Mage_Catalog_Model_Resource_Category_Collection $categories */
        $categories = $product->getCategoryCollection();
        $categories->addAttributeToSelect('name');

        /** @var Mage_Catalog_Model_Category $firstCategory */
        $firstCategory = $categories->getFirstItem();
        Mage::helper('cleanchimp')->log("Category: " . print_r($firstCategory, 1));
        if (! $firstCategory || ! $firstCategory->getId()) {
            return $this->_defaultCategoryName();
        }

        return $firstCategory->getName();
    }

    protected function _getItems()
    {
        $itemsArray = array();

        /** @var $item Mage_Sales_Model_Order_Item */
        foreach ($this->getOrder()->getAllVisibleItems() as $item) {
            $itemsArray[] = array(
                'line_num'      => $item->getid(),
                'product_id'    => $item->getProductId(),
                'sku'           => $item->getSku(),
                'product_name'  => $item->getName(),
                'category_id'   => $this->_getCategoryId($item),
                'category_name' => $this->_getCategoryName($item),
                'qty'           => $item->getQtyOrdered(),
                'cost'          => $item->getRowTotal(),
            );
        }

        return $itemsArray;
    }

    protected function _handleResponse($response)
    {
        parent::_handleResponse($response);

        if (!isset($response['complete']) || ! $response['complete']) {
            throw new Clean_Mailchimp_Exception_General("The response was expected to be 'complete' but it wasn't: " . print_r($response, 1));
        }

        return $response;
    }

    public function setOrder($order)
    {
        $this->_order = $order;
        return $this;
    }

    public function getOrder()
    {
        if (! isset($this->_order)) {
            throw new Clean_Mailchimp_Exception_General("The order has to be set on this model in order to use it");
        }

        return $this->_order;
    }
}