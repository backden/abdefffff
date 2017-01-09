<?php

/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Plugin\Customer\Model;
/**
 * Class Attribute
 * @package Swatch\Localization\Plugin\Customer\Model
 */
class Attribute
{
    /** @var \Magento\Customer\Model\AttributeFactory */
    protected $attrFactory;

    /**
     * @var array
     */
    protected $hiddenAttributes;

    /**
     * Attribute constructor.
     * @param \Magento\Customer\Model\AttributeFactory $attrFactory
     */
    public function __construct(\Magento\Customer\Model\AttributeFactory $attrFactory)
    {
        $this->attrFactory = $attrFactory;
        $this->hiddenAttributes = \Swatch\Localization\Helper\Address::getHiddenAttributes();
    }

    /**
     * After save
     * @param $subject
     * @param \Magento\Customer\Model\Attribute $attribute
     * @return \Magento\Customer\Model\Attribute
     */
    public function afterSave($subject, $attribute)
    {
        if (in_array($subject->getAttributeCode().'_id',$this->hiddenAttributes) ) {
            /** @var \Magento\Customer\Model\Attribute $attribute */
            $relateAttribute = $this->_initAttribute($attribute->getEntityTypeId(),$attribute->getAttributeCode().'_id');
            if ($relateAttribute->getId()) {
                $relateAttribute->setWebsite($attribute->getWebsite() );
                $relateAttribute->setIsVisible($attribute->getIsVisible() );
                $relateAttribute->setSortOrder($attribute->getSortOrder() );
                $relateAttribute->setScopeIsVisible($attribute->getScopeIsVisible() );
                $relateAttribute->save();
            }
        }
        return $attribute;
    }

    /**
     * Init attribute
     * @param $typeId
     * @param null $attributeCode
     * @return $this
     */
    protected function _initAttribute($typeId, $attributeCode = null)
    {
        /** @var $attribute \Magento\Customer\Model\Attribute */
        $attribute = $this->attrFactory->create();
        return $attribute->loadByCode($typeId, $attributeCode);
    }
}