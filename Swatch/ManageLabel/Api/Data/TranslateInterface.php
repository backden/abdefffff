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

namespace Swatch\ManageLabel\Api\Data;

interface TranslateInterface
{

    const TRANSLATE_ID = 'translate_id';
    const ID = 'id';


    /**
     * Get translate_id
     * @return string|null
     */
    
    public function getTranslateId();

    /**
     * Set translate_id
     * @param string $translate_id
     * @return Swatch\ManageLabel\Api\Data\TranslateInterface
     */
    
    public function setTranslateId($translateId);

    /**
     * Get id
     * @return string|null
     */
    
    public function getId();

    /**
     * Set id
     * @param string $id
     * @return Swatch\ManageLabel\Api\Data\TranslateInterface
     */
    
    public function setId($id);
}
