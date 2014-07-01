<?php
/**
 * @var $this Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

try {
    $installer->getConnection()
        ->addColumn($installer->getTable('sales_flat_order'), 'cleanchimp_sent_at', 'DATETIME');

    $installer->getConnection()
        ->addColumn($installer->getTable('sales_flat_order'), 'cleanchimp_error', 'VARCHAR(255)');

    $installer->getConnection()
        ->addColumn($installer->getTable('sales_flat_order'), 'cleanchimp_campaign_id', 'VARCHAR(255)');

    $installer->getConnection()
        ->addColumn($installer->getTable('sales_flat_order'), 'cleanchimp_email_id', 'VARCHAR(255)');
} catch (Exception $e) {
    Mage::logException($e);
}

