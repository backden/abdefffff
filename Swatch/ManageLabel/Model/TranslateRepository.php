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

use Magento\Framework\Api\Search\SearchResultInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;
use Swatch\ManageLabel\Api\Data\TranslateSearchResultsInterfaceFactory;
use Swatch\ManageLabel\Api\Data\TranslateInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Swatch\ManageLabel\Model\ResourceModel\Translate as ResourceTranslate;
use Swatch\ManageLabel\Model\ResourceModel\Translate\CollectionFactory as TranslateCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class TranslateRepository implements TranslateRepositoryInterface
{

    /**
     * @var array $sectionTranslate
     */
    protected $sectionTranslate;

    /**
     * @var ResourceTranslate $resource
     */
    protected $resource;

    /**
     * @var TranslateSearchResultsInterfaceFactory $translateFactory
     */
    protected $translateFactory;

    /**
     * @var TranslateCollectionFactory $translateCollectionFactory
     */
    protected $translateCollectionFactory;

    /**
     * @var TranslateSearchResultsInterfaceFactory $searchResultsFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper $dataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor $dataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var TranslateInterfaceFactory $dataTranslateFactory
     */
    protected $dataTranslateFactory;

    /**
     * @var StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @param ResourceTranslate $resource
     * @param TranslateFactory $translateFactory
     * @param TranslateInterfaceFactory $dataTranslateFactory
     * @param TranslateCollectionFactory $translateCollectionFactory
     * @param TranslateSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceTranslate $resource,
        TranslateFactory $translateFactory,
        TranslateInterfaceFactory $dataTranslateFactory,
        TranslateCollectionFactory $translateCollectionFactory,
        TranslateSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->resource = $resource;
        $this->translateFactory = $translateFactory;
        $this->translateCollectionFactory = $translateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTranslateFactory = $dataTranslateFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
    ) {
        try {
            $this->resource->save($translate);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the translate: %1',
                $exception->getMessage()
            ));
        }
        return $translate;
    }

    /**
     * {@inheritdoc}
     */
    public function saveCollection(array $items, $needUpdateExtend = false)
    {
        if (count($items) > 0) {
            $section = "";
            foreach ($items as $translate) {
                $section = $translate->getSection();
                $this->save($translate);
            }
            // Update for other section which has labels using value default
            if ($needUpdateExtend) {
                $collection = $this->translateCollectionFactory->create();
                // Find all label by section
                $itemsNeedUpdate = $collection->getItemsByColumnValue(TranslateInterface::SECTION_NAME, $section);
                foreach ($itemsNeedUpdate as $item) {
                    if ($item->isUseDefault() && isset($items[$item->getString()])) {
                        $defaultItem = $items[$item->getString()];
                        // Update translate text
                        $item->setTranslate($defaultItem->getTranslate());
                        $this->save($item);
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getById($translateId)
    {
        $translate = $this->translateFactory->create();
        $translate->load($translateId);
        if (!$translate->getId()) {
            throw new NoSuchEntityException(__('Translate with id "%1" does not exist.', $translateId));
        }
        return $translate;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->translateCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? : 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $items = [];

        foreach ($collection as $translateModel) {
            $translateData = $this->dataTranslateFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $translateData,
                $translateModel->getData(),
                TranslateInterface::class
            );
            $items[] = $translateData;
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
    ) {
        try {
            $this->resource->delete($translate);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Translate: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($translateId)
    {
        return $this->delete($this->getById($translateId));
    }

    /**
     * {@inheritdoc}
     */
    public function fetchSectionData($storeScope, $section, $group = 'labels', $useDefault = false)
    {
        if (!isset($this->sectionTranslate[$section])) {
            $storeId = !empty($storeScope) ? $storeScope : 0;
            if ($useDefault) {
                $storeId = 0;
            }
            $searchBuilder = $this->searchCriteriaBuilder->addFilter('store_id', $storeId)
                ->addFilter('section', $section);
            $searchCriteria = $searchBuilder->create();
            $translates = $this->getList($searchCriteria)->getItems();
            if (count($translates) > 0) {
                $this->sectionTranslate[$section] = $translates;
            }
        }
        return $this->sectionTranslate[$section];
    }

    /**
     * @param $storeId
     * @return mixed
     */
    public function getListByStore(
        $storeId
    ) {
        $collection = $this->translateCollectionFactory->create();
        return $collection->getItemsByColumnValue(TranslateInterface::STORE_ID, $storeId);
    }
}
