<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Plugin\Directory\Model\ResourceModel\Country;

/**
 * Class Collection
 * @package Swatch\Localization\Plugin\Directory\Model\ResourceModel\Country
 */
class Collection
{
    /**
     * After To Option array
     * @param \Magento\Directory\Model\ResourceModel\Country\Collection $subject
     * @param array $options
     * @return mixed
     */
    public function afterToOptionArray($subject, $options)
    {
        if (count($options) > 0) {
            $option = array_shift($options);
            if ($option['label'] == ' ') {
                $option['label']  = __('Please select');
            }
            array_unshift($options,$option);
        }
        return $options;
    }
}