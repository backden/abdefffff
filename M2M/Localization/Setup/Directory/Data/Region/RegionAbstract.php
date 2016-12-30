<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Setup\Directory\Data\Region;
use Isobar\Localization\Setup\Directory;

abstract class RegionAbstract implements Directory\Data\DataInterface
{
    const TABLE = 'directory_country_region';
    const NAME_TABLE = 'directory_country_region_name';

    /** @var string  */
    protected $_primaryField = 'region_id';
    
    /** @var array  */
    protected $_localeIndex = [];

    /**
     * @return array
     */
    public function getInsertFields()
    {
        return [
            'country_id' => 0,
            'code' => 1,
            'default_name' => 2
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
     * @param $setup
     * @return array
     */
    public function prepareBindingData(array $data = [], $setup)
    {
        $insertData = [];
        foreach ($this->getInsertFields() as $field => $index) {
            $insertData[$field] = $data[$index];
        }
        return $insertData;
    }
}