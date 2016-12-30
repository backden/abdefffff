<?php

namespace Isobar\Localization\Model\ResourceModel\Address\Attribute\Source;
/**
 * Class City
 * @package Isobar\Localization\Model\ResourceModel\Address\Attribute\Source
 */
class City extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * @var \Magento\Directory\Model\ResourceModel\Region\CollectionFactory
     */
    protected $_regionsFactory;

    /**
     * @var \Isobar\Localization\Model\ResourceModel\City\CollectionFactory
     */
    protected $_cityFactory;

    /**
     * City construct
     *
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $attrOptionFactory
     * @param \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionsFactory
     */
    public function __construct(
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $attrOptionFactory,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionsFactory,
        \Isobar\Localization\Model\ResourceModel\City\CollectionFactory $cityFactory
    ) {
        $this->_regionsFactory = $regionsFactory;
        $this->_cityFactory = $cityFactory;
        parent::__construct($attrOptionCollectionFactory, $attrOptionFactory);
    }

    /**
     * Retrieve all region options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->_createCityCollection()->load()->toOptionArray();
        }

        return $this->_options;
    }

    /**
     * @return \Isobar\Localization\Model\ResourceModel\City\Collection
     */
    protected function _createCityCollection()
    {
        return $this->_cityFactory->create();
    }
}
