<?php
/**
 * Copyright © Lyra Network.
 * This file is part of PayZen plugin for OpenMage. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @copyright Lyra Network
 * @license   https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

/**
 * Custom renderer for the customer group options field.
 */
class Lyranetwork_Payzen_Block_Adminhtml_System_Config_Field_Fullcb_CustgroupOptions
    extends Lyranetwork_Payzen_Block_Adminhtml_System_Config_Field_CustgroupOptions
{
    public function __construct()
    {
        parent::__construct();

        $this->_default = array('amount_min' => '100', 'amount_max' => '1500');
    }
}
