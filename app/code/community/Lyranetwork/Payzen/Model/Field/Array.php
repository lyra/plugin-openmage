<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Model_Field_Array extends Mage_Adminhtml_Model_System_Config_Backend_Serialized_Array
{
    protected function _throwError($column, $position, $extraMsg = '')
    {
        // Translate field and column names.
        $field = Mage::helper('payzen')->__((string) $this->getFieldConfig()->label);
        $column = Mage::helper('payzen')->__((string) $column);
        $group = Mage::helper('payzen')->getConfigGroupTitle($this->getGroupId());

        // Main message.
        $msg = Mage::helper('payzen')->__('The field &laquo; %s &raquo; is invalid: please check column &laquo; %s &raquo; of the option %s in section &laquo; %s &raquo;.', $field, $column, $position, $group);

        if ($extraMsg) {
            $msg .= "\n" . Mage::helper('payzen')->__($extraMsg);
        }

        // Throw exception.
        Mage::throwException($msg);
    }

    protected function checkMandatoryDecimal($value, $fieldLabel, $i)
    {
        if (empty($value)) {
            $this->_throwError($fieldLabel, $i);
        }

        $this->checkDecimal($value, $fieldLabel, $i);
    }

    protected function checkDecimal($value, $fieldLabel, $i)
    {
        if (! empty($value) && ! preg_match('#^\d+$#', $value)) {
            $this->_throwError($fieldLabel, $i);
        }
    }

    protected function checkAmount($amount, $fieldLabel, $i)
    {
        if (! empty($amount) && ! preg_match('#^\d+(\.\d+)?$#', $amount)) {
            $this->_throwError($fieldLabel, $i);
        }
    }

    protected function checkRate($rate, $fieldLabel, $i)
    {
        if (! empty($rate) && (! is_numeric($rate) || ($rate < 0) || ($rate >= 100))) {
            $this->_throwError($fieldLabel, $i);
        }
    }
}
