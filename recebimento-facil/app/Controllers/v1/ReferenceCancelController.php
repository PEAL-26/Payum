<?php

namespace App\Controllers\v1;

use App\Generics\ApiController;

class ReferenceCancelController extends ApiController
{
    public function handle()
    {
        $data =  (object)$this->request->getVar();
    }
}
