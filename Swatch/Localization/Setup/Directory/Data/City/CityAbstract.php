<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Setup\Directory\Data\City;
use Swatch\Localization\Setup\Directory;

abstract class CityAbstract implements Directory\Data\DataInterface
{
    const TABLE = 'directory_country_city';
    const NAME_TABLE = 'directory_country_city_name';

    /** @var string  */
    protected $_primaryField = 'city_id';
    
    /** @var array  */
    protected $_localeIndex = [];

    /**
     * @return array
     */
    public function getInsertFields()
    {
        return [
            'country_id' => 0,
            'region_code' => 1,
            'code' => 2,
            'default_name' => 4
        ];
    }

    /**
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
     * @return string
     */
    public function getTable()
    {
        return self::TABLE;
    }
    
    /**
     * @return string
     */
    public function getNameTable()
    {
        return self::NAME_TABLE;
    }

    /**
     * @return string
     */
    public function getPrimaryFieldName()
    {
        return $this->_primaryField;
    }

    /**
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
        if (!empty($insertData['region_code'])) {
            $select = "select region_id from ".$setup->getConnection()->getTableName('directory_country_region')."
                where country_id = :country_id and code = :code";
            $bind = [':country_id' => $insertData['country_id'],':code' => $insertData['region_code']];
            if($regionId = $setup->getConnection()->fetchRow($select,$bind,\Zend_Db::FETCH_COLUMN))
                $insertData['region_id'] = $regionId;
        }
        return $insertData;
    }
}