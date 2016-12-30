<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Plugin\Sales\Block\Adminhtml\Order\Create\Form;

/**
 * Class Address
 * @package Isobar\Localization\Plugin\Sales\Block\Adminhtml\Order\Create\Form
 */
class Address
{
    /**
     * After get Form
     * @param \Magento\Sales\Block\Adminhtml\Order\Create\Form\Address $subject
     * @param \Magento\Framework\Data\Form $form
     * @return \Magento\Framework\Data\Form
     */
    public function afterGetForm($subject, $form)
    {
        if (!$form->getHasCustomElementRenderers()) {
            if ($form->getElement('city_id')) {
                $element = $form->getElement('city');
                $form->getElement('city_id')->setNoDisplay(true);
                $element->setRenderer($subject->getLayout()->createBlock('Isobar\Localization\Block\Adminhtml\Edit\Renderer\City'));
            };
        }
        if (!$form->getHasCustomElementRenderers()) {
            if ($form->getElement('district_id')) {
                $element = $form->getElement('district');
                $form->getElement('district_id')->setNoDisplay(true);
                $element->setRenderer($subject->getLayout()->createBlock('Isobar\Localization\Block\Adminhtml\Edit\Renderer\District'));
            };
        }
        return $form;
    }
}