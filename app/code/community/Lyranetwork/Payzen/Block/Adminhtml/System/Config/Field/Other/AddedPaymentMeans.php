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
 * Custom renderer for the added payment means field.
 */
class Lyranetwork_Payzen_Block_Adminhtml_System_Config_Field_Other_AddedPaymentMeans

    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn(
            'code',
            array(
                'label' => Mage::helper('payzen')->__('Code'),
                'style' => 'width: 150px;'
            )
        );

        $this->addColumn(
            'title',
            array(
                'label' => Mage::helper('payzen')->__('Label'),
                'style' => 'width: 200px;'
            )
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('payzen')->__('Add');
        parent::__construct();
    }
}
