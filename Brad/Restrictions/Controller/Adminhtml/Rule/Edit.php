<?php

namespace Brad\Restrictions\Controller\Adminhtml\Rule;

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
    const ADMIN_RESOURCE = 'Brad_Restrictions::save';

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
     * @return \Magento\Backend\Model\View\Result\Rule
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Rule $resultRule */
        $resultRule = $this->resultPageFactory->create();
        $resultRule->setActiveMenu('Brad_Restrictions::rule')
            ->addBreadcrumb(__('Restrictions'), __('Restrictions'))
            ->addBreadcrumb(__('Manage Rules'), __('Manage Rules'));
        return $resultRule;
    }

    /**
     * Edit Restrictions rule
     *
     * @return \Magento\Backend\Model\View\Result\Rule|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Brad\Restrictions\Model\Rule');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This rule no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('rule', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Rule $resultRule */
        $resultRule = $this->_initAction();
        $resultRule->addBreadcrumb(
            $id ? __('Edit Rule') : __('New Rule'),
            $id ? __('Edit Rule') : __('New Rule')
        );
        $resultRule->getConfig()->getTitle()->prepend(__('Rules'));
        $resultRule->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Rule'));

        return $resultRule;
    }
}
