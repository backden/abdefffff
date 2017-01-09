<?php

namespace Swatch\Localization\Block\Checkout;

/**
 * Class LayoutProcessor
 * @package Swatch\Checkout\Block\Checkout
 */
class LayoutProcessor extends \Magento\Checkout\Block\Checkout\LayoutProcessor
{
    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var \Magento\Ui\Component\Form\AttributeMapper
     */
    protected $attributeMapper;

    /**
     * @var \Magento\Checkout\Block\Checkout\AttributeMerger
     */
    protected $merger;

    /**
     * @var \Magento\Customer\Model\Options
     */
    private $options;

    /**
     * LayoutProcessor constructor.
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     * @param \Magento\Ui\Component\Form\AttributeMapper $attributeMapper
     * @param \Magento\Checkout\Block\Checkout\AttributeMerger $merger
     * @param \Magento\Customer\Model\Options $options
     *
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider,
        \Magento\Ui\Component\Form\AttributeMapper $attributeMapper,
        \Magento\Checkout\Block\Checkout\AttributeMerger $merger,
        \Magento\Customer\Model\Options $options
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
        $this->attributeMapper = $attributeMapper;
        $this->merger = $merger;
        $this->options = $options;
    }

    /**
     * Get option
     * @deprecated
     * @return \Magento\Customer\Model\Options
     */
    private function getOptions()
    {
        return $this->options;
    }

    /**
     * Get address attribute
     * @return array
     */
    private function getAddressAttributes()
    {
        /** @var \Magento\Eav\Api\Data\AttributeInterface[] $attributes */
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer_address',
            'customer_register_address'
        );

        $elements = [];
        foreach ($attributes as $attribute) {
            $code = $attribute->getAttributeCode();
            if ($attribute->getIsUserDefined()) {
                if (!in_array($code, \Swatch\Localization\Helper\Address::getAdditionalAttributes())){
                    continue;
                }
            }
            $elements[$code] = $this->attributeMapper->map($attribute);
            if (isset($elements[$code]['label'])) {
                $label = $elements[$code]['label'];
                $elements[$code]['label'] = __($label);
            }
        }
        return $elements;
    }

    /**
     * Convert elements(like prefix and suffix) from inputs to selects when necessary
     *
     * @param array $elements address attributes
     * @param array $attributesToConvert fields and their callbacks
     * @return array
     */
    private function convertElementsToSelect($elements, $attributesToConvert)
    {
        $codes = array_keys($attributesToConvert);
        foreach (array_keys($elements) as $code) {
            if (!in_array($code, $codes)) {
                continue;
            }
            $options = call_user_func($attributesToConvert[$code]);
            if (!is_array($options)) {
                continue;
            }
            $elements[$code]['dataType'] = 'select';
            $elements[$code]['formElement'] = 'select';

            foreach ($options as $key => $value) {
                $elements[$code]['options'][] = [
                    'value' => $key,
                    'label' => $value,
                ];
            }
        }

        return $elements;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $attributesToConvert = [
            'prefix' => [$this->getOptions(), 'getNamePrefixOptions'],
            'suffix' => [$this->getOptions(), 'getNameSuffixOptions'],
        ];

        $elements = $this->getAddressAttributes();
        $elements = $this->convertElementsToSelect($elements, $attributesToConvert);

        // The following code is a workaround for custom address attributes
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']
        )) {
            if (!isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'])) {
                $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'] = [];
            }

            $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['payments-list']['children'] =
                array_merge_recursive(
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'],
                    $this->processPaymentConfiguration(
                        $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                        ['payment']['children']['renders']['children'],
                        $elements
                    )
                );
        }

        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children']
        )) {
            $fields = $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'] = $this->merger->merge(
                $elements,
                'checkoutProvider',
                'shippingAddress',
                $fields
            );
        }
        return $jsLayout;
    }

    /**
     * Inject billing address component into every payment component
     *
     * @param array $configuration list of payment components
     * @param array $elements attributes that must be displayed in address form
     * @return array
     */
    private function processPaymentConfiguration(array &$configuration, array $elements)
    {
        $output = [];
        foreach ($configuration as $paymentGroup => $groupConfig) {
            foreach ($groupConfig['methods'] as $paymentCode => $paymentComponent) {
                if (empty($paymentComponent['isBillingAddressRequired'])) {
                    continue;
                }
                $output[$paymentCode . '-form'] = [
                    'component' => 'Magento_Checkout/js/view/billing-address',
                    'displayArea' => 'billing-address-form-' . $paymentCode,
                    'provider' => 'checkoutProvider',
                    'deps' => 'checkoutProvider',
                    'dataScopePrefix' => 'billingAddress' . $paymentCode,
                    'sortOrder' => 1,
                    'children' => [
                        'form-fields' => [
                            'component' => 'uiComponent',
                            'displayArea' => 'additional-fieldsets',
                            'children' => $this->merger->merge(
                                $elements,
                                'checkoutProvider',
                                'billingAddress' . $paymentCode,
                                [
                                    'firstname_input' => [
                                        'component' => 'Magento_Ui/js/form/element/abstract',
                                        'deps'    => 'billingAddress' . $paymentCode . '.firstname_katakana'
                                    ],
                                    'lastname_input' => [
                                        'component' => 'Magento_Ui/js/form/element/abstract',
                                        'deps'    => 'billingAddress' . $paymentCode . '.firstname'
                                    ],
                                    'firstname' => [
                                        'validation' => [
                                            'max_text_length' => 30,
                                        ],
                                    ],
                                    'lastname' => [
                                        'validation' => [
                                            'max_text_length' => 30,
                                        ],
                                    ],
                                    'country_id' => [
                                        'sortOrder' => 67,
                                    ],
                                    'region' => [
                                        'visible' => false,
                                    ],
                                    'region_id' => [
                                        'component' => 'Magento_Ui/js/form/element/region',
                                        'config' => [
                                            'template' => 'ui/form/field',
                                            'elementTmpl' => 'ui/form/element/select',
                                            'customEntry' => 'billingAddress' . $paymentCode . '.region',
                                        ],
                                        'validation' => [
                                            'required-entry' => true,
                                        ],
                                        'filterBy' => [
                                            'target' => '${ $.provider }:${ $.parentScope }.country_id',
                                            'field' => 'country_id',
                                        ],
                                    ],
                                    'region_id_input' =>[
                                        'component' => 'Magento_Ui/js/form/element/abstract',
                                        'deps'    => 'billingAddress' . $paymentCode . '.region_id'
                                    ],
                                    'city' => [
                                        'visible' => false,
                                    ],
                                    'city_id' => [
                                        'component' => 'Swatch_Localization/js/form/element/city',
                                        'config' =>[
                                            'customEntry' => 'billingAddress' . $paymentCode . '.city',
                                            'template' => 'ui/form/field',
                                            'elementTmpl' => 'ui/form/element/select',
                                        ],
                                        'filterBy' => [
                                            'target' => '${ $.provider }:${ $.parentScope }.region_id',
                                            'field' => 'region_id',
                                        ],
                                    ],
                                    'city_id_input' =>[
                                        'component' => 'Magento_Ui/js/form/element/abstract',
                                        'deps'    => 'billingAddress' . $paymentCode . '.city_id'
                                    ],
                                    'district' => [
                                        'visible' => false,
                                    ],
                                    'district_id' => $elements['district_id']['visible']?[
                                        'component' => 'Swatch_Localization/js/form/element/district',
                                        'config' => [
                                            'template' => 'ui/form/field',
                                            'elementTmpl' => 'ui/form/element/select',
                                            'customEntry' => 'billingAddress' . $paymentCode . '.district',
                                        ],
                                        'filterBy' => [
                                            'target' => '${ $.provider }:${ $.parentScope }.city_id',
                                            'field' => 'city_id',
                                        ],
                                    ]:[],
                                    'district_id_input' => $elements['district_id']['visible']?[
                                        'component' => 'Magento_Ui/js/form/element/abstract',
                                        'deps'    => 'billingAddress' . $paymentCode . '.district_id'
                                    ]:[],
                                    'postcode' => [
                                        'component' => 'Magento_Ui/js/form/element/post-code',
                                        'validation' => [
                                            'required-entry' => true,
                                            'max_text_length' => 10,
                                        ],
                                    ],
                                    'company' => [
                                        'validation' => [
                                            'min_text_length' => 0,
                                        ],
                                    ],
                                    'fax' => [
                                        'validation' => [
                                            'min_text_length' => 0,
                                        ],
                                    ],
                                    'telephone' => [
                                        'validation' => [
                                            'max_text_length' => 20,
                                            'validate-phone-number' => true,
                                        ],
                                        'config' => [
                                            'tooltip' => [
                                                'description' => __('For delivery questions.'),
                                            ],
                                        ],
                                    ],
                                ]
                            ),
                        ],
                    ],
                ];
            }
            unset($configuration[$paymentGroup]['methods']);
        }

        return $output;
    }

}
