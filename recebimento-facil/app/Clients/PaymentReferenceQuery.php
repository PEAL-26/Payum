<?php

namespace App\Clients;

use App\Entities\Reference;
use App\Libraries\Uuid;

class PaymentReferenceQuery //extends SoapClient
{
    private $url = 'https://spf-webservices-uat.bancoeconomico.ao:7443/soa-infra/services/SPF/WSI_PaymentRefDetailsQuery/WSI_PaymentRefDetailsQuery?WSDL';

    public function request(Reference $reference)
    {
        $uuid = new Uuid();

        /** HEADER */
        $data['SOURCE'] = env('SOAP.SOURCE');
        $data['MSGID'] =  $uuid->v4() . '-' . date('Y-m-d\TH:i:s');
        $data['USERID'] =  env('SOAP.USERID');
        $data['BRANCH'] = env('SOAP.BRANCH');
        $data['PASSWORD_USER'] = env('SOAP.PASSWORD_USER');
        $data['INVOKETIMESTAMP'] = date('Y-m-d\TH:i:s');

        /** HEADER SECURITY */
        $data['USERNAME'] = env('SOAPUSERNAME');
        $data['PASSWORD_HEADER_SECURITY'] = env('SOAP.PASSWORD_HEADER_SECURITY');
        $data['CREATED'] = date('Y-m-d\TH:i:s');

        /** BODY */
        $data['AUTHTOKEN'] = env('SOAP.AUTHTOKEN');
        $data['ENTITYID'] = env('SOAP.ENTITYID');
        $data['PRODUCT_NO'] = env('SOAP.PRODUCT_NO');
        $data['SOURCE_ID'] = $reference->sourceId;
        $data['REFERENCE'] = $reference->reference ?? '?';
        $data['AMOUNT'] = $reference->amount ?? '?';
        $data['START_DATE'] = $reference->dateStart ?? date('Y-m-d');
        $data['END_DATE'] =  $reference->dateEnd ?? date('Y-m-d');
        $data['TAX_RATE'] = $reference->taxRate ?? '?';
        $data['CUSTOMER_NAME'] = $reference->customerName;
        $data['ADDRESS'] = $reference->customerAddress ?? '?';
        $data['TAX_ID'] =  $reference->customerTaxId ?? '?';
        $data['EMAIL'] =  $reference->customerEmail ?? '?';
        $data['PHONE_NUMBER'] =  $reference->customerPhoneNumber ?? '?';

        return null;
    }
}
