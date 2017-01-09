<?php

namespace Swatch\Localization\Model\ResourceModel\Address\Attribute\Backend;
/**
 * Class District
 * @package Swatch\Localization\Model\ResourceModel\Address\Attribute\Backend
 */
class District extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Swatch\Localization\Model\DistrictFactory
     */
    protected $_districtFactory;

    /**
     * District construct
     *
     * @param \Swatch\Localization\Model\DistrictFactory $districtFactory
     */
    public function __construct(\Swatch\Localization\Model\DistrictFactory $districtFactory)
    {
        $this->_districtFactory = $districtFactory;
    }

    /**
     * Prepare object for save
     *
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeSave($object)
    {
        $district = $object->getData('district_id');
        if ($district && is_numeric($district)) {
            $districtModel = $this->_createDistrictInstance();
            $districtModel->load($district);
            if ($districtModel->getId()) {
                $object->setDistrictId($districtModel->getId())->setDistrict($districtModel->getName());
            }
        } else {
            $object->setData('district_id', null);
        }
        return $this;
    }

    /** Create district instance
     *
     * @return \Swatch\Localization\Model\District
     */
    protected function _createDistrictInstance()
    {
        return $this->_districtFactory->create();
    }
}
