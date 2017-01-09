<?php

/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Block\Adminhtml\Form;
/**
 * Class FileUploader
 * @package Swatch\Content\Block\Adminhtml\Import\Form
 */
class FileUploader extends \Magento\Backend\Block\Template
{
    /** @var string  */
    protected $_template = 'form/uploader.phtml';

    /** @var  \Swatch\Content\Model\Import\ResourceModel\File\Collection */
    protected $_fileCollection;

    /**
     * FileUploader constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Swatch\Content\Model\Import\ResourceModel\File\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(\Magento\Backend\Block\Template\Context $context,
                                \Swatch\Content\Model\Import\ResourceModel\File\CollectionFactory $collectionFactory,
                                array $data = [])
    {
        $this->_fileCollection = $collectionFactory->create();
        parent::__construct($context, $data);
    }

    /**
     * @return \Swatch\Content\Model\Import\ResourceModel\File\Collection
     */
    public function getFiles()
    {
        $this->_fileCollection->addFieldToFilter('type', $this->getCode());
        return $this->_fileCollection;
    }
}