<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 13/01/2017
 * Time: 18:13
 */

namespace Swatch\ManageLabel\Webservice\V1\Production;

use Magento\Framework\Api\DataObjectHelper;
use Psr\Log\LoggerInterface;
use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;
use Swatch\ManageLabel\Model\Config\Structure\Data;
use Swatch\ManageLabel\Model\ResourceModel\Translate\CollectionFactory;
use Swatch\ManageLabel\Model\Validate\TranslateValidatorFactory;
use Swatch\Staging\Api\Data\ApproveListInterface;

class TranslateWebApi implements TranslateWebApiInterface
{
    /**
     * @var TranslateRepositoryInterface $translateRepository
     */
    protected $translateRepository;

    /**
     * @var DataObjectHelper $dataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var CollectionFactory $collectionFactory
     */
    protected $collectionFactory;

    /**
     * @var Data $dataReader
     */
    protected $dataReader;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var TranslateValidatorFactory $translateValidatorFactory
     */
    protected $translateValidatorFactory;

    /**
     * TranslateWebApi constructor.
     * @param TranslateRepositoryInterface $translateRepository
     * @param TranslateValidatorFactory $translateValidatorFactory
     * @param CollectionFactory $collectionFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param Data $dataReader
     * @param LoggerInterface $logger
     */
    public function __construct(
        TranslateRepositoryInterface $translateRepository,
        TranslateValidatorFactory $translateValidatorFactory,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper,
        Data $dataReader,
        LoggerInterface $logger
    ) {
        $this->translateRepository = $translateRepository;
        $this->collectionFactory = $collectionFactory;
        $this->translateValidatorFactory = $translateValidatorFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataReader = $dataReader;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function executeProductionWebService(ApproveListInterface $approvedData)
    {
        $result = [
            'code' => static::RESPONSE_SUCCESS,
            'description' => ''
        ];
        $contentId = $approvedData->getContentId();
        $entityType = $approvedData->getEntityType();
        $jsData = $approvedData->getContent();
        $actionType = $approvedData->getActionType();

        // Checking entity type.
        // Allow entity type is "labels"
        if ($entityType != static::ENTITY_TYPE) {
            $result['code'] = static::RESPONSE_ERROR_REQUEST;
            $result['description'] = 'Request format error';
            return [$result];
        }
        // Checking action type
        if ($actionType !== static::ACTION_TYPE_DELETE && !$jsData) {
            $result['code'] = static::RESPONSE_ERROR_DATA_INVALID;
            $result['description'] = 'Invalid data type';
            return [$result];
        } elseif ($actionType === static::ACTION_TYPE_DELETE) {
            return $this->delete($contentId);
        }

        // Build skeleton of model
        $configArr = [
            TranslateInterface::TRANSLATE_ID => null,
            TranslateInterface::STORE_ID => null,
            TranslateInterface::SECTION_NAME => null,
            TranslateInterface::ID_LABEL => null,
            TranslateInterface::STRING_LABEL => null,
            TranslateInterface::TRANSLATE_LABEL => null,
            TranslateInterface::USE_DEFAULT => null,
        ];

        // parse to array from json string
        $translateArr = json_decode($jsData, true);
        $translateArr = array_merge($configArr, $translateArr);

        // Use validator model of translate
        $validator = $this->translateValidatorFactory->create();

        $sectionDefinition = $this->dataReader->get();
        $this->dataObjectHelper->populateWithArray($validator, $translateArr, TranslateInterface::class);
        // Check invalid data of some fields
        if ($validator->isError()) {
            $result['code'] = static::RESPONSE_ERROR_DATA_INVALID;
            $result['description'] = 'Invalid data type: ' . implode(',', $validator->getErrorFields());
            return [$result];
        }

        // Checking section is defined or not
        $sections = $sectionDefinition['sections'];
        if (!isset($sections[$validator->getSection()])) {
            $result['code'] = static::RESPONSE_ERROR_DATA_INVALID;
            $result['description'] = 'Section not found: ' . $validator->getSection();
            return [$result];
        }
        // Checking label is defined or not
        $labels = $sections[$validator->getSection()]['children']['labels']['children'];
        if (!isset($labels[$validator->getIdString()])) {
            $result['code'] = static::RESPONSE_ERROR_DATA_INVALID;
            $result['description'] = 'Label was not supported: ' . $validator->getIdString();
            return $result;
        }

        // Execute action
        $translate = $validator;
        if ($actionType === static::ACTION_TYPE_CREATE) {
            $result = $this->save($translate);
        } elseif ($actionType === static::ACTION_TYPE_UPDATE) {
            $result = $this->save($translate);
        } else {
            // No supports
            $result['code'] = static::RESPONSE_ERROR_MESSAGE_NOT_SUPPORT;
            $result['description'] = 'Message not supported';
        }
        return [$result];
    }

    /**
     * Save item
     * @param TranslateInterface $translate
     * @return array
     */
    protected function save(TranslateInterface $translate)
    {
        $result = [
            'code' => static::RESPONSE_SUCCESS,
            'description' => 'Save successfully'
        ];
        try {
            $this->translateRepository->setBlockEvent(true);
            $this->translateRepository->saveCollection([
                $translate->getIdString() => $translate->getData()
            ], $translate->getStoreId() == 0 ? true : false);
        } catch (\Exception $e) {
            $result['code'] = static::RESPONSE_ERROR_SERVER;
            $result['description'] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Delete staging
     * @param $id
     * @return array
     */
    protected function delete($id) {
        $result = [
            'code' => static::RESPONSE_SUCCESS,
            'description' => 'Delete successfully'
        ];
        try {
            $this->translateRepository->setBlockEvent(true);
            $translate = $this->translateRepository->getById($id);
            $this->translateRepository->deleteCollection([
                $translate->getData()
            ], $translate->getStoreId() == 0 ? true : false);
        } catch (\Exception $e) {
            $result['code'] = static::RESPONSE_ERROR_SERVER;
            $result['description'] = $e->getMessage();
        }
        return $result;
    }

}