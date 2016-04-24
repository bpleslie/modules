<?php

namespace Brad\Restrictions\Model;

use Brad\Restrictions\Api\Data\RuleInterface;
use Brad\Restrictions\Model\ResourceModel\Rule as ResourceRule;
use Magento\Framework\Model\AbstractModel;

/**
 * Rule Model
 *
 * @method ResourceRule _getResource()
 * @method ResourceRule getResource()
 * @method Rule setStoreId(array $storeId)
 * @method array getStoreId()
 */
class Rule extends AbstractModel implements RuleInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Brad\Restrictions\Model\ResourceModel\Rule');
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
     * Get restriction rule
     *
     * @return string
     */
    public function getRestrictions()
    {
        return $this->getData(self::RESTRICTIONS);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Brad\Restrictions\Api\Data\RuleInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return \Brad\Restrictions\Api\Data\RuleInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Set restriction rule
     *
     * @param string $restrictions
     * @return \Brad\Restrictions\Api\Data\RuleInterface
     */
    public function setRestrictions($restrictions)
    {
        return $this->setData(self::RESTRICTIONS, $restrictions);
    }
}
