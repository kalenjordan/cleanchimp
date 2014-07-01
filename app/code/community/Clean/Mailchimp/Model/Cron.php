<?php

class Clean_Mailchimp_Model_Cron extends Varien_Object
{
    protected function _getExcludedOrderStates()
    {
        $states = array(
            Mage_Sales_Model_Order::STATE_CANCELED,
            Mage_Sales_Model_Order::STATE_CLOSED,
            Mage_Sales_Model_Order::STATE_HOLDED,
        );

        return $states;
    }

    /**
     * @param $observer Varien_Event_Observer
     */
    public function processOrders($observer)
    {
        if (! Mage::helper('cleanchimp')->enabled()) {
            Mage::helper('cleanchimp')->log("Not running cron because ecommerce 360 is disabled");
            return $this;
        }

        $orders = Mage::getResourceModel('sales/order_collection');
        $orders->addFieldToFilter('cleanchimp_sent_at', array('null' => true))
            ->addFieldToFilter('state', array('nin' => $this->_getExcludedOrderStates()))
            ->setOrder('entity_id', Varien_Data_Collection::SORT_ORDER_ASC)
            ->setPageSize(Mage::helper('cleanchimp')->getBatchSize());

        $orders->getSelect()
            ->where('cleanchimp_sent_at IS NULL')
            ->where('cleanchimp_error IS NULL');

        /** @var Mage_Sales_Model_Order $order */
        foreach ($orders as $order) {
            try {
                Mage::getModel('cleanchimp/api')->orderAdd($order);
                $order->setData('cleanchimp_sent_at', now());
                $order->save();
            } catch (Exception $e) {
                $order->setData('cleanchimp_error', $e->getMessage());
                $order->save();
            }
        }

        return $this;
    }
}