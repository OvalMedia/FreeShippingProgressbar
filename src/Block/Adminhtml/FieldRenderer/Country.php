<?php
declare(strict_types=1);

namespace OM\FreeShippingProgressBar\Block\Adminhtml\FieldRenderer;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use OM\FreeShippingProgressBar\Model\Source\Countries;

class Country extends Select
{
    /**
     * @var \OM\FreeShippingProgressBar\Model\Source\Countries
     */
    protected Countries $_countries;

    /**
     * @param \Magento\Framework\View\Element\Context $context
     * @param \OM\FreeShippingProgressBar\Model\Source\Countries $countries
     * @param array $data
     */
    public function __construct(
        Context $context,
        Countries $countries,
        array $data = []
    ) {
        $this->_countries = $countries;
        parent::__construct($context, $data);
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function setInputName(string $value)
    {
        return $this->setName($value);
    }

    /**
     * @param $value
     * @return \OM\FreeShippingProgressBar\Block\Adminhtml\FieldRenderer\Country
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->_countries->toOptionArray());
        }
        return parent::_toHtml();
    }
}