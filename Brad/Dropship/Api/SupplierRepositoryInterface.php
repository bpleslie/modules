<?php

namespace Brad\Dropship\Api;

use Brad\Dropship\Api\Data\SupplierInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Supplier CRUD interface.
 * @api
 */
interface SupplierRepositoryInterface
{
    /**
     * Save supplier.
     *
     * @param SupplierInterface $supplier
     * @return SupplierInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(SupplierInterface $supplier);

    /**
     * Retrieve supplier.
     *
     * @param int $supplierId
     * @return SupplierInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($supplierId);

    /**
     * Retrieve suppliers matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Brad\Dropship\Api\Data\SupplierSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete supplier.
     *
     * @param SupplierInterface $supplier
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(SupplierInterface $supplier);

    /**
     * Delete supplier by ID.
     *
     * @param int $supplierId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($supplierId);
}
