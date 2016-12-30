<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Plugin\Customer\Model\Metadata;

/**
 * Class AddressMetadata
 * @package Isobar\Localization\Plugin\Customer\Model\Metadata
 */
class AddressMetadata
{
    /**
     * Resort attribute
     * @param $subject
     * @param $attributes
     * @return array
     */
    public function afterGetAttributes($subject, $attributes)
    {
        uasort($attributes, function ($a, $b){
            if ($a->getSortOrder() == $b->getSortOrder()) {
                return 0;
            }
           return  ($a->getSortOrder() < $b->getSortOrder())?-1:1;
        });
        return $attributes;
    }
}