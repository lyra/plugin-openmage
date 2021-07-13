<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Model_Field_Other_AddedPaymentMeans extends Lyranetwork_Payzen_Model_Field_Array
{
    protected $_eventPrefix = 'payzen_field_added_payment_means';

    protected function _beforeSave()
    {
        $values = $this->getValue();
        $usedCards = Lyranetwork_Payzen_Model_Api_Api::getSupportedCardTypes();

        if (! is_array($values) || empty($values)) {
            $this->setValue(array());
        } else {
            foreach ($values as $key => $value) {
                $code = trim($value['code']);
                $title = $value['title'];
                if (empty($code)
                    || ! preg_match('#^[A-Za-z0-9\-_]+$#', $code)
                    || empty($title)
                    || ! preg_match('#^[^<>]*$#', $title)
                    || in_array($code, $usedCards)) {
                        // Invalid format of code or title, delete this means of payment.
                        unset($values[$key]);
                    } else {
                        $usedCards[] = $code;
                        // Update payment means code (to apply trim).
                        $values[$key]['code'] = $code;
                    }
            }

            // Update payment_means (options containing deleted payment means should not appear).
            $otherPaymentMeans = unserialize(Mage::getModel('payzen/payment_other')->getConfigData('payment_means'));

            $update = false;
            if (is_array($otherPaymentMeans) && ! empty($otherPaymentMeans)) {
                foreach ($otherPaymentMeans as $key => $option) {
                    if (! in_array($option['means'], array_keys($usedCards))) {
                        unset($otherPaymentMeans[$key]);
                        $update = true;
                    }
                }

                if ($update) {
                    Mage::getConfig()->saveConfig('payment/payzen_other/payment_means', serialize($otherPaymentMeans));
                }
            }

            $this->setValue($values);
        }

        return parent::_beforeSave();
    }
}
