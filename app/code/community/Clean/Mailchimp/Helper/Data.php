<?php

class Clean_Mailchimp_Helper_Data extends Mage_Core_Helper_Data
{
    public function log($message)
    {
        Mage::log($message, null, 'cleanchimp.log');
    }

    public function logIfEnabled($message)
    {
        if ($this->enableLogging()) {
            Mage::log($message, null, 'cleanchimp.log');
        }
    }

    public function getApiKey()
    {
        return Mage::getStoreConfig('newsletter/cleanchimp/api_key');
    }

    public function enabled()
    {
        return Mage::getStoreConfig('newsletter/cleanchimp/enabled');
    }

    public function enableLogging()
    {
        return Mage::getStoreConfig('newsletter/cleanchimp/enable_logging');
    }
}