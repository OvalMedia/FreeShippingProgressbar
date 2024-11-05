<?php
declare(strict_types=1);

namespace OM\FreeShippingProgressBar\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Component\ComponentRegistrar;

class HyvaConfigGenerateBefore implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Component\ComponentRegistrar
     */
    protected ComponentRegistrar $_componentRegistrar;

    /**
     * @param \Magento\Framework\Component\ComponentRegistrar $componentRegistrar
     */
    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->_componentRegistrar = $componentRegistrar;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $config = $observer->getData('config');
        $extensions = $config->hasData('extensions') ? $config->getData('extensions') : [];
        $moduleName = implode('_', array_slice(explode('\\', __CLASS__), 0, 2));
        $path = $this->_componentRegistrar->getPath(ComponentRegistrar::MODULE, $moduleName);
        $extensions[] = ['src' => substr($path, strlen(BP) + 1)];
        $config->setData('extensions', $extensions);
    }
}