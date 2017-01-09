<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Localization\Plugin\Checkout\Block\Checkout;

/**
 * Class AttributeMerger
 * @package Swatch\Localization\Plugin\Checkout\Block\Checkout
 */
class AttributeMerger
{
    /**
     * After Merge
     * @param \Magento\Checkout\Block\Checkout\AttributeMerger $subject
     * @param array $fields
     * @return array
     */
    public function afterMerge($subject, $fields)
    {
        if (isset($fields['country_id']) && isset($fields['country_id']['options']) && empty($fields['country_id']['value'])) {
            $fields['country_id']['value'] = '';
        }
        if (isset($fields['district_id']) && empty($fields['district_id']['visible'])) {
            $fields['district_id'] = [];
        }
        return $fields;
    }

    /**
     * Merge additional address fields for given provider
     *
     * @param $subject
     * @param $proceed
     * @param array $elements
     * @param string $providerName name of the storage container used by UI component
     * @param string $dataScopePrefix
     * @param array $fields
     * @return array
     */
    public function aroundMerge($subject, \Closure $proceed, $elements, $providerName, $dataScopePrefix, array $fields = [])
    {
        $result = $proceed($elements, $providerName, $dataScopePrefix, $fields);
        foreach ($elements as $attributeCode => $attributeConfig) {
            if (isset($attributeConfig['formElement']) && $attributeConfig['formElement'] == 'image' && isset($result[$attributeCode])) {
                $additionalConfig = isset($fields[$attributeCode]) ? $fields[$attributeCode] : [];
                if (isset($additionalConfig['config'])) {
                    $result[$attributeCode]['config'] = array_merge($result[$attributeCode]['config'],$additionalConfig['config']);
                }
                if (isset($result[$attributeCode]['validation']['max_file_size'])) {
                   $result[$attributeCode]['config']['maxFileSize'] = $result[$attributeCode]['validation']['max_file_size'];
                }
            }
        }
        return $result;

    }
}