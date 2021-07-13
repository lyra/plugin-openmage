<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Model_Field_ShopUrl extends Mage_Core_Model_Config_Data
{
    public function save()
    {
        $value = $this->getValue();

        if (! empty($value) && ! preg_match('|^https?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $value)) {
            $field = Mage::helper('payzen')->__((string) $this->getFieldConfig()->label);
            $group = Mage::helper('payzen')->getConfigGroupTitle($this->getGroupId());

            $msg = sprintf(Mage::helper('payzen')->__("Invalid value for field &laquo; %s &raquo; in section &laquo; %s &raquo;."), $field, $group);
            Mage::throwException($msg);
        }

        return parent::save();
    }
}
