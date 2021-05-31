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
namespace Lof\Authenticator\Block\Adminhtml\User\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Lof\Authenticator\Helper\Data;

class Tabs extends \Magento\User\Block\User\Edit\Tabs
{
    /**
     * Authenticator Helper
     *
     * @var Data
     */
    protected $helper;

    /**
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
         Context $context,
         EncoderInterface $jsonEncoder,
         Session $authSession,
         Data $helper,
         array $data = []
     ) {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
        $this->helper = $helper;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {
        // New tab in admin user form
        if ($this->helper->isEnable()) {
            $this->addTabAfter(
                'authentication',
                [
                    'label' => __('Authentication'),
                    'title' => __('Authentication'),
                    'content' =>$this->getLayout()->createBlock('Lof\Authenticator\Block\Adminhtml\User\Edit\Tab\Authentication')->toHtml()
                ],
                'roles_section'
            );
        }

        return parent::_beforeToHtml();
    }
}
