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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\EntityManager\EventManager;
use Psr\Log\LoggerInterface;
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
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var EventManager $eventManager
     */
    protected $eventManager;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * TranslateRepository constructor.
     * @param ResourceTranslate $resource
     * @param TranslateFactory $translateFactory
     * @param TranslateInterfaceFactory $dataTranslateFactory
     * @param TranslateCollectionFactory $translateCollectionFactory
     * @param TranslateSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
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
        ScopeConfigInterface $scopeConfig,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        EventManager $eventManager,
        LoggerInterface $logger
    ) {
        $this->resource = $resource;
        $this->translateFactory = $translateFactory;
        $this->translateCollectionFactory = $translateCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTranslateFactory = $dataTranslateFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
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
    public function saveMultiple(array $data)
    {
        $connection = $this->resource->getConnection();
        if ($connection) {
            $connection->insertOnDuplicate($this->resource->getMainTable(), $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function saveCollection(array $items, $section, $needUpdateExtend = false)
    {
        if (count($items) > 0) {
            $this->saveMultiple($items);
            // Update for other section which has labels using value default
            if ($needUpdateExtend) {
                $needUpdateItems = [];
                $collection = $this->translateCollectionFactory->create();
                // Find all label by section
                $itemsNeedUpdate = $collection->getItemsByColumnValue(TranslateInterface::SECTION_NAME, $section);
                foreach ($itemsNeedUpdate as $item) {
                    if ($item->getStoreId() !== 0) {
                        if (isset($items[$item->getIdString()])) {
                            $defaultItem = $items[$item->getIdString()];
                            // Update translate text and visibility
                            if ($item->isUseDefault()) {
                                $item->setTranslate($defaultItem[TranslateInterface::TRANSLATE_LABEL]);
                            }
                            $item->setIsVisible($defaultItem[TranslateInterface::IS_VISIBLE]);
                            $needUpdateItems[] = $item->getData();
                        }
                    }
                }
                if (count($needUpdateItems) > 0) {
                    $this->saveMultiple($needUpdateItems);
                }
            }
            if (!$this->_blockEvents) {
                $this->eventManager->dispatch('labels_save_after', [$items]);
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
    public function fetchSectionData($storeScope, $section, $useDefault = false)
    {
        $storeId = !empty($storeScope) ? $storeScope : 0;
        if ($useDefault) {
            $storeId = 0;
        }
        if (!isset($this->sectionTranslate[$storeId])) {
            $this->sectionTranslate[$storeId] = [];
        }
        if (!isset($this->sectionTranslate[$storeId][$section])) {
            $searchBuilder = $this->searchCriteriaBuilder->addFilter('store_id', $storeId)
                ->addFilter('section', $section);
            $searchCriteria = $searchBuilder->create();
            $translates = $this->getList($searchCriteria)->getItems();
            if (count($translates) > 0) {
                $this->sectionTranslate[$storeId][$section] = $translates;
            } else {
                $this->sectionTranslate[$storeId][$section] = [];
            }
        }
        return $this->sectionTranslate[$storeId][$section];
    }

    /**
     * {@inheritdoc}
     */
    public function getListByStore(
        $storeId
    ) {
        $collection = $this->translateCollectionFactory->create();
        return $collection->getItemsByColumnValue(TranslateInterface::STORE_ID, $storeId);
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationData($section, $storeId)
    {
        if (is_null($storeId)) {
            // Use current store
            $storeId = $this->storeManager->getStore()->getId();
        }
        $data = $this->resource->getTranslationArray(null, $storeId);
        return $data;
    }
}
