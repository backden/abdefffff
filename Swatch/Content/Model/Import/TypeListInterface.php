<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Swatch\Content\Model\Import;

/**
 * Interface TypeListInterface
 * @package Swatch\Content\Model\Import
 */
interface TypeListInterface
{
    /**
     * Get information about all declared import types
     *
     * @return array
     */
    public function getTypes();
}
