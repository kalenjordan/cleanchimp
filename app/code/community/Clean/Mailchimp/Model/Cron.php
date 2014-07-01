<?php

class Clean_Mailchimp_Model_Cron extends Varien_Object
{
    /**
     * @param $observer Varien_Event_Observer
     */
    public function processOrders($observer)
    {
        // todo this is dummy - replace this with a loop over actual orders.
        $order = Mage::getModel('sales/order')->load(45336);
        Mage::getModel('cleanchimp/api')->orderAdd($order);
    }
}