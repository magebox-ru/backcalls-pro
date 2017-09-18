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

class Eshift_Backcallspro_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_ENABLED   = 'backcallspro/backcallspro/enabled';
    const XML_PATH_NAME_ENABLED     = 'backcallspro/form/name_enabled';
    const XML_PATH_TIME_ENABLED     = 'backcallspro/form/time_enabled';
    const XML_PATH_COMMENT_ENABLED  = 'backcallspro/form/comment_enabled';
    const XML_PATH_PHONE_BIG  = 'backcallspro/button/phone_big1';
    const XML_PATH_PHONE_SMALL1  = 'backcallspro/button/phone_small1';
    const XML_PATH_JQUERY_POPUP_WIDTH  = 'backcallspro/popup/popup_width';
    const XML_PATH_JQUERY_POPUP_HEIGHT  = 'backcallspro/popup/popup_height';
    const XML_PATH_BUTTON_BTN_TYPE  = 'backcallspro/button/btn_type';
    const XML_PATH_BUTTON_BTN_TEXT  = 'backcallspro/button/btn_text';
    const XML_PATH_BUTTON_FREE_POS  = 'backcallspro/button/btn_free_position';
    const XML_PATH_BUTTON_HEADER_POS  = 'backcallspro/button/btn_header_position';
    const XML_PATH_BUTTON_LEFT_POS  = 'backcallspro/button/btn_left_position';
    const XML_PATH_BUTTON_RIGHT_POS  = 'backcallspro/button/btn_right_position';
    const XML_PATH_BUTTON_CUSTOM_CSS  = 'backcallspro/button/btn_custom_css';
    const XML_PATH_FORM_TITLE  = 'backcallspro/form/title';

    public function isEnabled()
    {
        return Mage::getStoreConfig( self::XML_PATH_ENABLED );
    }

    public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }

    public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }
    
     public function isNameEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_NAME_ENABLED);
    }
    
    public function isTimeEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_TIME_ENABLED);
    }
    
    public function isCommentEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_COMMENT_ENABLED);
    }

    public function getBtnType()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUTTON_BTN_TYPE);
    }

    public function getBtnText()
    {
        return $this->__(Mage::getStoreConfig(self::XML_PATH_BUTTON_BTN_TEXT));
    }
    
    public function showFormTitle()
    {
        return $this->__(Mage::getStoreConfig(self::XML_PATH_FORM_TITLE));
    }
    
    public function isInsertManually()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUTTON_FREE_POS);
    }
    
    public function isInsertHeader()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_BUTTON_HEADER_POS);
    }
    
    public function isInsertLeft()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_BUTTON_LEFT_POS);
    }
    
    public function isInsertRight()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_BUTTON_RIGHT_POS);
    }
    
    public function getCustomCss()
    {
        return Mage::getStoreConfig(self::XML_PATH_BUTTON_CUSTOM_CSS);
    }
    
    public function getPopupWidth()
    {
        return Mage::getStoreConfig(self::XML_PATH_JQUERY_POPUP_WIDTH);
    }
    
    public function getPopupHeight()
    {
        return Mage::getStoreConfig(self::XML_PATH_JQUERY_POPUP_HEIGHT);
    }
    
    public function getPhoneBig($i = 1)
    {
        return Mage::getStoreConfig('backcallspro/button/phone_big'.$i);
    }

    public function getPhoneSmall($i = 1)
    {
        return Mage::getStoreConfig('backcallspro/button/phone_small'.$i);
    }

}
