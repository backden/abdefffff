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

    protected $resource;

    protected $TranslateFactory;

    protected $TranslateCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataTranslateFactory;

    private $storeManager;


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
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->translateFactory = $translateFactory;
        $this->translateCollectionFactory = $translateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTranslateFactory = $dataTranslateFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Swatch\ManageLabel\Api\Data\TranslateInterface $translate
    ) {
        /* if (empty($translate->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $translate->setStoreId($storeId);
        } */
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
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
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
                'Swatch\ManageLabel\Api\Data\TranslateInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $translateData,
                'Swatch\ManageLabel\Api\Data\TranslateInterface'
            );
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
}
