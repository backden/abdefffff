<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 09/01/2017
 * Time: 16:54
 */

namespace Swatch\ManageLabel\Block\Adminhtml\System\Config;


use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;

class Form extends \Magento\Config\Block\System\Config\Form
{
    protected $sectionTranslate;

    /**
     * @var TranslateRepositoryInterface $translateRepository
     */
    protected $translateRepository;

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Config\Model\Config\Factory $configFactory
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param \Magento\Config\Block\System\Config\Form\Fieldset\Factory $fieldsetFactory
     * @param \Magento\Config\Block\System\Config\Form\Field\Factory $fieldFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Factory $configFactory,
        \Swatch\ManageLabel\Model\Config\Structure\Structure $configStructure,
        \Magento\Config\Block\System\Config\Form\Fieldset\Factory $fieldsetFactory,
        \Magento\Config\Block\System\Config\Form\Field\Factory $fieldFactory,
        TranslateRepositoryInterface $translateRepository,
        array $data = []
    ) {
        $this->translateRepository = $translateRepository;
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $configFactory,
            $configStructure,
            $fieldsetFactory,
            $fieldFactory,
            $data
        );
    }

    /**
     * Initialize form element
     *
     * @param \Magento\Config\Model\Config\Structure\Element\Field $field
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param string $path
     * @param string $fieldPrefix
     * @param string $labelPrefix
     * @return void
     */
    protected function _initElement(
        \Magento\Config\Model\Config\Structure\Element\Field $field,
        \Magento\Framework\Data\Form\Element\Fieldset $fieldset,
        $path,
        $fieldPrefix = '',
        $labelPrefix = ''
    ) {
        // Set "default checkbox"
        $inherit = true;
        $isUseDefault = $this->canUseDefaultValue($field->showInDefault());
        $data = $this->getSwatchTranslateData($path);

        if (is_null($data)) {
            // Use default value
            $data = $this->getSwatchTranslateData($path, true);
        } elseif ($data && !$data->isUseDefault()) {
            // Value was defined by user
            $inherit = false;
        }

        $fieldRendererClass = $field->getFrontendModel();
        if ($fieldRendererClass) {
            $fieldRenderer = $this->_layout->getBlockSingleton($fieldRendererClass);
        } else {
            $fieldRenderer = $this->_fieldRenderer;
        }

        $fieldRenderer->setForm($this);
        $fieldRenderer->setConfigData($this->_configData);

        $elementName = $this->_generateElementName($field->getPath(), $fieldPrefix);
        $elementId = $this->_generateElementId($field->getPath($fieldPrefix));

        $dependencies = $field->getDependencies($fieldPrefix, $this->getStoreCode());
        $this->_populateDependenciesBlock($dependencies, $elementId, $elementName);

        $sharedClass = $this->_getSharedCssClass($field);
        $requiresClass = $this->_getRequiresCssClass($field, $fieldPrefix);

        // Get all data of field
        $fielData = $field->getData();

        $isReadOnly = false;
        $formField = $fieldset->addField(
            $elementId,
            $field->getType(),
            [
                'name' => $elementName,
                'label' => $labelPrefix . " " . (isset($fielData['label']) ? $fielData['label'] : ''),
                'comment' => $field->getComment($data),
                'tooltip' => $field->getTooltip(),
                'hint' => $field->getHint(),
                'value' => is_null($data) ? '' : $data->getTranslate(),
                'inherit' => $inherit,
                'class' => $field->getFrontendClass() . $sharedClass . $requiresClass,
                'field_config' => $field->getData(),
                'scope' => $this->getScope(),
                'scope_id' => $this->getScopeId(),
                'scope_label' => $this->getScopeLabel($field),
                'can_use_default_value' => $isUseDefault,
                'can_use_website_value' => false,//$this->canUseWebsiteValue($field->showInWebsite()),
                'can_restore_to_default' => $this->isCanRestoreToDefault($field->canRestore()),
                'disabled' => $isReadOnly,
                'is_disable_inheritance' => $isReadOnly
            ]
        );
        $field->populateInput($formField);

        if ($field->hasValidation()) {
            $formField->addClass($field->getValidation());
        }
        $formField->setRenderer($fieldRenderer);
    }

    /**
     * Fetch data of Swatch Translate table by path
     * @param $path
     * @return mixed|null
     */
    public function getSwatchTranslateData($path, $useDefault = false)
    {
        if (strpos($path, '/')) {
            $exploded = explode('/', $path);
            $section = $exploded[0];
            $group = $exploded[1];
            $string = $exploded[2];
            $translates = $this->translateRepository
                ->fetchSectionData($this->getStoreCode(), $section, $useDefault);
            if (!empty($translates) && !empty($string)) {
                foreach ($translates as $translate) {
                    if ($translate->getIdString() === $string && $translate->isVisible()) {
                        return $translate;
                    }
                }
            }
        }
        return null;
    }

}