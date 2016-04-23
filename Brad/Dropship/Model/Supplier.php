<?php

namespace Brad\Dropship\Model;

use Brad\Dropship\Api\Data\SupplierInterface;
use Brad\Dropship\Model\ResourceModel\Supplier as ResourceSupplier;
use Magento\Framework\Model\AbstractModel;

/**
 * Supplier Model
 *
 * @method ResourceSupplier _getResource()
 * @method ResourceSupplier getResource()
 * @method Supplier setStoreId(array $storeId)
 * @method array getStoreId()
 */
class Supplier extends AbstractModel implements SupplierInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Brad\Dropship\Model\ResourceModel\Supplier');
    }

    /**
     * Load object data
     *
     * @param int|null $id
     * @param string $field
     * @return $this
     */
    public function load($id, $field = null)
    {
        return parent::load($id, $field);
    }

    /**
     * Get ID
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ID);
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getData(self::NAME);
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Brad\Dropship\Api\Data\SupplierInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Brad\Dropship\Api\Data\SupplierInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set email
     *
     * @param string $email
     * @return \Brad\Dropship\Api\Data\SupplierInterface
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }
}
