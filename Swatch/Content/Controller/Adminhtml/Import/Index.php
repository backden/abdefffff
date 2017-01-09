<?php
/**
 *
 * Copyright © 2016 Swatch. All rights reserved.
 */
namespace Swatch\Content\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Index
 * @package Swatch\Import\Controller\Adminhtml\Content\Import
 */
class Index extends Action
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Swatch_Content::import');
        $resultPage->getConfig()->getTitle()->prepend(__('Imports'));
        return $resultPage;
    }
}
