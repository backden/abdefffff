<?php
/**
 * Manage Label Of StoreView
 * Copyright (C) 2016
 *
 * This file included in Swatch/ManageLabel is licensed under OSL 3.0
 *
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Swatch\ManageLabel\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;

/**
 * Class InstallSchema
 * @package Swatch\ManageLabel\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table_swatch_translate = $setup->getConnection()->newTable($setup->getTable('swatch_translate'));

        $table_swatch_translate->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,),
            'Entity ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('nullable' => false, 'default' => 1),
            'Store ID'
        )->addColumn(
            'section',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            array('nullable' => false),
            'Section ID'
        )->addColumn(
            'id_string',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            array('nullable' => false),
            'ID Of Label'
        )->addColumn(
            'string',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            array('nullable' => false),
            'Label'
        )->addColumn(
            'translate',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            array('nullable' => true),
            'Translated Label'
        )->addColumn(
            'use_default',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            1,
            array('nullable' => true),
            'Use Default Config'
        )->addColumn(
            'is_visible',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            1,
            array('nullable' => true, 'default' => false),
            'Visibility'
        );

        $setup->getConnection()->createTable($table_swatch_translate);

        $setup->endSetup();
    }
}
