<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Model_Field_CustgroupOptions extends Lyranetwork_Payzen_Model_Field_Array
{
    protected $_eventPrefix = 'payzen_field_custgroup_options';

    protected function _beforeSave()
    {
        $values = $this->getValue();

        if (! is_array($values) || empty($values)) {
            $this->setValue(array());
        } else {
            $i = 0;
            foreach ($values as $value) {
                $i++;

                if (empty($value)) {
                    continue;
                }

                if (! empty($value['amount_min']) && (! is_numeric($value['amount_min']) || $value['amount_min'] < 0)) {
                    $this->_throwError('Minimum amount', $i);
                } elseif (! empty($value['amount_max']) && (! is_numeric($value['amount_max']) || $value['amount_max'] < 0)) {
                    $this->_throwError('Maximum amount', $i);
                }
            }
        }

        return parent::_beforeSave();
    }
}
