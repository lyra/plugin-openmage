<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Model_Field_Fullcb_PaymentOptions extends Lyranetwork_Payzen_Model_Field_Array
{
    protected $_eventPrefix = 'payzen_field_fullcb_payment_options';

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

                if (empty($value['label'])) {
                    $this->_throwError('Label', $i);
                }

                $this->checkAmount($value['amount_min'], 'Minimum amount', $i);
                $this->checkAmount($value['amount_max'], 'Maximum amount', $i);
                $this->checkRate($value['rate'], 'Rate', $i);
                $this->checkAmount($value['cap'], 'Cap', $i);
            }
        }

        return parent::_beforeSave();
    }
}
