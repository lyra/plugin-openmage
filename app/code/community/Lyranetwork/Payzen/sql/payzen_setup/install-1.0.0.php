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

// PayZen identifier.
$installer->addAttribute(
    'customer',
    'payzen_identifier',
    array(
        'type' => 'varchar',
        'input' => 'text',
        'label' => 'PayZen identifier',

        'global' => 1,
        'visible' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'required' => 0,
        'user_defined' => 0,
        'default' => '',
        'source' => null
    )
);

$entityTypeId     = $installer->getEntityTypeId('customer');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'payzen_identifier',
    '999' // Sort order
);

// PayZen masked card.
$installer->addAttribute(
    'customer',
    'payzen_masked_card',
    array(
        'type' => 'varchar',
        'input' => 'text',
        'label' => 'PayZen masked card',

        'global' => 1,
        'visible' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'required' => 0,
        'user_defined' => 0,
        'default' => '',
        'source' => null
    )
);

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'payzen_masked_card',
    '999' // Sort order.
);

// SEPA identifier.
$installer->addAttribute(
    'customer',
    'payzen_sepa_identifier',
    array(
        'type' => 'varchar',
        'input' => 'text',
        'label' => 'PayZen SEPA identifier',

        'global' => 1,
        'visible' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'required' => 0,
        'user_defined' => 0,
        'default' => '',
        'source' => null
    )
);

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'payzen_sepa_identifier',
    '999' // Sort order
);

// SEPA IBAN.
$installer->addAttribute(
    'customer',
    'payzen_sepa_iban',
    array(
        'type' => 'varchar',
        'input' => 'text',
        'label' => 'PayZen masked iban',

        'global' => 1,
        'visible' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'required' => 0,
        'user_defined' => 0,
        'default' => '',
        'source' => null
    )
);

$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'payzen_sepa_iban',
    '999' // Sort order.
);
