<?php
namespace Swatch\Content\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 * @package Swatch\Content\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        $setup->startSetup();
        $conn = $setup->getConnection();
        $table = $conn->newTable($setup->getTable('swatch_import_file'));
        $table->addColumn(
            'id',
            Table::TYPE_INTEGER,
            11,
            [
                'primary' => true,
                'unsigned' => true,
                'nullable' => false,
                'identity' => true,
            ],
            'File id'
        )->addColumn(
            'type',
            Table::TYPE_TEXT,
            32,
            [
                'nullable' => false
            ],
            'Import type code'
        )->addColumn(
            'filename',
            Table::TYPE_TEXT,
            255,
            [
                'nullable' => false
            ],
            'Import file name'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            2,
            [
                'nullable' => false,
                'default' => \Swatch\Content\Model\Import\TypeInterface::STATUS_READY
            ],
            'Status'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            [
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT
            ],
            'Created at'
        )->addColumn(
            'total',
            Table::TYPE_INTEGER,
            11,
            [
                'unsigned' => true,
                'nullable' => true,
                'default' => 1,
            ],
            'Total items'
        )->addColumn(
            'imported',
            Table::TYPE_INTEGER,
            11,
            [
                'unsigned' => true,
                'nullable' => true,
                'default' => 0
            ],
            'Imported item count'
        )->addIndex(
            $setup->getIdxName('swatch_import_file', ['type']),
            ['type']
        )->addIndex(
            $setup->getIdxName('swatch_import_file', ['status']),
            ['status']
        )->setComment(
            'Swatch import file'
        );
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }

}
