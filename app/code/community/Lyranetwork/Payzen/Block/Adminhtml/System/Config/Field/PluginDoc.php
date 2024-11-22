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

/**
 * Custom renderer for the plugin documentation element.
 */
class Lyranetwork_Payzen_Block_Adminhtml_System_Config_Field_PluginDoc extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Render field HTML.
     *
     * @param Varien_Data_Form_Element_Abstract $element
     *
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        // Get documentation links.
        $languages = array(
            'fr' => 'Français',
            'en' => 'English',
            'es' => 'Español',
            'pt' => 'Português'
            // Complete when other languages are managed.
        );

        $docs = "";
        foreach (PayzenApi::getOnlineDocUri() as $lang => $docUri) {
            $docs .= '<a style="margin-left: 10px; text-decoration: none; text-transform: uppercase;" href="' . $docUri . 'openmage/sitemap.html" target="_blank">' . $languages[$lang] . '</a>';
        }

        $element->setComment($docs);

        return parent::render($element);
    }
}