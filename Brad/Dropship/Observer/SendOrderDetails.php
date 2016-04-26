<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Brad\Dropship\Observer;

use Brad\Dropship\Model\SupplierFactory;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SendOrderDetails implements ObserverInterface
{
    /**
     * @var SupplierFactory
     */
    protected $_supplierFactory;

    /**
     * @param SupplierFactory $supplierFactory
     */
    public function __construct(
        SupplierFactory $supplierFactory
    ) {
        $this->_supplierFactory = $supplierFactory;
    }

    /**
     * @param EventObserver $observer
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!$order) {
            return $this;
        }

        // get dropship products
        $items      = $order->getAllVisibleItems();
        $dropship   = [];

        // loop through order items
        foreach ($items as $item) {
            // get supplier id from order item
            $supplier = $item->getProduct()->getSupplier();

            if (!empty($supplier)) {
                // load the supplier if we have one
                $email = $this->_supplierFactory->load( $supplier )->getEmail();
                $dropship[$email]['items'][] = $item;
            }
        }

        if (empty($dropship)){
            return $this;
        }


        // TODO: loop through dropship array and send email with order info

        return $this;
    }
}
