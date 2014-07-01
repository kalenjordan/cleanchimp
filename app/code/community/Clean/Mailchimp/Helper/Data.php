<?php

class Clean_Mailchimp_Helper_Data extends Mage_Core_Helper_Data
{
    public function log($message)
    {
        Mage::log($message, null, 'cleanchimp.log');
    }

    public function getApiKey()
    {
        return Mage::getStoreConfig('newsletter/cleanchimp/api_key');
    }
}