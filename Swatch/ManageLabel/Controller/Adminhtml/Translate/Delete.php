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

class Delete extends \Swatch\ManageLabel\Controller\Adminhtml\Translate
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('translate_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Swatch\ManageLabel\Model\Translate');
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the Translate.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['translate_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a Translate to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
