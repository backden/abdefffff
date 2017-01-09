<?php

namespace Swatch\Localization\Model\ResourceModel\Address\Attribute\Backend;
/**
 * Class City
 * @package Swatch\Localization\Model\ResourceModel\Address\Attribute\Backend
 */
class City extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Swatch\Localization\Model\CityFactory
     */
    protected $_cityFactory;

    /**
     * City construct
     * @param \Swatch\Localization\Model\CityFactory $cityFactory
     */
    public function __construct(\Swatch\Localization\Model\CityFactory $cityFactory)
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
