<?php
declare(strict_types=1);

namespace OM\FreeShippingProgressBar\Service;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;

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
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession
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
     * @return float
     */
    public function getFreeShippingDifference(): float
    {
        file_put_contents('fpb.txt', 'minTotal: ' . $this->getMinTotal() . "\n", FILE_APPEND);
        file_put_contents('fpb.txt', 'total: ' . $this->getTotal() . "\n", FILE_APPEND);
        return $this->getMinTotal() - $this->getTotal();
    }

    /**
     * @return float
     */
    public function getFreeShippingCompletionPercent(): float
    {
        return (float) ($this->getTotal() / $this->getMinTotal()) * 100;
    }

    /**
     * @return string|null
     */
    public function getDefaultShippingCountry(): ?string
    {
        return $this->_config->getShippingOriginCountryId();
    }

    /**
     * @return string|null
     */
    public function getCurrentShippingCountry(): ?string
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
            $id = (int) $quote->getCustomerGroupId();
        }

        return $id;
    }

    /**
     * @return float
     */
    public function getMinTotal(): ?float
    {
        $result = 0;
        $countryId = $this->getCurrentShippingCountry();

        if (!$countryId) {
            $countryId = $this->getDefaultShippingCountry();
        }

        $totals = $this->_config->getMinTotals();

        if (isset($totals[$countryId])) {
            $result = $totals[$countryId];
        }

        return (float) $result;
    }


    /**
     * @return bool
     */
    public function canShowBlock(): bool
    {
        $result = false;

        if ($this->_config->isEnabled()) {
            $result = $this->getTotal() >= $this->getMinTotal();
            $country = $this->getDefaultShippingCountry();

            if ($country == $this->_config->getShippingOriginCountryId() || $country == null) {
                try {
                    $groups = $this->_config->getAllowedCustomerGroupIds();

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