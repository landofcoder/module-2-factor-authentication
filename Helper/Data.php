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
namespace Lof\Authenticator\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    const XML_PATH_AUTHENTICATOR_ENABLE = 'authenticator/general/enable';

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns System Config value
     *
     * @param string System Config XML
     * @param int Store Id
     *
     * @return string
     */
    private function getConfigValue($field, $storeId = null)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(
            $field,
            $storeScope,
            $storeId
        );
    }

    /**
     * Check if authenticator is enable
     *
     * @param int Store Id
     *
     * @return string
     */
    public function isEnable($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_AUTHENTICATOR_ENABLE, $storeId);
    }
}
