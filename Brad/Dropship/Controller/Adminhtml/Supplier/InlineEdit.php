<?php

namespace Brad\Dropship\Controller\Adminhtml\Supplier;

use Brad\Dropship\Model\Supplier;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Brad\Dropship\Api\SupplierRepositoryInterface as SupplierRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Brad\Dropship\Api\Data\SupplierInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Cms supplier grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends Action
{
    /** @var PostDataProcessor */
    protected $dataProcessor;

    /** @var SupplierRepository  */
    protected $supplierRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param SupplierRepository $supplierRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        SupplierRepository $supplierRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->supplierRepository = $supplierRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $supplierId) {
            /** @var Supplier $supplier */
            $supplier = $this->supplierRepository->getById($supplierId);
            try {
                $supplierData = $this->filterPost($postItems[$supplierId]);
                $this->validatePost($supplierData, $supplier, $error, $messages);
                $extendedSupplierData = $supplier->getData();
                $this->setCmsSupplierData($supplier, $extendedSupplierData, $supplierData);
                $this->supplierRepository->save($supplier);
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithSupplierId($supplier, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithSupplierId($supplier, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithSupplierId(
                    $supplier,
                    __('Something went wrong while saving the supplier.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Filtering posted data.
     *
     * @param array $postData
     * @return array
     */
    protected function filterPost($postData = [])
    {
        return $this->dataProcessor->filter($postData);
    }

    /**
     * Validate post data
     *
     * @param array $supplierData
     * @param Supplier $supplier
     * @param bool $error
     * @param array $messages
     * @return void
     */
    protected function validatePost(array $supplierData, Supplier $supplier, &$error, array &$messages)
    {
        if (!($this->dataProcessor->validate($supplierData) && $this->dataProcessor->validateRequireEntry($supplierData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithSupplierId($supplier, $error->getText());
            }
        }
    }

    /**
     * Add supplier title to error message
     *
     * @param SupplierInterface $supplier
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithSupplierId(SupplierInterface $supplier, $errorText)
    {
        return '[Supplier ID: ' . $supplier->getId() . '] ' . $errorText;
    }

    /**
     * Set cms supplier data
     *
     * @param Supplier $supplier
     * @param array $extendedSupplierData
     * @param array $supplierData
     * @return $this
     */
    public function setDropshipSupplierData(Supplier $supplier, array $extendedSupplierData, array $supplierData)
    {
        $supplier->setData(array_merge($supplier->getData(), $extendedSupplierData, $supplierData));
        return $this;
    }
}
