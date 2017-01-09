<?php

namespace Swatch\Localization\Setup\Directory\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Setup\Exception;

/**
 * Class InstallData
 * @package Swatch\Localization\Setup\Directory\Data
 */
class InstallData
{

    protected $_chinaCity;

    protected $_chinaDistrict;

    protected $_chinaRegion;

    protected $_japanRegion;

    /**
     * InstallData constructor.
     */
    public function __construct(
        \Swatch\Localization\Setup\Directory\Data\City\China $chinaCity,
        \Swatch\Localization\Setup\Directory\Data\District\China $chinaDistrict,
        \Swatch\Localization\Setup\Directory\Data\Region\China $chinaRegion,
        \Swatch\Localization\Setup\Directory\Data\Region\Japan $japanRegion
    )
    {
        $this->_chinaCity = $chinaCity;
        $this->_chinaDistrict = $chinaDistrict;
        $this->_chinaRegion = $chinaRegion;
        $this->_japanRegion = $japanRegion;
    }

    /**
     * Install function
     * @param ModuleDataSetupInterface $setup
     * @param string $type
     * @param string $countryName
     * @param array $locales
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, $type, $countryName, array $locales = [])
    {
        $countryName = ucwords($countryName);
        $type = ucwords($type);
        try {
            if($countryName == 'China') {
                if($type == 'Region') {
                    $dataModel = $this->_chinaRegion;
                }
                if($type == 'City') {
                    $dataModel = $this->_chinaCity;
                }
                if($type == 'District') {
                    $dataModel = $this->_chinaDistrict;
                }
            } else if ($countryName == 'Japan'){
                if($type == 'Region') {
                    $dataModel = $this->_japanRegion;
                }
            }

            if(!empty($dataModel)) {
                $connection = $setup->getConnection();
                $table = $setup->getTable($dataModel->getTable());
                $tableName = $setup->getTable($dataModel->getNameTable());
                $primaryFieldName = $dataModel->getPrimaryFieldName();
                foreach ($dataModel->getData() as $data) {
                    $connection->insert($table, $dataModel->prepareBindingData($data, $setup));
                    $latestId = $connection->lastInsertId($table);
                    foreach ($locales as $locale) {
                        if ($index = $dataModel->getNameFieldIndex($locale)) {
                            $bind = [
                                'locale' => $locale,
                                $primaryFieldName => $latestId,
                                'name' => $data[$index]
                            ];
                            $connection->insert($tableName, $bind);
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }


}