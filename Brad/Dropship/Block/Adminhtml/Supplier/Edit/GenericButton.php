<?php

namespace Brad\Dropship\Block\Adminhtml\Supplier\Edit;

use Magento\Backend\Block\Widget\Context;
use Brad\Dropship\Api\SupplierRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var SupplierRepositoryInterface
     */
    protected $supplierRepository;

    /**
     * @param Context $context
     * @param SupplierRepositoryInterface $supplierRepository
     */
    public function __construct(
        Context $context,
        SupplierRepositoryInterface $supplierRepository
    ) {
        $this->context = $context;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Return supplier ID
     *
     * @return int|null
     */
    public function getId()
    {
        try {
            return $this->supplierRepository->getById(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
