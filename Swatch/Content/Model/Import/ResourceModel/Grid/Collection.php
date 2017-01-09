<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import\ResourceModel\Grid;


use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Swatch\Content\Model\Import\TypeListInterface;

/**
 * Class Collection
 * @package Swatch\Content\Model\Import\ResourceModel\Grid
 */
class Collection extends \Magento\Framework\Data\Collection
{
    /** @var TypeListInterface  */
    protected $_importTypeList;

    /**
     * Collection constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param TypeListInterface $importTypeList
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        TypeListInterface $importTypeList
    )
    {
        $this->_importTypeList = $importTypeList;
        parent::__construct($entityFactory);
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            foreach ($this->_importTypeList->getTypes() as $type) {
                $this->addItem($type);
            }
            $this->_setIsLoaded(true);
        }
        return $this;
    }

}