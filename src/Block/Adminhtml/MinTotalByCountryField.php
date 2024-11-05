<?php
declare(strict_types=1);

namespace OM\FreeShippingProgressBar\Block\Adminhtml;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use OM\FreeShippingProgressBar\Block\Adminhtml\FieldRenderer\Country;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\DataObject;

class MinTotalByCountryField extends AbstractFieldArray
{
    protected $_renderer;

    /**
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('country', [
            'label' => __('Country'),
            'renderer' => $this->_getRenderer()
        ]);
        $this->addColumn('min_total', ['label' => __('Minimum Order Total')]);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * @param DataObject $row
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $value = $row->getData('country');

        if ($value !== null) {
            $options['option_' . $this->_getRenderer()->calcOptionHash($value)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getRenderer(): BlockInterface
    {
        if (!$this->_renderer) {
            $this->_renderer = $this->getLayout()->createBlock(Country::class, '', ['data' => ['is_render_to_js_template' => true]]);
        }
        return $this->_renderer;
    }
}
