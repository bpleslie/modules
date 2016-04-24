<?php

namespace Brad\Restrictions\Block\Adminhtml\Rule;

use Brad\Restrictions\Model\ResourceModel\Rule\CollectionFactory;
use Brad\Restrictions\Model\Rule;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;

/**
 * Adminhtml cms pages grid
 */
class Grid extends Extended
{
    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var Rule
     */
    protected $_cmsRule;

    /**
     * @var BuilderInterface
     */
    protected $pageLayoutBuilder;

    /**
     * @param Context $context
     * @param Data $backendHelper
     * @param Rule $cmsRule
     * @param CollectionFactory $collectionFactory
     * @param BuilderInterface $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Rule $cmsRule,
        CollectionFactory $collectionFactory,
        BuilderInterface $pageLayoutBuilder,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_cmsRule = $cmsRule;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('restrictionsRuleGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \Brad\Restrictions\Model\ResourceModel\Rule\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn('name', ['header' => __('Name'), 'index' => 'name']);
        $this->addColumn('email', ['header' => __('Email'), 'index' => 'email']);

        return parent::_prepareColumns();
    }

    /**
     * After load collection
     *
     * @return void
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Filter store condition
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @param \Magento\Framework\DataObject $column
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _filterStoreCondition($collection, \Magento\Framework\DataObject $column)
    {
        if (!($value = $column->getFilter()->getValue())) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['id' => $row->getId()]);
    }
}
