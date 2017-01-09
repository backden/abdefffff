<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Block\Adminhtml\Import;


use Swatch\Content\Model\Import\TypeListInterface;

/**
 * Class Uploader
 * @package Swatch\Content\Block\Adminhtml\Import
 */
class Uploader extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var TypeListInterface
     */
    protected $_importTypeList;

    /**
     * Uploader constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param TypeListInterface $importTypeList
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Magento\Framework\Json\EncoderInterface $jsonEncoder,
                                \Magento\Backend\Model\Auth\Session $authSession,
                                TypeListInterface $importTypeList,
                                array $data = [])
    {
        parent::__construct($context, $jsonEncoder, $authSession, $data);
        $this->_importTypeList = $importTypeList;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('uploader_tab');
        $this->setDestElementId('uploader_tab_content');
    }
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $isFirst = true;
        foreach ($this->_importTypeList->getTypes() as $code => $type) {
            $uploader = $this->getLayout()->createBlock('Swatch\Content\Block\Adminhtml\Form\FileUploader');
            $uploader->setHtmlId('import-uploader-'.$code);
            $uploader->setCode($code);
            $this->addTab(
                $code,
                [
                    'label' => $type->getType(),
                    'content' => $uploader->toHtml(),
                    'active' => $isFirst
                ]
            );
            $isFirst = false;
        }
        return parent::_prepareLayout();
    }
}