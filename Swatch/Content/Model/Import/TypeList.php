<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Swatch\Content\Model\Import;

/**
 * Class TypeList
 * @package Swatch\Content\Model\Import
 */
class TypeList implements TypeListInterface
{
    /** @var array  */
    protected $_data = [];

    /**
     * TypeList constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return isset($this->_data['types'])?$this->_data['types']:[];
    }
}
