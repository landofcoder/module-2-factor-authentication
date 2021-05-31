<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_Authenticator
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */
namespace Lof\Authenticator\Block\Adminhtml\User\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Locale\ListsInterface;
use Magento\Framework\Registry;
use Lof\Authenticator\Lib\PHPGangsta\GoogleAuthenticator;

/**
 * Authentication edit form authentication tab
 *
 * @author     Shyam Kumar <kumar.30.shyam@gmail.com>
 */
class Authentication extends \Magento\Backend\Block\Widget\Form\Generic
{
    const CURRENT_USER_PASSWORD_FIELD = 'current_password';

    /**
     * @var Session
     */
    protected $_authSession;

    /**
     * @var ListsInterface
     */
    protected $_LocaleLists;

    /**
     * Google Authenticator
     *
     * @var GoogleAuthenticator $_googleAuthenticator
     */
    protected $_googleAuthenticator = null;

    /**
     * Google Secret
     *
     * @var string $_googleSecret
     */
    protected $_googleSecret = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Session $authSession
     * @param ListsInterface $localeLists
     * @param GoogleAuthenticator $googleAuthenticator
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Session $authSession,
        ListsInterface $localeLists,
        GoogleAuthenticator $googleAuthenticator,
        array $data = []
    ) {
        $this->_authSession = $authSession;
        $this->_LocaleLists = $localeLists;
        $this->_googleAuthenticator = $googleAuthenticator;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @return \Magento\Backend\Block\Widget\Form
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var $model \Magento\User\Model\User */
        $model = $this->_coreRegistry->registry('permissions_user');

        // Check for secret key. If doesn't exist, create a new one.
        $this->_googleSecret = $model->getSecretKey();
        if (!$this->_googleSecret) {
            $this->_googleSecret = $this->getSecretCode();
            $model->setSecretKey($this->_googleSecret);
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('user_');

        $authenticationFieldset = $form->addFieldset('authentication_fieldset', ['legend' => __('Authentication Information')]);

        $authenticationFieldset->addField(
            'secret_key',
            'text',
            [
                'name' => 'secret_key',
                'label' => __('Secret Key'),
                'id' => 'secret_key',
                'title' => __('Secret Key'),
                'required' => true
            ]
        );

        $authenticationFieldset->addField(
            'qr_code',
            'note',
            [
                'label'    => __('QR Code'),
                'title'    => __('QR Code'),
                'name'     => 'qr_code',
                'after_element_html' => '<img width="300" src="'
                . $this->getQRCodeUrl()
                . '" id="qr_code_image" />'
            ]
        );

        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Returns QR secret code
     *
     * @return string
     * @throws \Exception
     */
    protected function getSecretCode()
    {
        return $this->_googleAuthenticator->createSecret();
    }

    /**
     * Returns QR code url
     *
     * @return string
     */
    protected function getQRCodeUrl()
    {
        return $this->_googleAuthenticator->getQRCodeGoogleUrl('Lof Authenticator', $this->_googleSecret);
    }
}
