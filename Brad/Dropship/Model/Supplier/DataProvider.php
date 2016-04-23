<?php

namespace Brad\Dropship\Model\Supplier;

use Brad\Dropship\Model\ResourceModel\Supplier\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var \Brad\Dropship\Model\ResourceModel\Supplier\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $supplierCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $supplierCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $supplierCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $supplier \Brad\Dropship\Model\Supplier */
        foreach ($items as $supplier) {
            $this->loadedData[$supplier->getId()] = $supplier->getData();
        }

        $data = $this->dataPersistor->get('supplier');
        if (!empty($data)) {
            $supplier = $this->collection->getNewEmptyItem();
            $supplier->setData($data);
            $this->loadedData[$supplier->getId()] = $supplier->getData();
            $this->dataPersistor->clear('supplier');
        }

        return $this->loadedData;
    }
}
