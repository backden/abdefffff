<?php
/**
 * Manage Label Of StoreView
 * Copyright (C) 2016
 *
 * This file included in Swatch/ManageLabel is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Swatch\ManageLabel\Block\Adminhtml\System\Config;

use Swatch\ManageLabel\Model\Config\Structure\Structure;

class Edit extends \Magento\Config\Block\System\Config\Edit
{
    /**
     * Edit constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Structure $configStructure
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Structure $configStructure,
        array $data = []
    ) {
        parent::__construct($context, $configStructure, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $section = $this->_configStructure->getElement($this->getRequest()->getParam('section'));
        $this->_formBlockName = $section->getFrontendModel();
        if (empty($this->_formBlockName)) {
            $this->_formBlockName = self::DEFAULT_SECTION_BLOCK;
        }
        $this->setTitle($section->getLabel());
        $this->setHeaderCss($section->getHeaderCss());

        $this->getToolbar()->addChild(
            'reset_button',
            'Magento\Backend\Block\Widget\Button',
            [
                'id' => 'reset',
                'label' => __('Reset'),
                'class' => 'reset',
                'onclick' => 'window.location.href=""',
                'data_attribute' => [
                    'mage-init' => ['button' => ['target' => '#config-edit-form']],
                ]
            ]
        );

        $this->getToolbar()->addChild(
            'save_button',
            'Magento\Backend\Block\Widget\Button',
            [
                'id' => 'save',
                'label' => __('Save'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save', 'target' => '#config-edit-form']],
                ]
            ]
        );

        $formBlockName = 'Swatch\ManageLabel\Block\Adminhtml\System\Config\Form';
        $form = $this->getLayout()->createBlock($formBlockName);
        $this->setChild('form', $form);
        return $this;
    }

    /**
     * Submit save content url
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/translate/save', ['_current' => true]);
    }
}
