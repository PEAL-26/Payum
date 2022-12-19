<?php

namespace App\Controllers\v1;

use App\Clients\PaymentReferenceCreate;
use App\Entities\Reference;
use App\Generics\ApiController;

class ReferenceCreateController extends ApiController
{

    public function handle()
    {
        $data =  (object)$this->request->getVar();

        $reference = new Reference($data);
        if (!$reference->isValid()) {
            return $this->responseJSON(null, $reference->getErrors(), 400);
        }

        try {
            $payment = new PaymentReferenceCreate();
            $response = $payment->request($reference);

            print($response);
        } catch (\Throwable $th) {
            log_message('error', $th);
            return $this->responseJSON(null, [], 500);
        }

        return $this->responseJSON(null, []);
    }
}
