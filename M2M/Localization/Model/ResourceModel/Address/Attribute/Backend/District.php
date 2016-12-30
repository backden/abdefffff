<?php

namespace Isobar\Localization\Model\ResourceModel\Address\Attribute\Backend;
/**
 * Class District
 * @package Isobar\Localization\Model\ResourceModel\Address\Attribute\Backend
 */
class District extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * @var \Isobar\Localization\Model\DistrictFactory
     */
    protected $_districtFactory;

    /**
     * District construct
     *
     * @param \Isobar\Localization\Model\DistrictFactory $districtFactory
     */
    public function __construct(\Isobar\Localization\Model\DistrictFactory $districtFactory)
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
     * @return \Isobar\Localization\Model\District
     */
    protected function _createDistrictInstance()
    {
        return $this->_districtFactory->create();
    }
}
