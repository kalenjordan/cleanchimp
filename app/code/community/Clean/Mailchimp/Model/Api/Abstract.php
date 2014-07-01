<?php

abstract class Clean_Mailchimp_Model_Api_Abstract extends Varien_Object
{
    /** @var Clean_Mailchimp_Model_Api */
    protected $_api;

    abstract protected function _getApiRoute();
    abstract protected function _buildRequest();

    public function setApi($api)
    {
        $this->_api = $api;
        return $this;
    }

    public function getApi()
    {
        return $this->_api;
    }

    public function execute()
    {
        $route = $this->_getApiRoute();
        $request = $this->_buildRequest();
        $response = $this->getApi()->request($route, $request);

        return $this->_handleResponse($response);
    }

    protected function _handleResponse($response)
    {
        if (empty($response)) {
            throw new Clean_Mailchimp_Exception_General("The mailchimp API response is empty, there was an unknown problem");
        }

        if (isset($response['status']) && $response['status'] == 'error') {
            $name = isset($response['name']) ? $response['name'] : "(No Exception Name)";
            $details = isset($response['error']) ? $response['error'] : "(No Exception Details)";
            $exceptionMessage = "$name: $details";
            throw new Clean_Mailchimp_Exception_General($exceptionMessage);
        }

        return $response;
    }
}