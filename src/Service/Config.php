<?php
declare(strict_types=1);
namespace OM\FreeShippingProgressBar\Service;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\ScopeInterface;
use \Magento\Sales\Model\Order\Shipment;

class Config
{
    const XML_PATH_ENABLED = 'om_freeshipping_progress_bar/general/enable';
    const XML_PATH_CUSTOMER_GROUPS = 'om_freeshipping_progress_bar/general/customer_groups';
    const XML_PATH_MIN_TOTAL = 'om_freeshipping_progress_bar/general/min_total';
    const XML_PATH_MIN_TOTAL_BY_COUNTRY = 'om_freeshipping_progress_bar/general/min_total_by_country';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected ScopeConfigInterface $_scopeConfig;

    /**
     * @var
     */
    protected $_minTotals;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
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
     * @param string $countryId
     * @return float|null
     */
    public function getMinTotalByCountry(string $countryId): ?float
    {
        $minTotal = 0;
        $totals = $this->getMinTotals();

        if ($totals && isset($totals[$countryId])) {
            $minTotal = $totals[$countryId];
        }

        return $minTotal;
    }

    /**
     * @return array
     */
    public function getMinTotals(): array
    {
        if ($this->_minTotals == null) {
            $result = [];

            if ($totals = $this->_scopeConfig->getValue(self::XML_PATH_MIN_TOTAL_BY_COUNTRY)) {
                $totals = json_decode($totals, true);

                foreach ($totals as $total) {
                    $result[$total['country']] = $total['min_total'];
                }
            }

            $this->_minTotals = $result;
        }

        return $this->_minTotals;
    }

    /**
     * @return array
     */
    public function getAllowedCustomerGroupIds(): array
    {
        $groups = [];

        if ($groups = $this->_scopeConfig->getValue(self::XML_PATH_CUSTOMER_GROUPS)) {
            $groups = explode(',', $groups);
        }

        return $groups;
    }

    /**
     * @return array
     */
    public function getAllowedCountries(): ?array
    {
        $result = [];

        if ($countries = $this->getMinTotals()) {
            $result = array_keys($countries);
        }

        return $result;
    }
}