<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * @var $this Lyranetwork_Payzen_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();

$statusTable = $installer->getTable('sales/order_status');
$stateTable = $installer->getTable('sales/order_status_state');

// payzen_pending_transfer status.
$select = $connection->select()->from($statusTable, 'status')->where('status = "payzen_pending_transfer"');
if (! $connection->fetchOne($select)) { // Status does not exist.
    $connection->insert(
        $statusTable,
        array('status' => 'payzen_pending_transfer', 'label' => 'Pending funds transfer')
    );

    $connection->insert(
        $stateTable,
        array('status' => 'payzen_pending_transfer', 'state' => 'processing', 'is_default' => 0)
    );
}

// payzen_to_validate status.
$select = $connection->select()->from($statusTable, 'status')->where('status = "payzen_to_validate"');
if (! $connection->fetchOne($select)) { // Status does not exist.
    $connection->insert(
        $statusTable,
        array('status' => 'payzen_to_validate', 'label' => 'To validate payment')
    );

    $connection->insert(
        $stateTable,
        array('status' => 'payzen_to_validate', 'state' => 'payment_review', 'is_default' => 0)
    );
}

$installer->endSetup();
