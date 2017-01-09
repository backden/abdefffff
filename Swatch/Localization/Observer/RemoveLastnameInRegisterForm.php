<?php
namespace Swatch\Localization\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class RemoveLastnameInRegisterForm
 * @package Swatch\Localization\Observer
 */
class RemoveLastnameInRegisterForm implements ObserverInterface
{

    /**
     * @var \Magento\Framework\View\Element\Template
     */
    protected $template;

    /**
     * RemoveLastnameInRegisterForm constructor.
     * @param \Magento\Framework\View\Element\Template $template
     */
    public function __construct(
        \Magento\Framework\View\Element\Template $template
    )
    {
        $this->template = $template;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if($observer->getElementName() == 'customer_form_register') {
            $block = $this->template;
            $block->setTemplate('Swatch_Localization::remove_lastname.phtml');
            $html = $observer->getTransport()->getOutput() . $block->toHtml();
            $observer->getTransport()->setOutput($html);
        }
    }
}