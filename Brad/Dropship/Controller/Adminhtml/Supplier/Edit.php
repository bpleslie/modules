<?php

namespace Brad\Dropship\Controller\Adminhtml\Supplier;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Brad_Dropship::save';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Supplier
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Supplier $resultSupplier */
        $resultSupplier = $this->resultPageFactory->create();
        $resultSupplier->setActiveMenu('Brad_Dropship::supplier')
            ->addBreadcrumb(__('Dropship'), __('Dropship'))
            ->addBreadcrumb(__('Manage Suppliers'), __('Manage Suppliers'));
        return $resultSupplier;
    }

    /**
     * Edit Dropship supplier
     *
     * @return \Magento\Backend\Model\View\Result\Supplier|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Brad\Dropship\Model\Supplier');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This supplier no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('supplier', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Supplier $resultSupplier */
        $resultSupplier = $this->_initAction();
        $resultSupplier->addBreadcrumb(
            $id ? __('Edit Supplier') : __('New Supplier'),
            $id ? __('Edit Supplier') : __('New Supplier')
        );
        $resultSupplier->getConfig()->getTitle()->prepend(__('Suppliers'));
        $resultSupplier->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Supplier'));

        return $resultSupplier;
    }
}
