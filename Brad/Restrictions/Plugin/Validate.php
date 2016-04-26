<?php

namespace Brad\Restrictions\Plugin;

use Brad\Restrictions\Model\Rule;
use Magento\Sales\Model\Order\Address\Validator;

class Validate {

    protected $rule;

    public function __construct(
        Rule $rule
    )
    {
        $this->returnValue = $rule;
    }

    public function afterValidate(Validator $validator) {
        // TODO: if the address is valid, make sure there aren't any uncaught restrictions (final check)
        $value = $this->returnValue->getValue();
        $validator->getResponse()->appendBody( $value );
    }
}