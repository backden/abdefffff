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
     * Get translate_id
     * @return string
     */
    public function getTranslateId()
    {
        return $this->getData(self::TRANSLATE_ID);
    }

    /**
     * Set translate_id
     * @param string $translateId
     * @return Swatch\ManageLabel\Api\Data\TranslateInterface
     */
    public function setTranslateId($translateId)
    {
        return $this->setData(self::TRANSLATE_ID, $translateId);
    }

    /**
     * Get id
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }

    /**
     * Set id
     * @param string $id
     * @return Swatch\ManageLabel\Api\Data\TranslateInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }
}
