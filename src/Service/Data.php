<?php
declare(strict_types=1);
namespace OM\FreeShippingProgressBar\Service;

class Data
{
    const XML_PATH_CUSTOMER_GROUPS = 'om_freeshipping_progress_bar/general/customer_groups';

    /**
     * @var \OM\FreeShippingProgressBar\Service\Config
     */
    protected Config $_config;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected \Magento\Checkout\Model\Session $_checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected \Magento\Customer\Model\Session $_customerSession;

    /**
     * @param \OM\FreeShippingProgressBar\Service\Config $config
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        Config $config,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_config = $config;
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
    }

    /**
     * @return false|\Magento\Quote\Api\Data\CartInterface|\Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        $quote = false;

        try {
            $quote = $this->_checkoutSession->getQuote();
        } catch (\Exception $e) {}

        return $quote;
    }

    /**
     * @return bool|\Magento\Quote\Model\Quote\Address
     */
    public function getShippingAddress()
    {
        $address = false;

        if ($quote = $this->getQuote()) {
            $address = $quote->getShippingAddress();
        }

        return $address;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        if ($address = $this->getShippingAddress()) {
            $subTotal = $address->getSubtotalInclTax();
            $discount = ($address->getDiscountAmount() * -1);
            $total = (float) ($subTotal - $discount);
        }

        return (float) $total;
    }

    /**
     * @return bool|float
     */
    public function getFreeShippingDifference()
    {
        return $this->_config->getMinTotal() - $this->getTotal();
    }

    /**
     * @return float
     */
    public function getFreeShippingCompletionPercent(): float
    {
        return (float) ($this->getTotal() / $this->_config->getMinTotal()) * 100;
    }

    /**
     * @return string|null
     */
    public function getShippingCountry(): ?string
    {
        $id = null;

        if ($address = $this->getShippingAddress()) {
            $id = $address->getCountryId();
        }

        return $id;
    }

    /**
     * @return int|null
     */
    public function getCustomerGroupId(): ?int
    {
        $id = null;

        if ($quote = $this->getQuote()) {
            $id = $quote->getCustomerGroupId();
        }

        return $id;
    }

    /**
     * @return bool
     */
    public function canShowBlock(): bool
    {
        $result = false;

        if ($this->_config->isEnabled()) {
            $result = $this->getTotal() >= $this->_config->getMinTotal();
            $country = $this->getShippingCountry();

            if ($country == $this->_config->getShippingOriginCountryId() || $country == null) {
                try {
                    $groups = $this->_config->getCustomerGroups();

                    if (empty($groups)) {
                        $result = true;
                    } else {
                        $customerGroupId = $this->_customerSession->getCustomerGroupId();
                        $result = in_array($customerGroupId, $groups);
                    }
                } catch (\Exception $e) {
                }
            }

        }

        return $result;
    }
}