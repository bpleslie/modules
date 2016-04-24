<?php

namespace Brad\Restrictions\Api;

use Brad\Restrictions\Api\Data\RuleInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Rule CRUD interface.
 * @api
 */
interface RuleRepositoryInterface
{
    /**
     * Save rule.
     *
     * @param RuleInterface $rule
     * @return RuleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(RuleInterface $rule);

    /**
     * Retrieve rule.
     *
     * @param int $ruleId
     * @return RuleInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($ruleId);

    /**
     * Retrieve rules matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Brad\Restrictions\Api\Data\RuleSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete rule.
     *
     * @param RuleInterface $rule
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(RuleInterface $rule);

    /**
     * Delete rule by ID.
     *
     * @param int $ruleId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($ruleId);
}
