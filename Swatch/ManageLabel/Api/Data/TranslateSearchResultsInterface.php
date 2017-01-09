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

interface TranslateSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Translate list.
     * @return \Swatch\ManageLabel\Api\Data\TranslateInterface[]
     */
    
    public function getItems();

    /**
     * Set id list.
     * @param \Swatch\ManageLabel\Api\Data\TranslateInterface[] $items
     * @return $this
     */
    
    public function setItems(array $items);
}
