<?php

class Clean_Mailchimp_Model_Api extends Varien_Object
{
    public function orderAdd($order)
    {
        $result = Mage::getModel('cleanchimp/api_orderAdd')
            ->setOrder($order)
            ->setApi($this)
            ->execute();

        return $result;
    }

    // TODO Probably need to make the 'us1' configurable
    protected function _getApiEndpoint()
    {
        return 'https://us1.api.mailchimp.com/2.0/';
    }

    public function request($route, $parameters = array())
    {
        if (! Mage::helper('cleanchimp')->getApiKey()) {
            throw new Clean_Mailchimp_Exception_General("No API Key has been setup yet");
        }

        $parameters['apikey'] = Mage::helper('cleanchimp')->getApiKey();
        $url = $this->_getApiEndpoint() . $route;

        return $this->_curlPost($url, $parameters);
    }

    protected function _curlPost($url, $body)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseJson = curl_exec($ch);
        curl_close ($ch);

        if (! $responseJson) {
            return array();
        }

        $responseArray = json_decode($responseJson, true);
        if (! $responseArray) {
            return array();
        }

        return $responseArray;
    }
}