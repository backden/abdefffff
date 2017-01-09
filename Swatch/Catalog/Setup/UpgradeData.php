<?php
namespace Swatch\Catalog\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Class UpgradeData
 * @package Swatch\Catalog\Setup
 */
class UpgradeData implements UpgradeDataInterface  {

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $_eavSetupFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * UpgradeData constructor.
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory  $eavSetupFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_productFactory = $productFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {

        $entityTypeId = $this->_productFactory->create()->getResource()->getTypeId();
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            //update searchable for attribute
            $eavSetup->updateAttribute($entityTypeId, 'color', 'is_searchable', 0);
            $eavSetup->updateAttribute($entityTypeId, 'description', 'is_searchable', 0);
            $eavSetup->updateAttribute($entityTypeId, 'manufacturer', 'is_searchable', 0);
            $eavSetup->updateAttribute($entityTypeId, 'price', 'is_searchable', 0);
            $eavSetup->updateAttribute($entityTypeId, 'short_description', 'is_searchable', 0);
            $eavSetup->updateAttribute($entityTypeId, 'tax_class_id', 'is_searchable', 0);
            $eavSetup->updateAttribute($entityTypeId, 'status', 'is_searchable', 0);

            $eavSetup->updateAttribute($entityTypeId, 'name', 'is_searchable', 1);
            $eavSetup->updateAttribute($entityTypeId, 'sku', 'is_searchable', 1);
            $eavSetup->updateAttribute($entityTypeId, 'locale_name', 'is_searchable', 1);
            $eavSetup->updateAttribute($entityTypeId, 'product_code', 'is_searchable', 1);
            $eavSetup->updateAttribute($entityTypeId, 'ean', 'is_searchable', 1);
            $eavSetup->updateAttribute($entityTypeId, 'keywords', 'is_searchable', 1);
        }
    }
}
