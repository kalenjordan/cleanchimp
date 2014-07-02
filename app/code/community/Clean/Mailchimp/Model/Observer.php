<?php

class Clean_Mailchimp_Model_Observer extends Varien_Object
{
    /**
     * @param $observer Varien_Event_Observer
     */
    public function salesOrderSaveBefore($observer)
    {
        /** @var Mage_Sales_Model_Order $order */
        $order = $observer->getData('order');
        if (! $order || ! ($order instanceof Mage_Sales_Model_Order)) {
            return $this;
        }

        if (isset($_COOKIE['cleanchimp_campaign_id']) && $_COOKIE['cleanchimp_campaign_id']) {
            $order->setdata('cleanchimp_campaign_id', $_COOKIE['cleanchimp_campaign_id']);
        }

        if (isset($_COOKIE['cleanchimp_email_id']) && $_COOKIE['cleanchimp_email_id']) {
            $order->setdata('cleanchimp_email_id', $_COOKIE['cleanchimp_email_id']);
        }

        return $this;
    }
}