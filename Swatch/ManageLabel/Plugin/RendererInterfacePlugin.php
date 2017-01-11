<?php
/**
 * Created by PhpStorm.
 * User: bluecom
 * Date: 11/01/2017
 * Time: 16:34
 */

namespace Swatch\ManageLabel\Plugin;

use Magento\Framework\Phrase\RendererInterface;
use Magento\Store\Api\StoreManagementInterface;
use Magento\Store\Model\StoreManagement;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Swatch\ManageLabel\Api\TranslateRepositoryInterface;

/**
 * Class RendererInterfacePlugin
 * @package Swatch\ManageLabel\Plugin
 * @author long.pham
 */
class RendererInterfacePlugin
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

    public function __construct(
        LoggerInterface $logger,
        TranslateRepositoryInterface $translateRepository,
        StoreManagerInterface $storeManagement
    ) {
        $this->logger = $logger;
        $this->translateRepository = $translateRepository;
        $this->storeManagement = $storeManagement;
    }

    /**
     * Render source text
     *
     * @param RendererInterface $subject
     * @param [] $source
     * @param [] $arguments
     * @return string
     */
    public function aroundRender(RendererInterface $subject, \Closure $process, array $source, array $arguments)
    {
        $text = $source[0];
        if ($text == 'labels') {
            $s = '';
        }
//        $defaultTranslates = $this->translateRepository->getListByStore(0);
//
//        if ($this->storeManagement->getWebsite()->getId() > 1) {
//            $currentStore = $this->storeManagement->getStore();
//            $translates = $this->translateRepository->getListByStore($currentStore->getId());
//            foreach ($translates as $translate) {
//                if ($translate->getString() === $text) {
//                    return $translate->getTranslate();
//                }
//            }
//        }
//        foreach ($defaultTranslates as $translate) {
//            if ($translate->getString() === $text) {
//                return $translate->getTranslate();
//            }
//        }
        $result = $process($source, $arguments);
        if ($result == 'Labels') {
            $s = '';
        }
        return $result;
    }
}