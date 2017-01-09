<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import;

/**
 * Class File
 * @package Swatch\Content\Model\Import
 */
class File extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getData('filename');
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return (int)$this->getData('total');
    }

    /**
     * @return int
     */
    public function getImported()
    {
        return (int)$this->getData('imported');
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getData('type');
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getStatus()
    {
        switch ($this->getData('status')){
            case TypeInterface::STATUS_READY:
                return __('Ready');
            case TypeInterface::STATUS_IN_PROGRESS:
                return __('Processing');
            case TypeInterface::STATUS_COMPLETED:
                return __('Completed');
            default:
                return __('Unknown');
        }
    }
}