<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 * Date: 03/01/2017
 * Time: 15:39
 */

namespace Swatch\Localization\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Install the Catalog module DB schema
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** Create table directory_country_city */
        $table = $setup->getConnection()->newTable($setup->getTable('directory_country_city'));
        $table->addColumn(
            'city_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'City Id'
        )->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            4,
            ['nullable' => false, 'default' => '0'],
            'Country Id in ISO-2'
        )->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => 0],
            'Region Id'
        )->addColumn(
            'region_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            32,
            ['nullable' => true],
            'Region Code'
        )
            ->addColumn(
                'code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => true, 'default' => null],
                'City code'
            )->addColumn(
                'default_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'City Name'
            )->addIndex(
                $setup->getIdxName('directory_country_city', ['country_id']),
                ['country_id']
            )->setComment(
                'Directory Country City'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'directory_country_city_name'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('directory_country_city_name')
        )->addColumn(
            'locale',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            8,
            ['nullable' => false, 'primary' => true, 'default' => false],
            'Locale'
        )->addColumn(
            'city_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
            'City Id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null],
            'City Name'
        )->addIndex(
            $setup->getIdxName('directory_country_city_name', ['city_id']),
            ['city_id']
        )->addForeignKey(
            $setup->getFkName(
                'directory_country_city_name',
                'city_id',
                'directory_country_city',
                'city_id'
            ),
            'city_id',
            $setup->getTable('directory_country_city'),
            'city_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Directory Country City Name'
        );
        $setup->getConnection()->createTable($table);

        /** Create table directory_country_district */
        $table = $setup->getConnection()->newTable($setup->getTable('directory_country_district'));
        $table->addColumn(
            'district_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'District Id'
        )->addColumn(
            'country_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            4,
            ['nullable' => false, 'default' => '0'],
            'Country Id in ISO-2'
        )->addColumn(
            'city_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => true, 'default' => 0],
            'City Id'
        )->addColumn(
            'city_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => true],
            'City Code'
        )
            ->addColumn(
                'code',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                64,
                ['nullable' => true, 'default' => null],
                'District code'
            )->addColumn(
                'default_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'District Name'
            )->addIndex(
                $setup->getIdxName('directory_country_district', ['country_id']),
                ['country_id']
            )->setComment(
                'Directory Country District'
            );
        $setup->getConnection()->createTable($table);

        /**
         * Create table 'directory_country_district_name'
         */
        $table = $setup->getConnection()->newTable(
            $setup->getTable('directory_country_district_name')
        )->addColumn(
            'locale',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            8,
            ['nullable' => false, 'primary' => true, 'default' => false],
            'Locale'
        )->addColumn(
            'district_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true, 'default' => '0'],
            'District Id'
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null],
            'District Name'
        )->addIndex(
            $setup->getIdxName('directory_country_district_name', ['district_id']),
            ['district_id']
        )->addForeignKey(
            $setup->getFkName(
                'directory_country_district_name',
                'district_id',
                'directory_country_district',
                'district_id'
            ),
            'district_id',
            $setup->getTable('directory_country_district'),
            'district_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Directory Country District Name'
        );
        $setup->getConnection()->createTable($table);

        $setup->getConnection()->addColumn($setup->getTable('directory_country_region'),
            'sort_order',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                'nullable' => true,
                'default' => 0,
                'comment' => 'Sort Order',
            ]
        );

        $setup->endSetup();
    }
}
