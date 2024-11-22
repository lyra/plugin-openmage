<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

use Lyranetwork\Payzen\Model\Api\Form\Api as PayzenApi;

class Lyranetwork_Payzen_Model_Field_Other_PaymentMeans extends Lyranetwork_Payzen_Model_Field_Array
{
    protected $_eventPrefix = 'payzen_field_other_payment_means';

    protected function _beforeSave()
    {
        $values = $this->getValue();
        $usedCards = array();

        if (! is_array($values) || empty($values)) {
            $this->setValue(array());
        } else {
            $i = 0;
            $means = array();
            $supportedCards = PayzenApi::getSupportedCardTypes();
            $addedCards = Mage::getModel('payzen/payment_other')->getAddedMeans();
            $cards = array_merge($supportedCards, $addedCards);
            foreach ($values as $key => $value) {
                $i++;

                if (empty($value)) {
                    continue;
                }

                if (in_array($value['means'], $usedCards)) {
                    // Do not save several options with the same means of payment.
                    $this->_throwError('Payment means', $i, Mage::helper('payzen')->__('You cannot enable several options with the same means of payment.'));
                } else {
                    $usedCards[] = $value['means'];
                }

                if (empty($value['label'])) {
                    $value['label'] = Mage::helper('payzen')->__('Payment with %s', $cards[$value['means']]);
                    $values[$key] = $value;
                }

                $this->checkAmount($value['minimum'], 'Min. amount', $i);
                $this->checkAmount($value['maximum'], 'Max. amount', $i);
                $this->checkDecimal($value['capture_delay'], 'Capture delay', $i);

                $means[$value['means']] = $value['label'];
            }

            $this->setValue($values);
            Mage::helper('payzen')->updateMeanModelConfig($means);
        }

        return parent::_beforeSave();
    }
}
