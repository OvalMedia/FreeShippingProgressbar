<?php
declare(strict_types=1);
namespace OM\FreeShippingProgressBar\Service;

use \Magento\Store\Model\ScopeInterface;
use \Magento\Sales\Model\Order\Shipment;

class Config
{
    const XML_PATH_ENABLED = 'om_freeshipping_progress_bar/general/enable';
    const XML_PATH_CUSTOMER_GROUPS = 'om_freeshipping_progress_bar/general/customer_groups';
    const XML_PATH_MIN_TOTAL = 'om_freeshipping_progress_bar/general/min_total';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected \Magento\Framework\App\Config\ScopeConfigInterface $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->_scopeConfig->getValue(self::XML_PATH_ENABLED);
    }

    /**
     * Get the default shipping origin
     *
     * @return string
     */
    public function getShippingOriginCountryId(): string
    {
        return $this->_scopeConfig->getValue(Shipment::XML_PATH_STORE_COUNTRY_ID, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return float
     */
    public function getMinTotal(): float
    {
        return (float) $this->_scopeConfig->getValue(self::XML_PATH_MIN_TOTAL);
    }

    /**
     * @return array
     */
    public function getCustomerGroups(): array
    {
        $groups = [];

        if ($groups = $this->_scopeConfig->getValue(self::XML_PATH_CUSTOMER_GROUPS)) {
            $groups = explode(',', $groups);
        }

        return $groups;
    }
}