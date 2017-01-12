<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 11/01/2017
 * Time: 16:34
 */

namespace Swatch\ManageLabel\Plugin;

use Magento\Framework\App\CacheInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;

/**
 * Class TranslatePlugin
 * @package Swatch\ManageLabel\Plugin
 * @author long.pham
 */
class TranslatePlugin
{
    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var TranslateRepositoryInterface $translateRepository
     */
    protected $translateRepository;

    /**
     * @var StoreManagerInterface $storeManagement
     */
    protected $storeManagement;

    /**
     * @var CacheInterface $cache
     */
    protected $cache;

    /**
     * RendererInterfacePlugin constructor.
     * @param LoggerInterface $logger
     * @param TranslateRepositoryInterface $translateRepository
     * @param StoreManagerInterface $storeManagement
     */
    public function __construct(
        LoggerInterface $logger,
        TranslateRepositoryInterface $translateRepository,
        StoreManagerInterface $storeManagement,
        CacheInterface $cache
    ) {
        $this->logger = $logger;
        $this->translateRepository = $translateRepository;
        $this->storeManagement = $storeManagement;
        $this->cache = $cache;
    }

    /**
     * Plugin on getTranslationArray of Translate.php file
     * @param \Magento\Translation\Model\ResourceModel\Translate $subject
     * @param \Closure $process
     * @param null $storeId
     * @param null $locale
     * @return array
     */
    public function aroundGetTranslationArray(
        \Magento\Translation\Model\ResourceModel\Translate $subject,
        \Closure $process,
        $storeId = null,
        $locale = null
    ) {
        $result = $process($storeId, $locale);
        // Get and insert user-define translate to result
        $defaultTranslates = $this->translateRepository->getTranslationData(null, $storeId);

        return array_merge($result, $defaultTranslates);
    }
}