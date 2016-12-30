<?php
/**
 * Copyright © 2016 Isobar. All rights reserved.
 */

namespace Isobar\Localization\Setup\Directory\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Setup\Exception;

/**
 * Class InstallData
 * @package Isobar\Localization\Setup\Directory\Data
 */
class InstallData
{
    /** @var \Magento\Framework\App\ObjectManager */
    protected $_objectManager;

    /**
     * InstallData constructor.
     */
    public function __construct()
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
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
        try {
            /** @var DataInterface $dataModel */
            $dataModel = $this->_objectManager->get(__NAMESPACE__. '\\' . ucwords($type) . '\\' . ucwords($countryName));
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

        } catch (Exception $e) {
            throw $e;
        }
    }


}