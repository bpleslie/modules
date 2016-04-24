<?php

namespace Brad\Restrictions\Controller\Adminhtml\Rule;

use Brad\Restrictions\Model\Rule;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Brad\Restrictions\Api\RuleRepositoryInterface as RuleRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Brad\Restrictions\Api\Data\RuleInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Cms rule grid inline edit controller
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class InlineEdit extends Action
{
    /** @var PostDataProcessor */
    protected $dataProcessor;

    /** @var RuleRepository  */
    protected $ruleRepository;

    /** @var JsonFactory  */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param PostDataProcessor $dataProcessor
     * @param RuleRepository $ruleRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        RuleRepository $ruleRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->ruleRepository = $ruleRepository;
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

        foreach (array_keys($postItems) as $ruleId) {
            /** @var Rule $rule */
            $rule = $this->ruleRepository->getById($ruleId);
            try {
                $ruleData = $this->filterPost($postItems[$ruleId]);
                $this->validatePost($ruleData, $rule, $error, $messages);
                $extendedRuleData = $rule->getData();
                $this->setCmsRuleData($rule, $extendedRuleData, $ruleData);
                $this->ruleRepository->save($rule);
            } catch (LocalizedException $e) {
                $messages[] = $this->getErrorWithRuleId($rule, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithRuleId($rule, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithRuleId(
                    $rule,
                    __('Something went wrong while saving the rule.')
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
     * @param array $ruleData
     * @param Rule $rule
     * @param bool $error
     * @param array $messages
     * @return void
     */
    protected function validatePost(array $ruleData, Rule $rule, &$error, array &$messages)
    {
        if (!($this->dataProcessor->validate($ruleData) && $this->dataProcessor->validateRequireEntry($ruleData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithRuleId($rule, $error->getText());
            }
        }
    }

    /**
     * Add rule title to error message
     *
     * @param RuleInterface $rule
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithRuleId(RuleInterface $rule, $errorText)
    {
        return '[Rule ID: ' . $rule->getId() . '] ' . $errorText;
    }

    /**
     * Set cms rule data
     *
     * @param Rule $rule
     * @param array $extendedRuleData
     * @param array $ruleData
     * @return $this
     */
    public function setRestrictionsRuleData(Rule $rule, array $extendedRuleData, array $ruleData)
    {
        $rule->setData(array_merge($rule->getData(), $extendedRuleData, $ruleData));
        return $this;
    }
}
