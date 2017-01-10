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
        return $this->setData(self::TRANSLATE_ID, $id);
    }

    /**
     * @param int $storeId
     * @return void
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @param string $section
     * @return void
     */
    public function setSection($section)
    {
        return $this->setData(self::SECTION_NAME, $section);
    }

    /**
     * @param string $string
     * @return void
     */
    public function setString($string)
    {
        return $this->setData(self::STRING_LABEL, $string);
    }

    /**
     * @param string $translate
     * @return void
     */
    public function setTranslate($translate)
    {
        return $this->setData(self::TRANSLATE_LABEL, $translate);
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
}
