<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 13/01/2017
 * Time: 18:13
 */

namespace Swatch\ManageLabel\Webservice\V1\Production;


use Magento\Framework\Api\DataObjectHelper;
use Swatch\ManageLabel\Api\Data\TranslateInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;
use Swatch\ManageLabel\Model\ResourceModel\Translate\CollectionFactory;

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

    public function __construct(
        TranslateRepositoryInterface $translateRepository,
        CollectionFactory $collectionFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->translateRepository = $translateRepository;
        $this->collectionFactory = $collectionFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function executeProductionWebService(\Swatch\ManageLabel\Api\Data\TranslateInterface $translate)
    {
        $result = [
            'code' => 200
        ];
        try {
            $this->translateRepository->saveCollection([
                $translate->getIdString() => $translate->getData()
            ], true);
        } catch (\Exception $e) {
            $result['code'] = 'FAILED';
            $result['description'] = $e->getMessage();
        }
        return $result;
    }
}