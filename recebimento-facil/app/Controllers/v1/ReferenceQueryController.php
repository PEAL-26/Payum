<?php

namespace App\Controllers\v1;

use App\Entities\Reference;
use App\Generics\ApiController;

class ReferenceQueryController extends ApiController
{
    public function handle()
    {
        $data =  (object)$this->request->getVar();

        $reference = new Reference();
        $reference->addPaymentIds($data->payment_ids ?? []);
        $reference->addSourceIds($data->source_ids ?? []);
    }
}
