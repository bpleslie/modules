<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brad\Restrictions\Api\Data;

/**
 * Restrictions rule interface.
 * @api
 */
interface RuleInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID            = 'id';
    const NAME          = 'name';
    const RESTRICTIONS  = 'restrictions';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get restriction rule
     *
     * @return string|null
     */
    public function getRestrictions();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    public function setId($id);

    /**
     * Set name
     *
     * @param string $name
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    public function setName($name);

    /**
     * Set restriction rule
     *
     * @param string $restrictions
     * @return \Brad\Restrictions\Api\Data\RuleInterface
     */
    public function setRestrictions($restrictions);

}
