<?php

namespace Isobar\Localization\Model\ResourceModel\Address\Attribute\Backend;
/**
 * Class City
 * @package Isobar\Localization\Model\ResourceModel\Address\Attribute\Backend
 */
class City extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Isobar\Localization\Model\CityFactory
     */
    protected $_cityFactory;

    /**
     * City construct
     * @param \Isobar\Localization\Model\CityFactory $cityFactory
     */
    public function __construct(\Isobar\Localization\Model\CityFactory $cityFactory)
    {
        $this->_cityFactory = $cityFactory;
    }

    /**
     * Prepare object for save
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $city = $object->getData('city_id');
        if ($city && is_numeric($city)) {
            $cityModel = $this->_createCityInstance();
            $cityModel->load($city);
            if ($cityModel->getId()) {
                $object->setCityId($cityModel->getId())->setCity($cityModel->getName());
            }
        } else {
            $object->setData('city_id', null);
        }
        return $this;
    }

    /**
     * Create instance city
     * @return \Magento\Directory\Model\City
     */
    protected function _createCityInstance()
    {
        return $this->_cityFactory->create();
    }
}
