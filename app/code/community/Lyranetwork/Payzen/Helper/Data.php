<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Helper_Data extends Mage_Core_Helper_Abstract
{
    const MODE_FORM = 1;
    const MODE_LOCAL_TYPE = 2;
    const MODE_EMBEDDED = 4;
    const MODE_POPIN = 5;
    /**
     * @var array a global var to easily enable/disable features
     */
    public static $pluginFeatures = array(
        'qualif' => false,
        'prodfaq' => true,
        'acquis' => false,
        'restrictmulti' => false,
        'shatwo' => true,
        'embedded' => true,

        'multi' => true,
        'gift' => true,
        'oney' => true,
        'fullcb' => true,
        'sepa' => true
    );

    /**
     * Shortcut method to get general module configuration.
     *
     * @param  string $field
     * @param  int    $storeId
     * @return string configuration parameter value
     */
    public function getCommonConfigData($field, $storeId = null)
    {
        if ($storeId === null && $this->isAdmin()) {
            $storeId = Mage::getSingleton('adminhtml/session_quote')->getStoreId();
        }

        $value = Mage::getStoreConfig('payment/payzen/' . $field, $storeId);
        return is_string($value) ? trim($value) : $value;
    }

    /**
     * Returns a configuration parameter from xml files.
     *
     * @param  $name the name of the parameter to retrieve
     * @return array code=>name
     */
    public function getConfigArray($name = '')
    {
        $result = array();

        $xmlNode = 'global/payment/payzen/' . $name;
        foreach (Mage::getConfig()->getNode($xmlNode)->asArray() as $xmlData) {
            $result[$xmlData['code']] = $xmlData['name'];
        }

        return $result;
    }

    /**
     * Return user's IP Address.
     *
     * @return string
     */
    public function getIpAddress()
    {
        return Mage::helper('core/http')->getRemoteAddr();
    }

    /**
     * Returns a configuration parameter from xml files.
     *
     * @param  $name the name of the parameter to retrieve
     * @return array code=>name
     */
    public function getConfigGroupTitle($group)
    {
        // Get group title.
        $config = Mage::getModel('core/config_base');
        $config->loadFile(Mage::getConfig()->getModuleDir('etc', 'Lyranetwork_Payzen') . DS . 'system.xml');
        $node = $config->getNode('sections/payment/groups/' . $group);

        return Mage::helper('payzen')->__((string) $node->label);
    }

    /**
     * Returns the complete URL matching the $url argument.
     *
     * @param string $url     openmage path
     * @param int    $storeId the ID of the store
     * @param bool   $admin   true if this is an admin call
     *
     * @return string the complete url
     */
    public function prepareUrl($url, $storeId = null, $admin = false)
    {
        $params = array();
        $params['_secure'] = Mage::app()->getStore($storeId)->isCurrentlySecure();
        $params['_nosid'] = true;

        if ($storeId !== null) {
            $params['_store'] = $storeId;
        }

        if ($admin) {
            $result = Mage::getModel('adminhtml/url')->getUrl($url, $params);
        } else {
            $result = Mage::getUrl($url, $params);
        }

        return $result;
    }

    /**
     * Return true if this is an admin session.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return Mage::app()->getStore()->isAdmin();
    }

    /**
     * Return true if SSL is activated for the current store.
     *
     * @return bool
     */
    public function isCurrentlySecure()
    {
        return Mage::app()->getStore()->isCurrentlySecure();
    }

    /**
     * Return true if Openmage shop is in maintenance mode.
     *
     * @return bool
     */
    public function isMaintenanceMode()
    {
        $maintenanceFile = Mage::getRoot() . '/maintenance.flag';
        if ($this->fileExists($maintenanceFile)) {
            return true;
        }

        return false;
    }

    /**
     * Return true if file exists. Uses Varien_Io_File API.
     *
     * @param  $fileName
     * @return bool
     */
    public function fileExists($fileName)
    {
        $io = new Varien_Io_File();
        return $io->fileExists($fileName);
    }

    /**
     * Add a model config parameter for each of given $options (multi payment options).
     *
     * @param array[int] $options
     */
    public function updateOptionModelConfig($options)
    {
        foreach ($options as $option) {
            Mage::getConfig()->saveConfig('payment/payzen_multi_' . $option . 'x/model', 'payzen/payment_multix');
        }
    }

    /**
     * Add a model config parameter for each of given $options.
     *
     * @param array $options
     */
    public function updateMeanModelConfig($options)
    {
        foreach ($options as $means => $label) {
            Mage::getConfig()->saveConfig('payment/payzen_other_' . $means . '/model', 'payzen/payment_multix');
            Mage::getConfig()->saveConfig('payment/payzen_other_' . $means . '/title', $label);
        }
    }


    /**
     * Restore active quote to checkout session.
     *
     * @param int $quoteId
     */
    public function getReviewState()
    {
        if (defined('Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW')) {
            return Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
        }
    }

    /**
     * Log function. Uses Mage::log() with built-in extra data (module version, method called...).
     *
     * @param $message
     * @param $level
     */
    public function log($message, $level = null)
    {
        if (! $this->getCommonConfigData('enable_logs')) {
            return;
        }

        $currentMethod = $this->_getCallerMethod();

        $log  = '';
        $log .= 'PayZen ' . $this->getCommonConfigData('plugin_version');
        $log .= ' - ' . $currentMethod;
        $log .= ' : ' . $message;

        Mage::log($log, $level, 'payzen.log', true);
    }

    /**
     * Find the name of the method that called the log method.
     *
     * @return the name of the method that is logging a message
     */
    protected function _getCallerMethod()
    {
        $traces = debug_backtrace();

        if (isset($traces[2])) {
            $trace = '';

            $trace .= isset($traces[2]['class']) ? ($traces[2]['class'] . '::') : '';
            $trace .= isset($traces[2]['function']) ? $traces[2]['function'] : '';
            return $trace;
        }

        return null;
    }
}
