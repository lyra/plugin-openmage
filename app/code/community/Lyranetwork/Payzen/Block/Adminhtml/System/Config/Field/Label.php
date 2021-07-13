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
 * Custom renderer for the label field.
 */
class Lyranetwork_Payzen_Block_Adminhtml_System_Config_Field_Label extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Unset some non-related element parameters.
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();

        $fieldConfig = $element->getFieldConfig();
        if (isset($fieldConfig->is_country) && (bool) $fieldConfig->is_country) {
            $name = Mage::app()->getLocale()->getCountryTranslation($element->getValue());
            if (! empty($name)) {
                $element->setValue($name);
            }
        }

        return parent::render($element);
    }
}
