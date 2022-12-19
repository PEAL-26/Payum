<?php

namespace App\Entities;

class Reference
{
    private $errors = [];

    public function __construct($data = null)
    {
        $this->sourceId = $data->source_id ?? '';
        $this->reference = $data->reference ?? '';
        $this->amount = $data->amount ?? '';
        $this->dateStart = $data->date_start ?? '';
        $this->dateEnd = $data->date_end ?? '';
        $this->taxRate = $data->tax_rate ?? '';
        $this->customerName = $data->customer_name ?? '';
        $this->customerAddress = $data->customer_address ?? '';
        $this->customerTaxId = $data->customer_tax_id ?? '';
        $this->customerEmail = $data->customer_email ?? '';
        $this->customerPhoneNumber = $data->customer_phone_number ?? '';
        $this->tipo = $data->tipo ?? '';

        if ($this->isEmpty($this->sourceId)) $this->addError("Campo 'source_id' obrigatório.");
        if ($this->isEmpty($this->amount)) $this->addError("Campo 'amount' obrigatório.");
        if ($this->isEmpty($this->tipo)) $this->addError("Campo 'tipo' obrigatório. (EX.: FACTURA ou OUTRO)");
        if ($this->tipo === 'FACTURA') {
        }
    }

    public function isValid()
    {
        return count($this->errors) === 0;
    }

    public function isEmpty($value)
    {
        return empty(trim($value)) || is_null(trim($value));
    }

    public function addError($message)
    {
        $this->errors[] = $message;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function addPaymentIds($payments = [])
    {
    }

    public function addSourceIds($sources = [])
    {
    }
}
