<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */
namespace Isobar\Localization\Block\Adminhtml\Edit\Renderer;
use Isobar\Localization\Helper\Data;

/**
 * Customer address city field renderer
 */
class City extends \Magento\Backend\Block\AbstractBlock implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var \Magento\Directory\Helper\Data
     */
    protected $_directoryHelper;

    /**
     * City constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param Data $directoryHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Isobar\Localization\Helper\Data $directoryHelper,
        array $data = []
    ) {
        $this->_directoryHelper = $directoryHelper;
        parent::__construct($context, $data);
    }

    /**
     * Output the city element and javasctipt that makes it dependent from country element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if (!($country = $element->getForm()->getElement('country_id')) || !($region = $element->getForm()->getElement('region_id'))) {
            return $element->getDefaultHtml();
        }
        $cityId = $element->getForm()->getElement('city_id')->getValue();

        $html = '<div class="field field-state required admin__field _required">';
        $element->setClass('input-text admin__control-text');
        $element->setRequired(true);
        $html .= $element->getLabelHtml() . '<div class="control admin__field-control">';
        $html .= $element->getElementHtml();

        $selectName = str_replace('city', 'city_id', $element->getName());
        $selectId = $element->getHtmlId() . '_id';
        $html .= '<select id="' .
            $selectId .
            '" name="' .
            $selectName .
            '" class="select required-entry admin__control-select" style="display:none">';
        $html .= '<option value="">' . __('Please select') . '</option>';
        $html .= '</select>';

        $html .= '<script>' . "\n";
        $html .= 'require(["prototype", "Isobar_Localization/js/form"], function(){';
        $html .= '$("' . $selectId . '").setAttribute("defaultValue", "' . $cityId . '");' . "\n";
        $html .= 'new cityUpdater("' .
            $region->getHtmlId() .
            '", "' .
            $element->getHtmlId() .
            '", "' .
            $selectId .
            '", ' .
            $this->_directoryHelper->getCityJson() .
            ');' .
            "\n";

        $html .= '});';
        $html .= '</script>' . "\n";

        $html .= '</div></div>' . "\n";

        return $html;
    }
}
