<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */
namespace Swatch\Localization\Block\Adminhtml\Edit\Renderer;
use Swatch\Localization\Helper\Data;

/**
 * Customer address district field renderer
 */
class District extends \Magento\Backend\Block\AbstractBlock implements
    \Magento\Framework\Data\Form\Element\Renderer\RendererInterface
{
    /**
     * @var \Magento\Directory\Helper\Data
     */
    protected $_directoryHelper;

    /**
     * District constructor.
     * @param \Magento\Backend\Block\Context $context
     * @param Data $directoryHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Swatch\Localization\Helper\Data $directoryHelper,
        array $data = []
    ) {
        $this->_directoryHelper = $directoryHelper;
        parent::__construct($context, $data);
    }

    /**
     * Output the district element and javasctipt that makes it dependent from country element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        if (!($country = $element->getForm()->getElement('country_id')) || !($city = $element->getForm()->getElement('city_id'))) {
            return $element->getDefaultHtml();
        }
        $districtId = $element->getForm()->getElement('district_id')->getValue();

        $html = '<div class="field field-state required admin__field">';
        $element->setClass('input-text admin__control-text');
        $element->setRequired(true);
        $html .= $element->getLabelHtml() . '<div class="control admin__field-control">';
        $html .= $element->getElementHtml();

        $selectName = str_replace('district', 'district_id', $element->getName());
        $selectId = $element->getHtmlId() . '_id';
        $html .= '<select id="' .
            $selectId .
            '" name="' .
            $selectName .
            '" class="select required-entry admin__control-select" style="display:none">';
        $html .= '<option value="">' . __('Please select') . '</option>';
        $html .= '</select>';

        $html .= '<script>' . "\n";
        $html .= 'require(["prototype", "Swatch_Localization/js/form"], function(){';
        $html .= '$("' . $selectId . '").setAttribute("defaultValue", "' . $districtId . '");' . "\n";
        $html .= 'new districtUpdater("' .
            $city->getHtmlId() .
            '", "' .
            $element->getHtmlId() .
            '", "' .
            $selectId .
            '", ' .
            $this->_directoryHelper->getDistrictJson() .
            ');' .
            "\n";

        $html .= '});';
        $html .= '</script>' . "\n";

        $html .= '</div></div>' . "\n";

        return $html;
    }
}
