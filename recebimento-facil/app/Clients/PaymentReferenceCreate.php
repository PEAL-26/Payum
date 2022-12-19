<?php

namespace App\Clients;

use App\Entities\Reference;
use App\Libraries\SoapHeaderSecurity;
use App\Libraries\Uuid;
use SoapClient;

class PaymentReferenceCreate extends SoapClient
{
    private $url = 'https://spf-webservices-uat.bancoeconomico.ao:7443/soa-infra/services/SPF/WSI_PaymentRefCreate/WSI_PaymentRefCreate?wsdl';
    // private $url = 'http://www.dneonline.com/calculator.asmx?WSDL';

    public function __construct()
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
                'crypto_method' => STREAM_CRYPTO_METHOD_TLS_CLIENT
            )
        );

        $options = array(
            'soap_version' => SOAP_1_1,
            'exceptions' => true,
            'trace' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create($arrContextOptions)
        );

        parent::__construct($this->url, $options);
    }

    public function request(Reference $reference)
    {
        $uuid = new Uuid();

        /** HEADER SECURITY */
        // $data['USERNAME'] = env('SOAP.USERNAME');
        // $data['PASSWORD_HEADER_SECURITY'] = env('SOAP.PASSWORD_HEADER_SECURITY');
        // $data['CREATED'] = date('Y-m-d\TH:i:s');

        /** HEADER */
        $header['SOURCE'] = env('SOAP.SOURCE');
        $header['MSGID'] =  $uuid->v4() . '-' . date('Y-m-d\TH:i:s');
        $header['USERID'] =  env('SOAP.USERID');
        $header['BRANCH'] = env('SOAP.BRANCH');
        $header['PASSWORD_USER'] = env('SOAP.PASSWORD_USER');
        $header['INVOKETIMESTAMP'] = date('Y-m-d\TH:i:s');

        /** BODY */
        $body['AUTHTOKEN'] = env('SOAP.AUTHTOKEN');
        $body['ENTITYID'] = env('SOAP.ENTITYID');
        $body['PRODUCT_NO'] = env('SOAP.PRODUCT_NO');
        $body['SOURCE_ID'] = $reference->sourceId;
        $body['REFERENCE'] = $reference->reference ?? '?';
        $body['AMOUNT'] = $reference->amount ?? '?';
        $body['START_DATE'] = $reference->dateStart ?? date('Y-m-d');
        $body['END_DATE'] =  $reference->dateEnd ?? date('Y-m-d');
        $body['TAX_RATE'] = $reference->taxRate ?? '?';
        $body['CUSTOMER_NAME'] = $reference->customerName;
        $body['ADDRESS'] = $reference->customerAddress ?? '?';
        $body['TAX_ID'] =  $reference->customerTaxId ?? '?';
        $body['EMAIL'] =  $reference->customerEmail ?? '?';
        $body['PHONE_NUMBER'] =  $reference->customerPhoneNumber ?? '?';

        $headerSecurity = new SoapHeaderSecurity(env('SOAP.USERNAME'), env('SOAP.PASSWORD_USER'));
        $this->__setSoapHeaders($headerSecurity);

        $params = [
            'HEADER' => $header,
            'BODY' => ['Payment' => $body]
        ];

        $response = $this->paymentRefCreate_execute($params);

        return $response;
    }
}
