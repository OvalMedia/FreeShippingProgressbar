<?php
declare(strict_types=1);

namespace OM\FreeShippingProgressBar\ViewModel;

use Magento\Store\Model\ScopeInterface;
use Magento\Sales\Model\Order\Shipment;
use OM\FreeShippingProgressBar\Service\Config;
use OM\FreeShippingProgressBar\Service\Data;

class Cart implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \OM\FreeShippingProgressBar\Service\Config
     */
    protected Config $_config;

    /**
     * @var \OM\FreeShippingProgressBar\Service\Data
     */
    protected Data $_data;

    /**
     * @param \OM\FreeShippingProgressBar\Service\Config $config
     * @param \OM\FreeShippingProgressBar\Service\Data $data
     */
    public function __construct(
        Config $config,
        Data $data
    ) {
        $this->_config = $config;
        $this->_data = $data;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->_data->getTotal();
    }

    /**
     * @return string|null
     */
    public function getShippingCountry(): ?string
    {
        return $this->_data->getShippingCountry();
    }

    /**
     * @return string
     */
    public function getShippingOriginCountryId(): string
    {
        return $this->_config->getShippingOriginCountryId();
    }

    /**
     * @return array
     */
    public function getMinTotals(): array
    {
        return $this->_config->getMinTotals();
    }

    /**
     * @return int|null
     */
    public function getCustomerGroupId(): ?int
    {
        return $this->_data->getCustomerGroupId();
    }

    /**
     * @return array|null
     */
    public function getAllowedCountries(): ?array
    {
        return $this->_config->getAllowedCountries();
    }

    /**
     * @return array|null
     */
    public function getAllowedCustomerGroupIds(): ?array
    {
        return $this->_config->getAllowedCustomerGroupIds();
    }

    /**
     * @return bool
     */
    public function canShowBlock(): bool
    {
        return $this->_data->canShowBlock();
    }
}