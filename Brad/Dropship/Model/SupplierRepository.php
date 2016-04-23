<?php

namespace Brad\Dropship\Model;

use Brad\Dropship\Api\Data;
use Brad\Dropship\Api\Data\SupplierInterface;
use Brad\Dropship\Api\SupplierRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Brad\Dropship\Model\ResourceModel\Supplier as ResourceSupplier;
use Brad\Dropship\Model\ResourceModel\Supplier\CollectionFactory as SupplierCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SupplierRepository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SupplierRepository implements SupplierRepositoryInterface
{
    /**
     * @var ResourceSupplier
     */
    protected $resource;

    /**
     * @var SupplierFactory
     */
    protected $supplierFactory;

    /**
     * @var SupplierCollectionFactory
     */
    protected $supplierCollectionFactory;

    /**
     * @var Data\SupplierSearchResultsInterfaceFactory
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
     * @var \Brad\Dropship\Api\Data\SupplierInterfaceFactory
     */
    protected $dataSupplierFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ResourceSupplier $resource
     * @param SupplierFactory $supplierFactory
     * @param Data\SupplierInterfaceFactory $dataSupplierFactory
     * @param SupplierCollectionFactory $supplierCollectionFactory
     * @param Data\SupplierSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceSupplier $resource,
        SupplierFactory $supplierFactory,
        Data\SupplierInterfaceFactory $dataSupplierFactory,
        SupplierCollectionFactory $supplierCollectionFactory,
        Data\SupplierSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->supplierFactory = $supplierFactory;
        $this->pageCollectionFactory = $supplierCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataSupplierFactory = $dataSupplierFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * Save Supplier data
     *
     * @param SupplierInterface $supplier
     * @return Supplier
     * @throws CouldNotSaveException
     */
    public function save(SupplierInterface $supplier)
    {
        if (empty($supplier->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $supplier->setStoreId($storeId);
        }
        try {
            $this->resource->save($supplier);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the supplier: %1',
                $exception->getMessage()
            ));
        }
        return $supplier;
    }

    /**
     * Load Supplier data by given Supplier Identity
     *
     * @param string $supplierId
     * @return Supplier
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($supplierId)
    {
        $supplier = $this->supplierFactory->create();
        $supplier->load($supplierId);
        if (!$supplier->getId()) {
            throw new NoSuchEntityException(__('Dropship Supplier with id "%1" does not exist.', $supplierId));
        }
        return $supplier;
    }

    /**
     * Load Supplier data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteriaInterface $criteria
     * @return \Brad\Dropship\Model\ResourceModel\Supplier\Collection
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
        $collection->setCurSupplier($criteria->getCurrentSupplier());
        $collection->setSupplierSize($criteria->getSupplierSize());
        $suppliers = [];
        /** @var Supplier $supplierModel */
        foreach ($collection as $supplierModel) {
            $supplierData = $this->dataSupplierFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $supplierData,
                $supplierModel->getData(),
                'Brad\Dropship\Api\Data\SupplierInterface'
            );
            $suppliers[] = $this->dataObjectProcessor->buildOutputDataArray(
                $supplierData,
                'Brad\Dropship\Api\Data\SupplierInterface'
            );
        }
        $searchResults->setItems($suppliers);
        return $searchResults;
    }

    /**
     * Delete Supplier
     *
     * @param SupplierInterface $supplier
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SupplierInterface $supplier)
    {
        try {
            $this->resource->delete($supplier);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the page: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * Delete Supplier by given Supplier Identity
     *
     * @param string $supplierId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($supplierId)
    {
        return $this->delete($this->getById($supplierId));
    }
}
