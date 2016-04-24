<?php

namespace Brad\Restrictions\Model;

use Brad\Restrictions\Api\Data;
use Brad\Restrictions\Api\Data\RuleInterface;
use Brad\Restrictions\Api\RuleRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Brad\Restrictions\Model\ResourceModel\Rule as ResourceRule;
use Brad\Restrictions\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class RuleRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var ResourceRule
     */
    protected $resource;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var RuleCollectionFactory
     */
    protected $ruleCollectionFactory;

    /**
     * @var Data\RuleSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Brad\Restrictions\Api\Data\RuleInterfaceFactory
     */
    protected $dataRuleFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceRule $resource
     * @param RuleFactory $ruleFactory
     * @param Data\RuleInterfaceFactory $dataRuleFactory
     * @param RuleCollectionFactory $ruleCollectionFactory
     * @param Data\RuleSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceRule $resource,
        RuleFactory $ruleFactory,
        Data\RuleInterfaceFactory $dataRuleFactory,
        RuleCollectionFactory $ruleCollectionFactory,
        Data\RuleSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->ruleFactory = $ruleFactory;
        $this->pageCollectionFactory = $ruleCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataRuleFactory = $dataRuleFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Rule data
     *
     * @param RuleInterface $rule
     * @return Rule
     * @throws CouldNotSaveException
     */
    public function save(RuleInterface $rule)
    {
        if (empty($rule->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $rule->setStoreId($storeId);
        }
        try {
            $this->resource->save($rule);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the rule: %1',
                $exception->getMessage()
            ));
        }
        return $rule;
    }

    /**
     * Load Rule data by given Rule Identity
     *
     * @param string $ruleId
     * @return Rule
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($ruleId)
    {
        $rule = $this->ruleFactory->create();
        $rule->load($ruleId);
        if (!$rule->getId()) {
            throw new NoSuchEntityException(__('Restrictions Rule with id "%1" does not exist.', $ruleId));
        }
        return $rule;
    }

    /**
     * Load Rule data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return \Brad\Restrictions\Model\ResourceModel\Rule\Collection
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $collection = $this->pageCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurRule($criteria->getCurrentRule());
        $collection->setRuleSize($criteria->getRuleSize());
        $rules = [];
        /** @var Rule $ruleModel */
        foreach ($collection as $ruleModel) {
            $ruleData = $this->dataRuleFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $ruleData,
                $ruleModel->getData(),
                'Brad\Restrictions\Api\Data\RuleInterface'
            );
            $rules[] = $this->dataObjectProcessor->buildOutputDataArray(
                $ruleData,
                'Brad\Restrictions\Api\Data\RuleInterface'
            );
        }
        $searchResults->setItems($rules);
        return $searchResults;
    }

    /**
     * Delete Rule
     *
     * @param RuleInterface $rule
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(RuleInterface $rule)
    {
        try {
            $this->resource->delete($rule);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the page: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Rule by given Rule Identity
     *
     * @param string $ruleId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($ruleId)
    {
        return $this->delete($this->getById($ruleId));
    }
}
