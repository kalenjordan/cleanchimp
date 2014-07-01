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

        return array(
            'order' => array(
                'id'            => $order->getIncrementId(),
                'campaign_id'   => $this->_getCampaignId(),
                'email_id'      => $this->_getEmailId(),
                'email'         => $order->getCustomerEmail(),
                'total'         => $order->getGrandTotal(),
                'order_date'    => $order->getCreatedAt(),
                'shipping'      => $order->getShippingAmount(),
                'tax'           => $order->getTaxAmount(),
                'store_id'      => Mage::app()->getStore()->getCode(),
                'store_name'    => Mage::app()->getStore()->getName(),
                'items'         => $this->_getItems(),
            )
        );
    }

    protected function _getCampaignId()
    {
        // todo get the campaign ID off of the order if available
        return null;
    }

    // The email ID field is a specific query string parameter that mailchimp
    // passes in when ecommerce 360 is enabled.
    protected function _getEmailId()
    {
        // todo get the email ID off of the cookie if available
        return null;
    }

    protected function _getCategoryId($item)
    {
        // todo grab the category off the item
        return null;
    }

    protected function _getCategoryName($item)
    {
        // todo grab the category name off the item
        return null;
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