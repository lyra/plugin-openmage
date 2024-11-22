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

class Lyranetwork_Payzen_Helper_Refund extends Mage_Core_Helper_Abstract implements \Lyranetwork\Payzen\Model\Api\Refund\Processor
{
    protected $payment;

    public function setPayment($payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * Action to do in case of error during refund process.
     *
     */
    public function doOnError($errorCode, $message)
    {
        if ($errorCode === 'PSP_100') {
            // Merchant has not subscribed to REST WS option, let Magento refund payment offline.
            $notice = $this->translate('You are not authorized to do this action online. Please, do not forget to update payment in PayZen Back Office.');
            $this->_getAdminSession()->addWarning($notice);
        } else {
            $this->_getAdminSession()->addWarning($message);
        }
    }

    public function doOnSuccess($operationResponse, $operationType)
    {
        $order = Mage::getModel('sales/order');
        $orderId = $operationResponse['orderDetails']['orderId'];
        $order->loadByIncrementId($orderId);

        if ($operationType == 'refund') { // Actual refund.
            $this->_createRefundTransaction($this->payment, $operationResponse);
        } elseif ($operationType == 'cancel') { // Cancellation.
            $order->cancel();
        }
    }

    protected function _createRefundTransaction($payment, $refundResponse)
    {
        $response = $this->_getRestHelper()->convertRestResult($refundResponse, true);

        // Save transaction details to sales_payment_transaction.
        $transactionId = $response['vads_trans_id'] . '-' . $response['vads_sequence_number'];

        $expiry = '';
        if ($response['vads_expiry_month'] && $response['vads_expiry_year']) {
            $expiry = str_pad($response['vads_expiry_month'], 2, '0', STR_PAD_LEFT) . ' / ' .
                $response['vads_expiry_year'];
        }

        // Save paid amount.
        $currency = PayzenApi::findCurrencyByNumCode($response['vads_currency']);
        $amount = number_format($currency->convertAmountToFloat($response['vads_amount']), $currency->getDecimals(), ',', ' ');

        $amountDetail = $amount . ' ' . $currency->getAlpha3();

        if ($response['vads_effective_currency'] &&
            ($response['vads_currency'] !== $response['vads_effective_currency'])) {
            $effectiveCurrency = _PayzenApi::findCurrencyByNumCode($response['vads_effective_currency']);

            $effectiveAmount = number_format(
                $effectiveCurrency->convertAmountToFloat($response['vads_effective_amount']),
                $effectiveCurrency->getDecimals(),
                ',',
                ' '
            );

            $amountDetail = $effectiveAmount . ' ' . $effectiveCurrency->getAlpha3() . ' (' . $amountDetail . ')';
        }

        $additionalInfo = array(
            'Transaction Type' => 'CREDIT',
            'Amount' => $amountDetail,
            'Transaction ID' => $transactionId,
            'Transaction UUID' => $response['vads_trans_uuid'],
            'Transaction Status' => $response['vads_trans_status'],
            'Means of payment' => $response['vads_card_brand'],
            'Card Number' => $response['vads_card_number'],
            'Expiration Date' => $expiry
        );

        $transactionType = Mage_Sales_Model_Order_Payment_Transaction::TYPE_REFUND;
        $this->_getPaymentHelper()->addTransaction($payment, $transactionType, $transactionId, $additionalInfo);
    }

    /**
     * Action to do after failed refund process.
     *
     */
    public function doOnFailure($errorCode, $message)
    {
        Mage::throwException($message, $errorCode);
    }

    /**
     * Translate given message.
     *
     */
    public function translate($message)
    {
        return $this->_getHelper()->__($message);
    }

    public function log($message, $level = Zend_Log::INFO)
    {
        $this->_getHelper()->log($message, $level);
    }

    protected function _getRestHelper()
    {
        return Mage::helper('payzen/rest');
    }

    protected function _getPaymentHelper()
    {
        return Mage::helper('payzen/payment');
    }
    protected function _getHelper()
    {
        return Mage::helper('payzen');
    }

    protected function _getAdminSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}