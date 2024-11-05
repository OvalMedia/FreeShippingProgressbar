<?php
declare(strict_types=1);
namespace OM\FreeShippingProgressBar\Plugin\Model\Checkout\CustomerData;

use OM\FreeShippingProgressBar\Service\Config;
use OM\FreeShippingProgressBar\Service\Data;
use Magento\Checkout\CustomerData\Cart as CustomerCart;

class Cart
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
     * @param \Magento\Checkout\CustomerData\Cart\CheckoutCart $cart
     * @param $result
     * @return mixed|void
     */
    public function afterGetSectionData(CustomerCart $cart, $result)
    {
        if (!$this->_config->isEnabled()) {
            return $result;
        }

        $result['freeshipping_progress_bar'] = [
            'difference' => $this->_data->getFreeShippingDifference(),
            'percent_complete' => $this->_data->getFreeShippingCompletionPercent(),
            'min_total' => $this->_data->getMinTotal(),
            'default_shipping_country' => $this->_data->getDefaultShippingCountry(),
            'current_shipping_country' => $this->_data->getCurrentShippingCountry(),
            'customer_group_id' => $this->_data->getCustomerGroupId()
        ];

        return $result;
    }
}