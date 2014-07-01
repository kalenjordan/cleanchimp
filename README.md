# Ecommerce 360 for Magento

This module sends orders over the Mailchimp API version 2.0.  

## About
It batches the orders that it handles during each cron run, so it should be able to handle a large number of orders.  The ecommerce 360 query string parameters are handled javascript-side, so it's compatible with full page caching.

## Installation

1. You can install using modman:

        modman clone git@github.com:kalenjordan/clean-mailchimp.git
        
2. To configure it, drop your API key into System > Config > Newsletter > Mailchimp Settings
    
