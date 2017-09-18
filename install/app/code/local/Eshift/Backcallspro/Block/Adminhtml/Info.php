<?php
/**
 *
 * This source file is subject to the Ecommerce Shift Software License, which is available at http://ecommerceshift.com/common/license-commercial-ru.txt.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 *
 * NOTICE OF LICENSE
 *
 * You may not sell, sub-license, rent or lease
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Eshift
 * @package    Eshift_Backcallspro
 * @copyright  Copyright (c) 2013 Ecommerce Shift (http://ecommerceshift.com/)
 * @contacts   support@ecommerceshift.com
 * @author     Alexander Dashkov (dashkov1@gmail.com)
 * @license    http://ecommerceshift.com/common/license-commercial-ru.txt
 */

class Eshift_Backcallspro_Block_Adminhtml_Info
    extends Mage_Adminhtml_Block_Abstract
        implements Varien_Data_Form_Element_Renderer_Interface
{
   
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $helper = Mage::helper('backcallspro');
        
        $html = 
            '<span>Быстрый старт интернет-магазина на Magento: <a href="http://magebox.ru/" target="_blank">magebox.ru</a></span><br />
            <span>Рекомендуемые модули для дальнейшего улучшения магазина: <b><a href="http://magebox.ru/backcall-pro/" target="_blank">Обратный звонок ПРО</a> + <a href="http://magebox.ru/one-click-order/" target="_blank">Заказ в 1 КЛИК</a></b></span>
            ';

        return $html;
    }

    protected function _getConfigValue($module, $config)
    {
        $locale = Mage::app()->getLocale()->getLocaleCode();
        $defaultLocale = 'en_US';
        $mainConfig = Mage::getConfig();
        $moduleConfig = $mainConfig->getNode('modules/' . $module . '/' . $config);

        if ((string)$moduleConfig) {
            return $moduleConfig;
        }

        if ($moduleConfig->$locale) {
            return $moduleConfig->$locale;
        } else {
            return $moduleConfig->$defaultLocale;
        }
    }

}