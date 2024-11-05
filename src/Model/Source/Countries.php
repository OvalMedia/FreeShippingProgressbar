<?php
declare(strict_types=1);

namespace OM\FreeShippingProgressBar\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Directory\Model\AllowedCountries;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\Country;

class Countries implements OptionSourceInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected StoreManagerInterface $_storeManager;

    /**
     * @var \Magento\Directory\Model\AllowedCountries
     */
    protected AllowedCountries $_allowedCountries;

    /**
     * @var \Magento\Directory\Helper\Data
     */
    protected DirectoryHelper $_directoryHelper;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\AllowedCountries $allowedCountries
     * @param \Magento\Directory\Helper\Data $directoryHelper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        AllowedCountries $allowedCountries,
        DirectoryHelper $directoryHelper
    ) {
        $this->_storeManager = $storeManager;
        $this->_allowedCountries = $allowedCountries;
        $this->_directoryHelper = $directoryHelper;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $allowedCountries = $this->_allowedCountries->getAllowedCountries(ScopeInterface::SCOPE_STORES);
        asort($allowedCountries);
        $collection = $this->_directoryHelper->getCountryCollection();

        foreach ($collection as $country) {
            if (in_array($country->getCountryId(), $allowedCountries)) {
                $allowedCountries[$country->getCountryId()] = $country->getName();
            }
        }

        $options = [];

        foreach ($allowedCountries as $code => $name) {
            $options[] = [
                'value' => $code,
                'label' => $name
            ];
        }

        return $options;
    }
}