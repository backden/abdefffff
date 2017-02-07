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
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;
use Swatch\ManageLabel\Model\Config\Structure\Data;
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
     * @var Data $dataStructure
     */
    protected $dataStructure;

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param TranslateRepositoryInterface $translateRepository
     * @param CollectionFactory $collectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        TranslateRepositoryInterface $translateRepository,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Data $dataStructure,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->translateRepository = $translateRepository;
        $this->collectionFactory = $collectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->dataStructure = $dataStructure;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
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
                $this->getRequest()->getParam('website') : 0;
            $website = $this->storeManager->getWebsite($websiteId);
            $group = $this->storeManager->getGroup($website->getDefaultGroupId());
            $storeId = $group->getDefaultStoreId();
        }
        $configArr = [
            TranslateInterface::TRANSLATE_ID => null,
            TranslateInterface::STORE_ID => $storeId,
            TranslateInterface::SECTION_NAME => $section,
            TranslateInterface::ID_LABEL => null,
            TranslateInterface::STRING_LABEL => null,
            TranslateInterface::TRANSLATE_LABEL => null,
            TranslateInterface::USE_DEFAULT => null,
        ];

        /**
         * @var Collection $collection
         */
        $collection = $this->collectionFactory->create();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectParams = $this->getRequest()->getParams();
        unset($redirectParams['form_key']);

        if ($dataPost) {
            // Get existing and default translate
            list($sectionTranslated, $defaultTranslated) = $this->getTranslation($storeId, $section);
            // Get definition label
            $labelsDefined = $this->getDefinitionLabels($section);

            if (!$labelsDefined) {
                return $resultRedirect->setPath('managelabels/index/index', [
                    'section' => $section,
                    'store' => $storeId
                ]);
            }
            $items = [];
            $needUpdateExtend = false;
            foreach ($labels as $idLabel => $translate) {
                $oldLabelHash = "";
                $useDefault = 1;
                $labelDefined = $labelsDefined[$idLabel];
                if (isset($sectionTranslated[$idLabel])) {
                    // Get item for update
                    $item = $sectionTranslated[$idLabel];
                    $oldLabelHash = md5($item->getTranslate());
                    unset($sectionTranslated[$idLabel]);
                } else {
                    // New if any
                    $item = $collection->getNewEmptyItem();
                }
                // Get default translate for label if 'inherit' is given
                if (isset($translate['inherit']) && $translate['inherit'] == 1) {
                    $translate['value'] = isset($defaultTranslated[$idLabel]) ?
                        $defaultTranslated[$idLabel]->getTranslate() : '';
                } else {
                    // If store is default then set use_default is 0 as default
                    if ($storeId != 0) {
                        $useDefault = 0;
                    } else {
                        $needUpdateExtend = true;
                    }
                }
                $newLabelHash = md5($translate['value']);
                // Checking modify of translate to avoid update label was not modified
                if ($oldLabelHash !== $newLabelHash) {
                    $dataArr = [
                        TranslateInterface::TRANSLATE_ID => $item->getId(),
                        TranslateInterface::ID_LABEL => $idLabel,
                        TranslateInterface::STRING_LABEL => $labelDefined['label'],
                        TranslateInterface::TRANSLATE_LABEL => $translate['value'],
                        TranslateInterface::USE_DEFAULT => $useDefault
                    ];
                    $dataArr = array_merge($configArr, $dataArr);
                    $this->dataObjectHelper->populateWithArray($item, $dataArr, TranslateInterface::class);
                    $items[$item->getIdString()] = $item->getData();
                }
            }
            // Adding items no longer available
            $itemsDeleted = $this->mergeNotAvailableLabels($items, $sectionTranslated);

            try {
                $this->translateRepository->saveAndDeleteCollection($items, $itemsDeleted, $section, $needUpdateExtend);

                $this->messageManager->addSuccessMessage(__('You saved successfully.'));
                $this->dataPersistor->clear('swatch_managelabel_translate');

                return $resultRedirect->setPath('managelabels/index/index', [
                    'section' => $section,
                    'store' => $storeId
                ]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set('swatch_managelabel_translate', $dataPost);
        }
        return $resultRedirect->setPath('managelabels/index/index', [
            'section' => $section,
            'store' => $storeId
        ]);
    }

    /**
     * Get Translate existing and default values
     * @return array
     */
    protected function getTranslation($storeId, $section)
    {
        // Search translated text in data by current store and section
        $searchBuilder = $this->searchCriteriaBuilder
            ->addFilter('store_id', $storeId)->addFilter('section', $section);
        $searchCriteria = $searchBuilder->create();
        $existingTranslate = $this->translateRepository->getList($searchCriteria);
        $defaultTranslates = $this->translateRepository->fetchSectionData($storeId, $section, 'labels', true);

        if ($existingTranslate->getTotalCount() > 0) {
            $existingTranslate = $existingTranslate->getItems();
        } else {
            $existingTranslate = [];
        }
        // Re-define data to label (string) is the key
        $sectionTranslated = [];
        foreach ($existingTranslate as $index => $translate) {
            $sectionTranslated[$translate->getIdString()] = $translate;
        }
        // Re-define default data to array
        $defaultTranslated = [];
        if ($defaultTranslates) {
            foreach ($defaultTranslates as $index => $translate) {
                $defaultTranslated[$translate->getIdString()] = $translate;
            }
        }
        return [
            $sectionTranslated,
            $defaultTranslated
        ];
    }

    /**
     * Get definition of manage labels
     * @param string $section
     * @return array|bool False if occur error
     */
    protected function getDefinitionLabels($section)
    {
        $definitionArray = $this->dataStructure->get();
        $definitionArray = $definitionArray['sections'];
        if (!isset($definitionArray[$section])) {
            $this->messageManager->addErrorMessage(__("Section not found"));
            return false;
        }
        return $definitionArray[$section]['children']['labels']['children'];
    }

    /**
     * Items no longer available that will be removed
     * @param [] $items
     * @param [] $sectionTranslated
     * @return array Items need remove
     */
    protected function mergeNotAvailableLabels($items, $sectionTranslated)
    {
        $itemsDelete = [];
        // Items no longer available that will be removed
        foreach ($sectionTranslated as $translate) {
            $idLabel = $translate->getIdString();
            if (!isset($items[$idLabel])) {
                $data = $translate->getData();
                $itemsDelete[$idLabel] = $data;
            }
        }
        return $itemsDelete;
    }
}
