<?php

namespace Brad\Dropship\Block\Adminhtml;
use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Adminhtml suppliers content block
 */
class Page extends Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_page';
        $this->_blockGroup = 'Brad_Dropship';
        $this->_headerText = __('Manage Suppliers');

        parent::_construct();

        if ($this->_isAllowedAction('Brad_Dropship::save')) {
            $this->buttonList->update('add', 'label', __('Add New Supplier'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
