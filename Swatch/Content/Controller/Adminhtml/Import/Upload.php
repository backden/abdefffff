<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Swatch\Content\Model\FileProcessor;
use Swatch\Content\Model\FileUploader;

/**
 * Class Upload
 * @package Swatch\Content\Controller\Adminhtml\Import
 */
class Upload extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Swatch_Content::upload';

    /**
     * @var FileProcessor
     */
    protected $fileProcessor;

    /**
     * @param Context $context
     * @param FileProcessor $fileProcessor
     */
    public function __construct(
        Context $context,
        FileProcessor $fileProcessor
    ) {
        parent::__construct($context);
        $this->fileProcessor = $fileProcessor;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = [
            'error' => false,
            'name' => ''
            ];
        //;$this->fileProcessor->saveToTmp(key($_FILES));
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}