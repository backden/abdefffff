<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 09/01/2017
 * Time: 16:54
 */

namespace Swatch\ManageLabel\Block\Adminhtml\System\Config;


class Form extends \Magento\Config\Block\System\Config\Form
{
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
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $configFactory, $configStructure, $fieldsetFactory, $fieldFactory, $data);
    }
}