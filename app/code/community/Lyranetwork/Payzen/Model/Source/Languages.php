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
class Lyranetwork_Payzen_Model_Source_Languages
{
    public function toOptionArray()
    {
        $options =  array();

        foreach (PayzenApi::getSupportedLanguages() as $code => $name) {
            $options[] = array (
                'value' => $code,
                'label' => Mage::helper('payzen')->__($name)
            );
        }

        return $options;
    }
}
