<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import\ResourceModel;

/**
 * Class File
 * @package Swatch\Content\Model\Import\ResourceModel
 */
class File extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('swatch_import_file', 'id');
    }
}