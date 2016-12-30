<?php
namespace Isobar\Localization\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package Isobar\Localization\Setup
 */
class InstallData implements InstallDataInterface
{
    /** @var  Directory\Data\InstallData */
    protected $directoryInstallData;
    /** @var \Magento\Framework\Event\ManagerInterface */
    protected $eventManager;
    /** @var \Magento\Customer\Model\AttributeFactory */
    protected $attrFactory;
    /** @var \Magento\Store\Model\WebsiteFactory */
    protected $websiteFactory;
    /** @var  \Magento\Eav\Model\Entity\Type */
    protected $_entityType;
    /** @var \Magento\Eav\Model\Config */
    protected $eavConfig;

    /**
     * InstallData constructor.
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Customer\Model\AttributeFactory $attrFactory
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param Directory\Data\InstallDataFactory $installDataFactory
     */
    public function __construct(\Magento\Framework\Event\ManagerInterface $eventManager,
                                \Magento\Customer\Model\AttributeFactory $attrFactory,
                                \Magento\Store\Model\WebsiteFactory $websiteFactory,
                                \Magento\Eav\Model\Config $eavConfig,
                                Directory\Data\InstallDataFactory $installDataFactory)
    {
        $this->eventManager = $eventManager;
        $this->attrFactory = $attrFactory;
        $this->websiteFactory = $websiteFactory;
        $this->eavConfig = $eavConfig;
        $this->directoryInstallData = $installDataFactory->create();
    }

    /**
     * Function install
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        //Install region for Japan
        $this->directoryInstallData->install($setup, 'region', 'Japan', ['en_US', 'ja_JP']);

        //Install localization for China
        $this->directoryInstallData->install($setup, 'region', 'China', ['zh_CN']);
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
                'source_model' => 'Isobar\Localization\Model\ResourceModel\Address\Attribute\Source\City',
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
                'backend_model' => 'Isobar\Localization\Model\ResourceModel\Address\Attribute\Backend\District',
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
                'source_model' => 'Isobar\Localization\Model\ResourceModel\Address\Attribute\Source\District',
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
            $cityAttribute->setBackendModel('Isobar\Localization\Model\ResourceModel\Address\Attribute\Backend\City');
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