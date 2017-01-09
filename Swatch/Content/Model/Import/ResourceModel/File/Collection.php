<?php

/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import\ResourceModel\File;
/**
 * Class Collection
 * @package Swatch\Content\Model\Import\ResourceModel\File
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * init
     */
    protected function _construct()
    {
        $this->_init('Swatch\Content\Model\Import\File', 'Swatch\Content\Model\Import\ResourceModel\File');
    }
}