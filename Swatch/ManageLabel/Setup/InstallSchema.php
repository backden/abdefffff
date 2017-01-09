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
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('nullable' => false),
            'Store ID'
        )->addColumn(
            'section_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('nullable' => false),
            'Section ID'
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
        );


        
        $table_swatch_translate->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'id'
        );
        

        $setup->getConnection()->createTable($table_swatch_translate);

        $setup->endSetup();
    }
}
