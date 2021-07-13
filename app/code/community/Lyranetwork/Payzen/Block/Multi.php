<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Lyranetwork_Payzen_Block_Multi extends Lyranetwork_Payzen_Block_Abstract
{
    protected $_model = 'multi';

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payzen/multi.phtml');
    }

    public function getAvailableOptions()
    {
        $amount = $this->getMethod()->getInfoInstance()->getQuote()->getBaseGrandTotal();
        return $this->_getModel()->getAvailableOptions($amount);
    }

    public function getAvailableCcTypes()
    {
        return $this->_getModel()->getAvailableCcTypes();
    }
}
