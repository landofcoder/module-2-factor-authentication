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
namespace Lof\Authenticator\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\LayoutFactory;

class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * Layout Factory
     *
     * @var LayoutFactory $layoutFactory
     */
    protected $_layoutFactory = null;

    /**
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $layoutFactory
    ) {
        $this->_layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    /**
     * Varify QR code
     *
     * @return void
     * @throws \Exception
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();
        if (!$post) {
            $this->_redirect('*/*/');
            return;
        }

        $authenticator = $this->_layoutFactory->create()->createBlock('Lof\Authenticator\Block\Authenticator');
        if ($authenticator->authenticateQRCode($post['secret'], $post['code'])) {
            $this->messageManager->addSuccess(
                __('Successfully Authenticated. Cheers...')
            );
        } else {
            $this->messageManager->addError(
                __('Invalide Authentication Code!!!')
            );
        }
        $this->_redirect('authenticator/index');
        return;
    }
}
