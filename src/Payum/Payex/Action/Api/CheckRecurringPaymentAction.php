<?php

namespace Payum\Payex\Action\Api;

use ArrayAccess;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\ApiAwareTrait;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Payex\Api\RecurringApi;
use Payum\Payex\Request\Api\CheckRecurringPayment;

class CheckRecurringPaymentAction implements ActionInterface, ApiAwareInterface
{
    use ApiAwareTrait;

    public function __construct()
    {
        $this->apiClass = RecurringApi::class;
    }

    public function execute($request)
    {
        /** @var CheckRecurringPayment $request */
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $model->validatedKeysSet([
            'agreementRef',
        ]);

        $result = $this->api->check((array) $model);

        $model->replace($result);
    }

    public function supports($request)
    {
        return $request instanceof CheckRecurringPayment &&
            $request->getModel() instanceof ArrayAccess
        ;
    }
}
