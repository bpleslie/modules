<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brad\Dropship\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for supplier search results.
 * @api
 */
interface SupplierSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get suppliers list.
     *
     * @return \Brad\Dropship\Api\Data\SupplierInterface[]
     */
    public function getItems();

    /**
     * Set suppliers list.
     *
     * @param \Brad\Dropship\Api\Data\SupplierInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
