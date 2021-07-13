<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Block_Oney3x4x extends Lyranetwork_Payzen_Block_Abstract
{
    protected $_model = 'oney3x4x';

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payzen/oney3x4x.phtml');
    }

    public function getPaymentOptions()
    {
        $amount = $this->getMethod()->getInfoInstance()->getQuote()->getBaseGrandTotal();
        return $this->_getModel()->getPaymentOptions($amount);
    }
}
