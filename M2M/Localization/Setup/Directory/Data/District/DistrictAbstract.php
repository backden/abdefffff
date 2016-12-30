<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Setup\Directory\Data\District;
use Isobar\Localization\Setup\Directory;

/**
 * Class DistrictAbstract
 * @package Isobar\Localization\Setup\Directory\Data\District
 */
abstract class DistrictAbstract implements Directory\Data\DataInterface
{
    const TABLE = 'directory_country_district';
    const NAME_TABLE = 'directory_country_district_name';

    /** @var string  */
    protected $_primaryField = 'district_id';
    
    /** @var array  */
    protected $_localeIndex = [];

    /**
     * Get insert fields
     * @return array
     */
    public function getInsertFields()
    {
        return [
            'country_id' => 0,
            'region_code' =>1,
            'city_code' => 2,
            'code' => 3,
            'default_name' => 5
        ];
    }

    /**
     * Get name field index
     * @param string $locale
     * @return bool|int
     */
    public function getNameFieldIndex($locale)
    {
        if (isset($this->_localeIndex[$locale])) {
            return $this->_localeIndex[$locale];
        }
        return false;
    }

    /**
     * Get table
     * @return string
     */
    public function getTable()
    {
        return self::TABLE;
    }
    
    /**
     * Get Name table
     * @return string
     */
    public function getNameTable()
    {
        return self::NAME_TABLE;
    }

    /**
     * Get primary field name
     * @return string
     */
    public function getPrimaryFieldName()
    {
        return $this->_primaryField;
    }

    /**
     * Prepare binding data
    * @param array $data
    * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
    * @return array
    */
    public function prepareBindingData(array $data = [], $setup)
    {
        $insertData = [];
        foreach ($this->getInsertFields() as $field => $index) {
            $insertData[$field] = $data[$index];
        }
        if (!empty($insertData['city_code'])) {
            $select = "select city_id from ".$setup->getConnection()->getTableName('directory_country_city')."
                where country_id = :country_id and region_code = :region_code and code = :code";
            $bind = [':country_id' => $insertData['country_id'],':region_code' => $insertData['region_code'], ':code' => $insertData['city_code']];
            unset($insertData['region_code']);
            if($cityId = $setup->getConnection()->fetchRow($select,$bind,\Zend_Db::FETCH_COLUMN))
                $insertData['city_id'] = $cityId;
        }
        return $insertData;
    }
}