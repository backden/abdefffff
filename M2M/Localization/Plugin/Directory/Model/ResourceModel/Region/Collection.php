<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Plugin\Directory\Model\ResourceModel\Region;

/**
 * Class Collection
 * @package Isobar\Localization\Plugin\Directory\Model\ResourceModel\Region
 */
class Collection
{
    /**
     * @param $subject
     * @param bool $printQuery
     * @param bool $logQuery
     * @return array
     */
    public function beforeLoad($subject, $printQuery = false, $logQuery = false)
    {
        $subject->unshiftOrder('sort_order', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        return [
            $printQuery,
            $logQuery
        ];
    }
}