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

use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Webservice\V1\Production\TranslateWebApiInterface;

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
     * Save or update multiple translate
     * @param array $data Translate model as array key-value pairs
     * @return void
     */
    public function saveMultiple(array $data);

    /**
     * Save multiple records from array
     * @param TranslateInterface[] $items
     * @param string $items
     * @param bool $needUpdateExtend
     */
    public function saveCollection(
        array $items,
        $section,
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
     * Get all translation by store id
     * @param $storeId
     * @return mixed
     */
    public function getListByStore(
        $storeId
    );

    /**
     * Fetch data by section and store id (current) - B
     * @param string $storeScopeackend
     * @param string $section
     * @param bool $useDefault
     * @return array
     */
    public function fetchSectionData($storeScope, $section, $useDefault = false);

    /**
     * For frontend, get all translation by section / current store id
     * @param string $section
     * @param int $storeId
     * @return array
     */
    public function getTranslationData($section, $storeId);

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
