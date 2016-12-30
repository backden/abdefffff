<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Helper;

/**
 * Class Address
 * @package Isobar\Localization\Helper
 */
class Address
{
    /**
     * Get additional custom attributes
     * @return array
     */
    public static function getAdditionalAttributes()
    {
        return [
            'city_id',
            'district',
            'district_id'
        ];
    }
    /**
     * Get hidden attributes
     * @return array
     */
    public static function getHiddenAttributes()
    {
        return [
            'city_id',
            'district_id'
        ];
    }
}