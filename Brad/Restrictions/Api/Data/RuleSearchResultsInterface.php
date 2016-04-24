<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brad\Restrictions\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for rule search results.
 * @api
 */
interface RuleSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get rules list.
     *
     * @return \Brad\Restrictions\Api\Data\RuleInterface[]
     */
    public function getItems();

    /**
     * Set rules list.
     *
     * @param \Brad\Restrictions\Api\Data\RuleInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
