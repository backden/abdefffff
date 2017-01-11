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

namespace Swatch\ManageLabel\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Model\ResourceModel\Translate\Collection;

interface TranslateRepositoryInterface
{


    /**
     * Save Translate
     * @param \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
     * @return \Swatch\ManageLabel\Api\Data\TranslateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function save(
        \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
    );


    /**
     * Save multiple records from array
     * @param TranslateInterface[] $items
     * @param bool $needUpdateExtend
     */
    public function saveCollection(
        array $items,
        $needUpdateExtend = false
    );

    /**
     * Retrieve Translate
     * @param string $translateId
     * @return \Swatch\ManageLabel\Api\Data\TranslateInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getById($translateId);

    /**
     * Retrieve Translate matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Swatch\ManageLabel\Api\Data\TranslateSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * @param $storeId
     * @return mixed
     */
    public function getListByStore(
        $storeId
    );

    /**
     * Fetch data by section and store id (current)
     * @param string $storeScope
     * @param string $section
     * @param string $group Optional
     * @param bool $useDefault
     * @return array
     */
    public function fetchSectionData($storeScope, $section, $group = 'labels', $useDefault = false);

    /**
     * Delete Translate
     * @param \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function delete(
        \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
    );

    /**
     * Delete Translate by ID
     * @param string $translateId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function deleteById($translateId);
}
