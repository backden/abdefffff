<?php

namespace Swatch\Localization\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Upgrade Data script
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{

    /** @var  Directory\Data\InstallData */
    protected $directoryInstallData;

    /** @var \Magento\Customer\Model\AttributeFactory */
    protected $attrFactory;

    /** @var \Magento\Store\Model\WebsiteFactory */
    protected $websiteFactory;

    /** @var  \Magento\Eav\Model\Entity\Type */
    protected $_entityType;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * @var \Magento\Catalog\Setup\CategorySetupFactory|CategorySetupFactory
     */
    private $_categorySetupFactory;

    /**
     * UpgradeData constructor.
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory,
        Directory\Data\InstallDataFactory $installDataFactory,
        \Magento\Customer\Model\AttributeFactory $attrFactory,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
        \Magento\Eav\Model\Config $eavConfig
    )
    {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->attrFactory = $attrFactory;
        $this->websiteFactory = $websiteFactory;
        $this->eavConfig = $eavConfig;
        $this->_categorySetupFactory = $categorySetupFactory;
        $this->directoryInstallData = $installDataFactory->create();
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.1') < 0) {
            //update lastname to not required and invisible
            $eavSetup->updateAttribute('customer', 'lastname', 'is_required', 0);
            $eavSetup->updateAttribute('customer', 'lastname', 'is_visible', 0);
            $eavSetup->updateAttribute('customer_address', 'lastname', 'is_required', 0);
            $eavSetup->updateAttribute('customer_address', 'lastname', 'is_visible', 0);
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.1') < 0) {
            //Install region for Japan
            $this->directoryInstallData->install($setup, 'region', 'Japan', ['en_US', 'ja_JP']);

            //Install localization for China
            $this->directoryInstallData->install($setup, 'region', 'China', ['en_US', 'zh_CN']);
            $this->directoryInstallData->install($setup, 'city', 'China', ['zh_CN']);
            $this->directoryInstallData->install($setup, 'district', 'China', ['zh_CN']);
            //Install custom attribute address
            $customerAddressAttributes = [
                'city_id' => [
                    'frontend_label' =>
                        [
                            0 => 'City',
                        ],
                    'attribute_code' => 'city_id',
                    'frontend_input' => 'hidden',
                    'is_required' => 0,
                    'is_visible' => '1',
                    'sort_order' => '102',
                    'used_in_forms' =>
                        [
                            0 => 'customer_register_address',
                            1 => 'adminhtml_customer_address',
                            2 => 'customer_address_edit',
                        ],
                    'backend_type' => 'int',
                    'source_model' => 'Swatch\Localization\Model\ResourceModel\Address\Attribute\Source\City',
                    'backend_model' => '',
                    'is_user_defined' => 1,
                    'is_system' => 0,
                    'attribute_set_id' => '2',
                    'attribute_group_id' => '2',
                    'validate_rules' => [],
                ],

                'district' => [
                    'frontend_label' =>
                        [
                            0 => 'District',
                        ],
                    'attribute_code' => 'district',
                    'frontend_input' => 'text',
                    'is_required' => 0,
                    'is_visible' => '1',
                    'sort_order' => '103',
                    'used_in_forms' =>
                        [
                            0 => 'customer_register_address',
                            1 => 'adminhtml_customer_address',
                            2 => 'customer_address_edit',
                        ],
                    'backend_type' => 'varchar',
                    'source_model' => NULL,
                    'backend_model' => 'Swatch\Localization\Model\ResourceModel\Address\Attribute\Backend\District',
                    'is_user_defined' => 1,
                    'is_system' => 0,
                    'attribute_set_id' => '2',
                    'attribute_group_id' => '2',
                    'validate_rules' => [],
                ],

                'district_id' => [
                    'frontend_label' =>
                        [
                            0 => 'District',
                        ],
                    'attribute_code' => 'district_id',
                    'frontend_input' => 'hidden',
                    'is_required' => 0,
                    'is_visible' => '1',
                    'sort_order' => '104',
                    'used_in_forms' =>
                        [
                            0 => 'customer_register_address',
                            1 => 'adminhtml_customer_address',
                            2 => 'customer_address_edit',
                        ],
                    'backend_type' => 'int',
                    'source_model' => 'Swatch\Localization\Model\ResourceModel\Address\Attribute\Source\District',
                    'backend_model' => '',
                    'is_user_defined' => 1,
                    'is_system' => 0,
                    'attribute_set_id' => '2',
                    'attribute_group_id' => '2',
                    'validate_rules' => [],
                ],
            ];
            foreach ($customerAddressAttributes as $attributeData) {
                $attributeObject = $this->_initAttribute('customer_address');
                $attributeObject->addData($attributeData);
                $attributeObject->save();
            }
            //Update city attribute
            $cityAttribute = $this->_initAttribute('customer_address', 'city');
            if ($cityAttribute->getId()) {
                $cityAttribute->setBackendModel('Swatch\Localization\Model\ResourceModel\Address\Attribute\Backend\City');
                $cityAttribute->setSortOrder(101);
                $cityAttribute->save();
            }
            //Update sort order
            $attribute = $this->_initAttribute('customer_address','street');
            if ($attribute->getId()) {
                $attribute->setSortOrder(110);
                $attribute->save();
            }
            $attribute = $this->_initAttribute('customer_address','postcode');
            if ($attribute->getId()) {
                $attribute->setSortOrder(70);
                $attribute->save();
            }
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.2') < 0) {
            //Update for district
            $select = $setup->getConnection()->select()
                ->join(
                    ['name' => $setup->getTable('directory_country_district_name')],
                    'district.district_id = name.district_id and name.locale = \'zh_CN\'',
                    ['default_name' => 'name.name']
                )
                ->where('country_id = \'CN\'');
            ;
            $select = $setup->getConnection()->updateFromSelect(
                $select,
                ['district' => $setup->getTable('directory_country_district')]
            );

            $setup->getConnection()->query($select);

            // update for city
            $select = $setup->getConnection()->select()
                ->join(
                    ['name' => $setup->getTable('directory_country_city_name')],
                    'city.city_id = name.city_id and name.locale = \'zh_CN\'',
                    ['default_name' => 'name.name']
                )
                ->where('country_id = \'CN\'');
            ;
            $select = $setup->getConnection()->updateFromSelect(
                $select,
                ['city' => $setup->getTable('directory_country_city')]
            );
            $setup->getConnection()->query($select);

            // update for region
            $select = $setup->getConnection()->select()
                ->join(
                    ['name' => $setup->getTable('directory_country_region_name')],
                    'region.region_id = name.region_id and name.locale = \'zh_CN\'',
                    ['name' => 'name.name']
                )
                ->where('region.locale = \'en_US\'');
            ;
            $select = $setup->getConnection()->updateFromSelect(
                $select,
                ['region' => $setup->getTable('directory_country_region_name')]
            );
            $setup->getConnection()->query($select);
        }
    }

    /**
     * Init attribute
     * @param string $entityType
     * @param string $attributeCode
     * @return \Magento\Customer\Model\Attribute
     */
    protected function _initAttribute($entityType, $attributeCode = null)
    {
        $typeId = $this->_getEntityType($entityType)->getId();
        /** @var $attribute \Magento\Customer\Model\Attribute */
        $attribute = $this->attrFactory->create();
        $attribute->setEntityTypeId($typeId);
        if ($attributeCode !== null) {
            $attribute->loadByCode($typeId, $attributeCode);
        }
        if ($attribute->getWebsite() == null) {
            $website = $this->websiteFactory->create();
            $attribute->setWebsite($website);
        }
        return $attribute;
    }

    /**
     * Gen entity Type
     * @param string $type
     * @return \Magento\Eav\Model\Entity\Type
     */
    protected function _getEntityType($type)
    {
        if ($this->_entityType === null) {
            $this->_entityType = $this->eavConfig->getEntityType($type);
        }
        return $this->_entityType;
    }
}
