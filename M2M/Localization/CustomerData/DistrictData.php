<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Isobar\Localization\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

/**
 * Cart source
 */
class DistrictData implements SectionSourceInterface
{
    /**
     * @var \Magento\Directory\Helper\Data
     */
    protected $directoryHelper;

    /**
     * @param \Magento\Directory\Helper\Data $directoryHelper
     * @codeCoverageIgnore
     */
    public function __construct(\Isobar\Localization\Helper\Data $directoryHelper)
    {
        $this->directoryHelper = $directoryHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData()
    {
        return $this->directoryHelper->getDistrictData();
    }
}
