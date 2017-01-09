<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Block\Adminhtml\Import;

/**
 * Class Container
 * @package Swatch\Content\Block\Adminhtml\Import
 */
class Container extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_controller = 'content';
        $this->_headerText = __('Content Management');
        $this->buttonList->remove('add');
        $this->buttonList->add(
            'refresh',
            [
                'label' => __('Refresh'),
                'class' => 'primary refresh-import-list'
            ]
        );
    }
}