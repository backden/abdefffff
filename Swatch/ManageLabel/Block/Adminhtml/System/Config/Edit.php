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
        $form = $this->getLayout()->createBlock('Swatch\ManageLabel\Block\Adminhtml\System\Config\Form');
        $this->setChild('form', $form);
        return parent::_prepareLayout();
    }
}
