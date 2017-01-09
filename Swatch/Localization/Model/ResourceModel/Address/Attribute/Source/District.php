<?php

namespace Swatch\Localization\Model\ResourceModel\Address\Attribute\Source;

/**
 * Class District
 * @package Swatch\Localization\Model\ResourceModel\Address\Attribute\Source
 */
class District extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * @var \Swatch\Localization\Model\ResourceModel\District\CollectionFactory
     */
    protected $_districtFactory;

    /**
     * District constructor.
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $attrOptionFactory
     * @param \Swatch\Localization\Model\ResourceModel\District\CollectionFactory $districtFactory
     */
    public function __construct(
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $attrOptionFactory,
        \Swatch\Localization\Model\ResourceModel\District\CollectionFactory $districtFactory
    ) {
        $this->_districtFactory = $districtFactory;
        parent::__construct($attrOptionCollectionFactory, $attrOptionFactory);
    }

    /**
     * Get all option
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->_createDistrictCollection()->load()->toOptionArray();
        }

        return $this->_options;
    }

    /**
     * Create district collection
     * @return \Swatch\Localization\Model\ResourceModel\District\Collection
     */
    protected function _createDistrictCollection()
    {
        return $this->_districtFactory->create();
    }
}
