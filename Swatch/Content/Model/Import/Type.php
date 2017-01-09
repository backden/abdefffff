<?php
/**
 * Copyright © 2016 Swatch. All rights reserved.
 */

namespace Swatch\Content\Model\Import;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;

/**
 * Class Type
 * @package Swatch\Content\Model\Import
 */
class Type extends DataObject implements TypeInterface
{
    /** @var ScopeConfigInterface  */
    protected $_scopeConfig;

    /**
     * Type constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(ScopeConfigInterface $scopeConfig, array $data = [])
    {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($data);
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getData('code');
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->getData('source');
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->getData('mode');
    }

    /**
     * @return bool
     */
    public function isAuto()
    {
        return !!$this->getData('is_auto');
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return (int)$this->getData('status');
    }

    /**
     * @return string
     */
    protected function getModeConfigName()
    {
        return sprintf('%s/mode', self::XML_PATH_CONTENT_IMPORT, $this->getCode());
    }
}