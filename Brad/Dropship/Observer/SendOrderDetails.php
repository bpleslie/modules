<?php

namespace Brad\Dropship\Observer;

use Brad\Dropship\Model\SupplierFactory;
use Magento\Framework\App\Area;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Sales\Model\Order\Email\SenderBuilder;
use Magento\Store\Model\StoreManagerInterface;

class SendOrderDetails implements ObserverInterface
{
    /**
     * @var SupplierFactory
     */
    protected $_supplierFactory;

    /**
     * @var SenderBuilder
     */
    private $senderBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TransportBuilder
     */
    private $_transportBuilder;
    
    /**
     * @var StateInterface
     */
    private $inlineTranslation;

    /**
     * @param SupplierFactory $supplierFactory
     * @param SenderBuilder $senderBuilder
     * @param StoreManagerInterface $storeManager
     * @param TransportBuilder $_transportBuilder
     * @param StateInterface $inlineTranslation
     */
    public function __construct(
        SupplierFactory $supplierFactory,
        SenderBuilder $senderBuilder,
        StoreManagerInterface $storeManager ,
        TransportBuilder $_transportBuilder ,
        StateInterface $inlineTranslation

    ) {
        $this->_supplierFactory = $supplierFactory;
        $this->senderBuilder = $senderBuilder;
        $this->storeManager = $storeManager;
        $this->_transportBuilder = $_transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
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
        $items          = $order->getAllVisibleItems();
        $dropshipItems  = $this->getDropshipItems($items);

        if (empty($dropshipItems)){
            return $this;
        }

        foreach ($dropshipItems as $email => $items) {
            // TODO: finish email method
            $this->sendDropshipEmail($email, $items);
        }

        return $this;
    }

    /**
     * @param $items
     * @return mixed
     */
    public function getDropshipItems($items)
    {
        $dropshipItems = [];

        // loop through order items
        foreach ($items as $item) {
            // get supplier id from order item
            $supplier = $item->getProduct()->getSupplier();

            if (!empty($supplier)) {
                // load the supplier if we have one
                $email = $this->_supplierFactory->load($supplier)->getEmail();
                $dropshipItems[$email]['items'][] = $item;
            }
        }
        return $dropshipItems;
    }

    public function sendDropshipEmail($email, $items)
    {
        // TODO: clean this up -- pull sender email from config, make sure sending actually works, etc. Probably a better way to do this... idk
        $templateOptions = array('area' => Area::AREA_FRONTEND, 'store' => $this->storeManager->getStore()->getId());
        $templateVars = array(
            'store' => $this->storeManager->getStore(),
            'customer_name' => 'John Doe',
            'message'   => 'Hello World!!.'
        );
        $from = array('email' => "test@webkul.com", 'name' => 'Name of Sender');
        $this->inlineTranslation->suspend();
        $to = array($email,'Dropship');
        $transport = $this->_transportBuilder->setTemplateIdentifier('dropship_email_order_template')
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from)
            ->addTo($to)
            ->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }
}
