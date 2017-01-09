<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Swatch\Content\Model;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class FileUploader
 * @package Swatch\Content\Model
 */
class FileUploader
{
    /**
     * @var FileProcessorFactory
     */
    protected $fileProcessorFactory;

    /**
     * FileUploader constructor.
     * @param FileProcessorFactory $fileProcessorFactory
     */
    public function __construct(FileProcessorFactory $fileProcessorFactory)
    {
        $this->fileProcessorFactory = $fileProcessorFactory;
    }

    /**
     * Validate uploaded file
     *
     * @return array|bool
     */
    public function validate()
    {
        return $errors = [];
    }

    /**
     * Execute file uploading
     *
     * @return \string[]
     * @throws LocalizedException
     */
    public function upload()
    {
        return [];
    }

    /**
     * Retrieve data from global $_FILES array
     *
     * @return array
     */
    private function getData()
    {
        $data = [];
        return $data;
    }

    /**
     * Get allowed extensions
     *
     * @return array
     */
    private function getAllowedExtensions()
    {
        return ['csv','xml'];
    }
}
