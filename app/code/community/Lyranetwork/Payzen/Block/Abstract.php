<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

abstract class Lyranetwork_Payzen_Block_Abstract extends Mage_Payment_Block_Form
{
    protected $_model;

    protected function _construct()
    {
        parent::_construct();

        $logoURL = $this->_checkAndGetSkinUrl($this->getConfigData('module_logo'));

        if (! $this->_getHelper()->isAdmin() && $logoURL) {
            $logo = Mage::getConfig()->getBlockClassName('core/template');
            $logo = new $logo;
            $logo->setTemplate('payzen/logo.phtml');
            $logo->setLogoSrc($logoURL);
            $logo->setMethodTitle($this->getConfigData('title'));

            // Add logo to the method title.
            $this->setMethodLabelAfterHtml($logo->toHtml());
        }
    }

    protected function _checkAndGetSkinUrl($fileName)
    {
        if (! $fileName) {
            return false;
        }

        $filePath = Mage::getBaseDir('media') . DS . 'payzen' . DS . 'logos' . DS . $fileName;
        if (! $this->_getHelper()->fileExists($filePath)) {
            return false;
        }

        return Mage::getBaseUrl('media') . 'payzen/logos/' . $fileName;
    }

    public function getCcTypeImageSrc($card)
    {
        return $this->_getHelper()->getCommonConfigData('logo_url') . strtolower($card) . '.png';
    }

    protected function _getModel()
    {
        return Mage::getModel('payzen/payment_' . $this->_model);
    }

    public function getConfigData($name)
    {
        return $this->_getModel()->getConfigData($name);
    }

    /**
     * Return payzen data helper.
     *
     * @return Lyranetwork_Payzen_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('payzen');
    }

    /**
     * Get checkout session namespace
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
}
