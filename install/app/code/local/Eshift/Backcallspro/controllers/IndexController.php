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

/**
 * Backcalls index controller
 *
 * @category   Mage
 * @package    Eshift_Backcallspro
 * @author     Ecommerce Shift <support@ecommerceshift.ru>
 */

class Eshift_Backcallspro_IndexController extends Mage_Core_Controller_Front_Action
{

    const XML_PATH_EMAIL_RECIPIENT  = 'backcallspro/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'backcallspro/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'backcallspro/email/email_template';
    const XML_PATH_ENABLED          = 'backcallspro/backcallspro/enabled';
    const XML_PATH_NAME_ENABLED     = 'backcallspro/form/name_enabled';
    const XML_PATH_TIME_ENABLED     = 'backcallspro/form/time_enabled';
    const XML_PATH_COMMENT_ENABLED  = 'backcallspro/form/comment_enabled';
    const XML_PATH_SUCCESS_MESSAGE = 'backcallspro/form/success_message';
    const XML_PATH_FAIL_MESSAGE = 'backcallspro/form/fail_message';
    const XML_PATH_SENDER  = 'sender_email_identity';
    const XML_PATH_SMS_ENABLED  = 'backcallspro/smsnotification/enabled';
    const XML_PATH_SMS_PHONE  = 'backcallspro/smsnotification/phone';
    const XML_PATH_SMS_HEADER  = 'backcallspro/smsnotification/sms_header';
    const XML_PATH_SMS_SEND_TIME  = 'backcallspro/smsnotification/send_time';
    const XML_PATH_SMS_SEND_COMMENT  = 'backcallspro/smsnotification/send_comment';
    

        
    public function preDispatch()
    {
        parent::preDispatch();

        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
            $this->norouteAction();
        }
    }
    
    public function isTimeEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_TIME_ENABLED);
    }
    
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('backcallForm')
            ->setFormAction( Mage::getUrl('*/*/post') );
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
    
    public function postAction()
    {
        $post = $this->getRequest()->getPost();
        $response = array();

        if ( $post && Mage::helper('escommon')->isUserEmailSet()) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $postObject->setRemoteIp(Mage::helper('core/http')->getRemoteAddr());

                $error = false;

                if (Mage::getStoreConfig(self::XML_PATH_NAME_ENABLED)): 
                    if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                        $error = true;
                    }
                endif;
                 
                if (!Zend_Validate::is(trim($post['telephone']) , 'NotEmpty')) {
                    $error = true;
                }
               
                if (Mage::getStoreConfig(self::XML_PATH_TIME_ENABLED)):            
                    if (!Zend_Validate::is(trim($post['time']) , 'NotEmpty')) {
                       $error = true;
                   }
                endif;

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                 $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_SENDER))
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }
                
                
                /*
                 * Setup SMS message text
                 */
                if (Mage::getStoreConfig(self::XML_PATH_SMS_ENABLED) && Mage::helper('core')->isModuleEnabled('Eshift_Smssend')):

                    $sms_text = Mage::getStoreConfig(self::XML_PATH_SMS_HEADER);
                    $sms_text .= $post['telephone'];
                    
                    if (Mage::getStoreConfig(self::XML_PATH_NAME_ENABLED)):
                        $sms_text .= ' '.$post['name'];
                    endif;
                    
                    if (Mage::getStoreConfig(self::XML_PATH_SMS_SEND_TIME)):
                        $sms_text .= ' '.$post['time'];
                    endif;
                                      
                    if (Mage::getStoreConfig(self::XML_PATH_SMS_SEND_COMMENT)):
                        $sms_text .= ' '.$post['comment'];
                    endif;

                    /**
                     * Sending SMS
                     */

                    $sms = Mage::getModel('smssend/smssend');
                    $sms->setSmsText($sms_text);
                    $sms->setSmsReceiver(Mage::getStoreConfig(self::XML_PATH_SMS_PHONE));
                    $sms->sendSms();
                    
                endif;
                
                $translate->setTranslateInline(true);

                // $message = Mage::getStoreConfig(self::XML_PATH_SUCCESS_MESSAGE);
                Mage::getSingleton('customer/session')->addSuccess(Mage::getStoreConfig(self::XML_PATH_SUCCESS_MESSAGE));
                $this->loadLayout();
                $this->_initLayoutMessages('customer/session');
                $message = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $response['status'] = 'SUCCESS';
                $response['message'] = $message;

                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                // $message = Mage::getStoreConfig(self::XML_PATH_FAIL_MESSAGE);
                Mage::getSingleton('customer/session')->addError(Mage::getStoreConfig(self::XML_PATH_FAIL_MESSAGE));
                $this->loadLayout();
                $this->_initLayoutMessages('customer/session');
                $message = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
                $response['status'] = 'ERROR';
                $response['message'] = $message;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                return;
            }

        } else {
            Mage::getSingleton('customer/session')->addError(Mage::getStoreConfig(self::XML_PATH_FAIL_MESSAGE));
            $this->loadLayout();
            $this->_initLayoutMessages('customer/session');
            $message = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
            $response['status'] = 'ERROR';
            $response['message'] = $message;
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
    }
    

}
