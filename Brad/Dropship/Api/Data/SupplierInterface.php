<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brad\Dropship\Api\Data;

/**
 * Dropship supplier interface.
 * @api
 */
interface SupplierInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ID    = 'id';
    const NAME  = 'name';
    const EMAIL = 'email';
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
     * Get email
     *
     * @return string|null
     */
    public function getEmail();

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
     * Set email
     *
     * @param string $email
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    public function setEmail($email);

}
