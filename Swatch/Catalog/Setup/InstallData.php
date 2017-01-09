<?php
namespace Swatch\Catalog\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class InstallData implements InstallDataInterface {

    const WATCHES = 'Watches';
    const GLASSES = 'Glasses';
    const BIJOUX = 'Bijoux';
    const STRAPS = 'Straps';

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $_eavSetupFactory;

    /**
     * @var \Magento\Catalog\Setup\CategorySetupFactory
     */
    protected $_categorySetupFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute\SetFactory
     */
    protected $_attributeSetFactory;

    /**
     * InstallData constructor.
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory  $eavSetupFactory,
        \Magento\Catalog\Setup\CategorySetupFactory $categorySetupFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Eav\Model\Entity\Attribute\SetFactory $attributeSetFactory
    ) {
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->_categorySetupFactory = $categorySetupFactory;
        $this->_productFactory = $productFactory;
        $this->_attributeSetFactory = $attributeSetFactory;
    }

    public function install( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {

        /**
         * Add attribute sets
         */
        $entityTypeId = $this->_productFactory->create()->getResource()->getTypeId();
        $defaultSet = $this->_attributeSetFactory->create()
            ->getCollection()
            ->addFieldToFilter('attribute_set_name','Default')
            ->addFieldToFilter('entity_type_id', $entityTypeId)
            ->setPageSize(1)
            ->getFirstItem();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Watches');
        $model->setSortOrder(2);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Bijoux');
        $model->setSortOrder(3);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Glasses');
        $model->setSortOrder(4);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Straps');
        $model->setSortOrder(5);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Front');
        $model->setSortOrder(6);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Battery');
        $model->setSortOrder(7);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();

        $model = $this->_attributeSetFactory->create()
            ->setEntityTypeId($entityTypeId);
        $model->setAttributeSetName('Accessories');
        $model->setSortOrder(8);
        $model->save();
        $model->initFromSkeleton($defaultSet->getId());
        $model->save();


        /**
         * Add custom product attributes
         */
        $attributes = [
            'image_name' => [
                'type' => 'varchar',
                'label' => 'Image name',
                'input' => 'media_image',
                'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'locale_name' => [
                'type' => 'varchar',
                'label' => 'Local name',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'ereservation' => [
                'type' => 'int',
                'label' => 'Ereservation',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'online_only' => [
                'type' => 'int',
                'label' => 'Online Only',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'display_sku' => [
                'type' => 'varchar',
                'label' => 'Display SKU',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'parent_sku' => [
                'type' => 'varchar',
                'label' => 'Parent SKU',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'is_set' => [
                'type' => 'int',
                'label' => 'Is Set',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'product_code' => [
                'type' => 'varchar',
                'label' => 'Product code',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'collection_code' => [
                'type' => 'varchar',
                'label' => 'Collection code',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'collection_desc' => [
                'type' => 'int',
                'label' => 'Collection desc',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'launch_date' => [
                'type' => 'int',
                'label' => 'Launch Date',
                'input' => 'date',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'collection_category' => [
                'type' => 'int',
                'label' => 'Collection Category',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'ean' => [
                'type' => 'varchar',
                'label' => 'Ean code',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'upc_code' => [
                'type' => 'varchar',
                'label' => 'Upc code',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'limited_edition' => [
                'type' => 'int',
                'label' => 'Limited edition',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'units_oft_ltd_edition' => [
                'type' => 'varchar',
                'label' => 'Units of Ltd Edition',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-1024',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'item_function_id' => [
                'type' => 'text',
                'label' => 'Item function ID',
                'input' => 'multiselect',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'is_flickflack' => [
                'type' => 'int',
                'label' => 'Is Flikflak',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'keywords' => [
                'type' => 'text',
                'label' => 'Kewords',
                'input' => 'textarea',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'angle_image' => [
                'type' => 'varchar',
                'label' => 'Angles Image',
                'input' => 'media_image',
                'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            '3d' => [
                'type' => 'int',
                'label' => '3D',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'sku_picture' => [
                'type' => 'varchar',
                'label' => 'New sku picture',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-1024',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'image_url_angle' => [
                'type' => 'varchar',
                'label' => 'Image url angle',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-1024',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'gender' =>  [
                'type' => 'int',
                'label' => 'Gender',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'dial_sub_color' =>  [
                'type' => 'int',
                'label' => 'Dial Sub Color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'index_color' =>  [
                'type' => 'int',
                'label' => 'Index Color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'crown_material' =>  [
                'type' => 'int',
                'label' => 'Crown Material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'glass_material' =>  [
                'type' => 'int',
                'label' => 'Glass Material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'size_type' =>  [
                'type' => 'int',
                'label' => 'SizeType',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'item_size' =>  [
                'type' => 'int',
                'label' => 'Item Size',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'min_size_millimeter' =>  [
                'type' => 'varchar',
                'label' => 'Min size',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'max_size_millimeter' =>  [
                'type' => 'varchar',
                'label' => 'Max size',
                'input' => 'text',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'frontend_class' => 'validate-length maximum-length-255',
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'bezel_material' =>  [
                'type' => 'int',
                'label' => 'Bezel Material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'bezel_function' =>  [
                'type' => 'int',
                'label' => 'Bezel function',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'movement' =>  [
                'type' => 'int',
                'label' => 'Movement',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'packaging_type' =>  [
                'type' => 'int',
                'label' => 'Packaging type',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'special_family' =>  [
                'type' => 'int',
                'label' => 'Special Family',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'water_proof_meter' =>  [
                'type' => 'varchar',
                'label' => 'Water Prof Meter',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-1024',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'artist' =>  [
                'type' => 'int',
                'label' => 'Artist',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'item_color' =>  [
                'type' => 'int',
                'label' => 'Item Color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'item_sub_color' =>  [
                'type' => 'int',
                'label' => 'Item sub color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'caliber' =>  [
                'type' => 'varchar',
                'label' => 'Caliber',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-1024',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'width_millimeter' =>  [
                'type' => 'varchar',
                'label' => 'Width millimeter',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'thickness_millimeter' =>  [
                'type' => 'varchar',
                'label' => 'Thickness millimeter',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'height_millimeter' =>  [
                'type' => 'varchar',
                'label' => 'Height Millimeter',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'battery_type' =>  [
                'type' => 'int',
                'label' => 'Battery type',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'is_washable' => [
                'type' => 'int',
                'label' => 'Is washable',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'strap_color' => [
                'type' => 'int',
                'label' => 'Strap color',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'strap_sub_color' => [
                'type' => 'int',
                'label' => 'Strap Sub Color',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'strap_material' => [
                'type' => 'int',
                'label' => 'Strap Material',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'strap_clasp_material' => [
                'type' => 'int',
                'label' => 'Strap Clasp Material',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'strap_clasp' => [
                'type' => 'int',
                'label' => 'Strap Clasp',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'dial_color' => [
                'type' => 'int',
                'label' => 'Dial color',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'case_color' => [
                'type' => 'int',
                'label' => 'Case Color',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'case_sub_color' => [
                'type' => 'int',
                'label' => 'Case Sub Color',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'case_shape' => [
                'type' => 'int',
                'label' => 'Case Shape',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'case_material' => [
                'type' => 'int',
                'label' => 'Case Material',
                'input' => 'select',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'case_size' => [
                'type' => 'varchar',
                'label' => 'Case Size',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'product_background_color' => [
                'type' => 'varchar',
                'label' => 'Background color',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'product_font_color' => [
                'type' => 'varchar',
                'label' => 'Font color',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-255',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'product_picture_background' => [
                'type' => 'varchar',
                'label' => 'Picture Background',
                'input' => 'media_image',
                'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'short_description_color' => [
                'type' => 'varchar',
                'label' => 'Short Description Color',
                'input' => 'text',
                'frontend_class' => 'validate-length maximum-length-1024',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'set_mat' =>  [
                'type' => 'int',
                'label' => 'Material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_style' =>  [
                'type' => 'int',
                'label' => 'Style',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_design' =>  [
                'type' => 'int',
                'label' => 'Design',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_pattern' =>  [
                'type' => 'int',
                'label' => 'Pattern',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_subcolor' =>  [
                'type' => 'int',
                'label' => 'Subcolor',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_polish' =>  [
                'type' => 'int',
                'label' => 'Polish',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_solidity' =>  [
                'type' => 'int',
                'label' => 'Solidity',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_size' =>  [
                'type' => 'int',
                'label' => 'Size',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_bridge' =>  [
                'type' => 'int',
                'label' => 'Bridge',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'fr_temples' =>  [
                'type' => 'int',
                'label' => 'Temples',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'le_color' =>  [
                'type' => 'int',
                'label' => 'Lens color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'le_type' =>  [
                'type' => 'int',
                'label' => 'Lens type',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'le_mat' =>  [
                'type' => 'int',
                'label' => 'Lens mat',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'le_uv' =>  [
                'type' => 'int',
                'label' => 'Lens uv',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'te_color' =>  [
                'type' => 'int',
                'label' => 'Temple color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'te_polish' =>  [
                'type' => 'int',
                'label' => 'Temple polish',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'te_solidity' =>  [
                'type' => 'int',
                'label' => 'Temple solidity',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'te_mat' =>  [
                'type' => 'int',
                'label' => 'Temple material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'te_mat2' =>  [
                'type' => 'int',
                'label' => 'Temple sub material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'hi_color' =>  [
                'type' => 'int',
                'label' => 'Hinges color',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'hi_polish' =>  [
                'type' => 'int',
                'label' => 'Hinges polish',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'hi_solidity' =>  [
                'type' => 'int',
                'label' => 'Hinges solidity',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
            'hi_mat' =>  [
                'type' => 'int',
                'label' => 'Hinges material',
                'input' => 'select',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'required' => false,
                'user_defined' => true,
                'group' => ''
            ],
        ];

        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);
        foreach ($attributes as $attrCode => $attr) {
            $eavSetup->addAttribute('catalog_product', $attrCode, $attr);
        }


        $attributesToSet = [
            'image_name' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'locale_name' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'ereservation' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'online_only' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'display_sku' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'parent_sku' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'is_set' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'product_code' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'collection_code' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'collection_desc' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'launch_date' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'collection_category' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'ean' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'upc_code' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'limited_edition' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'units_oft_ltd_edition' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'item_function_id' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'is_flickflack' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'keywords' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'angle_image' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            '3d' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'sku_picture' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'image_url_angle' => [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'gender' =>  [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'dial_sub_color' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'index_color' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'crown_material' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'glass_material' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'size_type' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'item_size' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'min_size_millimeter' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'max_size_millimeter' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'bezel_material' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'bezel_function' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'movement' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'packaging_type' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'special_family' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'water_proof_meter' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'artist' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => false
            ],
            'item_color' =>  [
                self::WATCHES => true,
                self::GLASSES => true,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'item_sub_color' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => true,
                self::STRAPS => true
            ],
            'caliber' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'width_millimeter' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'thickness_millimeter' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'height_millimeter' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'battery_type' =>  [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'is_washable' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'strap_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'strap_sub_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'strap_material' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'strap_clasp_material' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'strap_clasp' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => true
            ],
            'dial_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'case_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'case_sub_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'case_shape' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'case_material' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'case_size' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'product_background_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'product_font_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'product_picture_background' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'short_description_color' => [
                self::WATCHES => true,
                self::GLASSES => false,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'set_mat' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_style' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_design' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_pattern' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_subcolor' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_polish' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_solidity' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_size' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_bridge' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'fr_temples' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'le_color' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'le_type' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'le_mat' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'le_uv' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'te_color' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'te_polish' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'te_solidity' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'te_mat' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'te_mat2' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'hi_color' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'hi_polish' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'hi_solidity' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
            'hi_mat' =>  [
                self::WATCHES => false,
                self::GLASSES => true,
                self::BIJOUX => false,
                self::STRAPS => false
            ],
        ];

        // add attribute to attributeset
        foreach ($attributesToSet as $attributeCode=>$arrayAttributeSet) {
            foreach($arrayAttributeSet as $key=>$value){
                if($value){
                    $attributeSetId = $eavSetup->getAttributeSetId($entityTypeId, $key);
                    $eavSetup->addAttributeToSet($entityTypeId, $attributeSetId, 'General', $attributeCode);
                }
            }
        }
    }
}
