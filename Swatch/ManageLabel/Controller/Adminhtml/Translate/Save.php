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

namespace Swatch\ManageLabel\Controller\Adminhtml\Translate;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;
use Swatch\ManageLabel\Model\ResourceModel\Translate\Collection;
use Swatch\ManageLabel\Model\ResourceModel\Translate\CollectionFactory;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @var TranslateRepositoryInterface $translateRepository
     */
    protected $translateRepository;

    /**
     * @param CollectionFactory $collection
     */
    protected $collectionFactory;

    /**
     * @var DataObjectHelper $dataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var SearchCriteriaBuilder $searchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param TranslateRepositoryInterface $translateRepository
     * @param CollectionFactory $collectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        TranslateRepositoryInterface $translateRepository,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->translateRepository = $translateRepository;
        $this->collectionFactory = $collectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $dataPost = $this->getRequest()->getPostValue();
        $labels = $dataPost['groups']['labels']['fields'];

        $section = $this->getRequest()->getParam('section');

        $storeId = $this->getRequest()->getParam('store');
        if (empty($storeId)) {
            // Get website or default
            $websiteId = $this->getRequest()->getParam('website') ?
                $this->getRequest()->getParam('website') : 1;
            $website = $this->storeManager->getWebsite($websiteId);
            $group = $this->storeManager->getGroup($website->getDefaultGroupId());
            $storeId = $group->getDefaultStoreId();
        }
        $configArr = [
            'store_id' => $storeId,
            'section' => $section
        ];

        $translates = [];
        /**
         * @var Collection $collection
         */
        $collection = $this->collectionFactory->create();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectParams = $this->getRequest()->getParams();
        unset($redirectParams['form_key']);

        if ($dataPost) {
            // Search translated text in data by current store and section
            $searchBuilder = $this->searchCriteriaBuilder->addFilter('store_id', $storeId)->addFilter('section', $section);
            $searchCriteria = $searchBuilder->create();
            $existingTranslate = $this->translateRepository->getList($searchCriteria);

            if ($existingTranslate->getTotalCount() > 0) {
                $existingTranslate = $existingTranslate->getItems();
            } else {
                $existingTranslate = [];
            }
            // Re-define data to label (string) is the key
            $sectionTranslated = [];
            foreach ($existingTranslate as $index => $translate) {
                $sectionTranslated[$translate->getString()] = $translate;
            }

            $items = [];
            foreach ($labels as $label => $translate) {
                if (isset($sectionTranslated[$label])) {
                    //
                    $item = $sectionTranslated[$label];
                } else {
                    // New if any
                    $item = $collection->getNewEmptyItem();
                }
                $dataArr = [
                    'string' => $label,
                    'translate' => $translate['value']
                ];
                $dataArr = array_merge($configArr, $dataArr);
                $this->dataObjectHelper->populateWithArray($item, $dataArr, TranslateInterface::class);
                $items[] = $item;
            }

            try {
                $this->translateRepository->saveCollection($items);

                $this->messageManager->addSuccess(__('You saved successfully.'));
                $this->dataPersistor->clear('swatch_managelabel_translate');

                return $resultRedirect->setPath('managecontent/index/index', [
                    'section' => $section,
                    'store' => $storeId
                ]);
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('swatch_managelabel_translate', $dataPost);
        }
        return $resultRedirect->setPath('managecontent/index/index', [
            'section' => $section,
            'store' => $storeId
        ]);
    }
}
