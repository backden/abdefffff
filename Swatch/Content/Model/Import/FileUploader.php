<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import;


use Swatch\Content\Model\FileProcessorFactory;

/**
 * Class FileUploader
 * @package Swatch\Content\Model\Import
 */

class FileUploader extends \Swatch\Content\Model\FileUploader
{
    /** @var  string */
    protected $importType;
    /** @var TypeListInterface  */
    protected $typeList;

    public function __construct(FileProcessorFactory $fileProcessorFactory, TypeListInterface $typeList)
    {
        $this->typeList = $typeList;
        parent::__construct($fileProcessorFactory);
    }

    /**
     * @param $typeCode
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setImportType($typeCode)
    {
        $types = $this->typeList->getTypes();
        if (isset($types[$typeCode])) {
            $this->importType = $typeCode;
        }
        else
        {
            throw new \Magento\Framework\Exception\LocalizedException(__('Import type does not defined'));
        }
        return $this;
    }
}