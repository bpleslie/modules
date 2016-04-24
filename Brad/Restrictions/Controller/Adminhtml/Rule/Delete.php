<?php

namespace Brad\Restrictions\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Brad_Restrictions::rule_delete';

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $name = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create('Brad\Restrictions\Model\Rule');
                $model->load($id);
                $name = $model->getName();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The rule has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_restrictionsrule_on_delete',
                    ['title' => $name, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_restrictionsrule_on_delete',
                    ['title' => $name, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
