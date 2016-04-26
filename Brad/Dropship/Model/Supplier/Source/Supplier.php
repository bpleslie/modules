<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brad\Dropship\Model\Supplier\Source;

use Brad\Dropship\Model\Supplier as SupplierModel;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class Supplier implements OptionSourceInterface
{
    /**
     * @var SupplierModel
     */
    protected $supplier;

    /**
     * Constructor
     *
     * @param SupplierModel $supplier
     */
    public function __construct(SupplierModel $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->supplier->getData();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
