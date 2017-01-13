<?php
/**
 * Manage Label Of StoreView
 * Copyright (C) 2016  
 * 
 * This file included in Swatch/ManageLabel is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Swatch\ManageLabel\Model;

use Swatch\ManageLabel\Api\Data\TranslateInterface;

class Translate extends \Magento\Framework\Model\AbstractModel implements TranslateInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Swatch\ManageLabel\Model\ResourceModel\Translate');
    }

    /**
     * Get id
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::TRANSLATE_ID);
    }

    /**
     * Set id
     * @param string $id
     * @return \Swatch\ManageLabel\Api\Data\TranslateInterface
     */
    public function setId($id)
    {
        $this->setData(self::TRANSLATE_ID, $id);
        return $this;
    }

    /**
     * @param int $storeId
     * @return void
     */
    public function setStoreId($storeId)
    {
        $this->setData(self::STORE_ID, $storeId);
        return $this;
    }

    /**
     * @param string $section
     * @return void
     */
    public function setSection($section)
    {
        $this->setData(self::SECTION_NAME, $section);
        return $this;
    }

    /**
     * @param string $string
     * @return void
     */
    public function setString($string)
    {
        $this->setData(self::STRING_LABEL, $string);
        return $this;
    }

    /**
     * @param string $translate
     * @return void
     */
    public function setTranslate($translate)
    {
        $this->setData(self::TRANSLATE_LABEL, $translate);
        return $this;
    }

    /**
     * @param $storeId
     * @return int
     */
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @param $section
     * @return string
     */
    public function getSection()
    {
        return $this->getData(self::SECTION_NAME);
    }

    /**
     * @param $string
     * @return string
     */
    public function getString()
    {
        return $this->getData(self::STRING_LABEL);
    }

    /**
     * @param $translate
     * @return string
     */
    public function getTranslate()
    {
        return $this->getData(self::TRANSLATE_LABEL);
    }

    public function setUseDefault($useDefault)
    {
        $this->setData(self::USE_DEFAULT, $useDefault);
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseDefault()
    {
        return $this->getData(self::USE_DEFAULT);
    }

    /**
     * @param string $idString
     * @return mixed
     */
    public function setIdString($idString)
    {
        $this->setData(self::ID_LABEL, $idString);
        return $this;
    }

    /**
     * @return string
     */
    public function getIdString()
    {
        return $this->getData(self::ID_LABEL);
    }

    /**
     * @param bool $visible
     * @return mixed
     */
    public function setIsVisible($visible)
    {
        $this->setData(self::IS_VISIBLE, $visible);
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->getData(self::IS_VISIBLE);
    }
}
