<?php

namespace OM\FreeShippingProgressBar\Plugin\Model\Checkout\CustomerData;

class Cart
{
    /**
     * @var \OM\FreeShippingProgressBar\Service\Config
     */
    protected \OM\FreeShippingProgressBar\Service\Config $_config;

    /**
     * @var \OM\FreeShippingProgressBar\Service\Data
     */
    protected \OM\FreeShippingProgressBar\Service\Data $_data;

    /**
     * @param \OM\FreeShippingProgressBar\Service\Config $config
     * @param \OM\FreeShippingProgressBar\Service\Data $data
     */
    public function __construct(
        \OM\FreeShippingProgressBar\Service\Config $config,
        \OM\FreeShippingProgressBar\Service\Data $data,
    ) {
        $this->_config = $config;
        $this->_data = $data;
    }

    /**
     * @param \Magento\Checkout\CustomerData\Cart\CheckoutCart $cart
     * @param $result
     * @return mixed|void
     */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $cart, $result)
    {
        if (!$this->_config->isEnabled()) {
            return $result;
        }

        $result['freeshipping_progress_bar'] = [
            'difference' => $this->_data->getFreeShippingDifference(),
            'percent_complete' => $this->_data->getFreeShippingCompletionPercent(),
            'min_total' => $this->_config->getMinTotal()
        ];

        return $result;
    }
}