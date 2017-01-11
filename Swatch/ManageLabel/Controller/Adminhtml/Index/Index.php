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

namespace Swatch\ManageLabel\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\Action
{

    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    protected $dataPersistor;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store');
        if (empty($storeId)) {
            // Get website or default
            $websiteId = $this->getRequest()->getParam('website') ?
                $this->getRequest()->getParam('website') : 0;
            $website = $this->storeManager->getWebsite($websiteId);
            $group = $this->storeManager->getGroup($website->getDefaultGroupId());
            $storeId = $group->getDefaultStoreId();
        }
        $page = $this->resultPageFactory->create();
        $page->getConfig()->getTitle()->set(__('Manage Labels'));
        // Force display on store scope
        $this->getRequest()->setParams([
            'store' => $storeId
        ]);
        return $page;
    }
}
