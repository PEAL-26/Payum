# Pagamento Fácil API

## Production

### Configuração do SOAP Client

- Localize **php.ini** na pasta bin do apache , ou seja **Apache/bin/php.ini**
- Remova o **;** do início de **extension=soap**
- Reinicie seu servidor Apache

### Configuração das variáveis de ambiente

- arquivo: .env

## Endpoints

### Criar referência -> **POST** -> [BASE_URL:PORT/api/v1/references]

- **request**

```sh request
{
 "source_id": string,
 "reference": string,
 "amount": decimal,
 "date_start": date('Y-m-d'),
 "date_end": date('Y-m-d'),
 "tax_rate": decimal,
 "customer_name": string,
 "customer_address": string,
 "customer_tax_id": string,
 "customer_email": string,
 "customer_phone_number": string,
 "tipo": "OUTRO" ou "FACTURA"
}
```

- **response**

```sh
{
 "source_id": string,
 "reference": string,
 "amount": decimal,
 "date_start": date('Y-m-d'),
 "date_end": date('Y-m-d'),
 "tax_rate": decimal,
 "customer_name": string,
 "customer_address": string,
 "customer_tax_id": string,
 "customer_email": string,
 "customer_phone_number": string,
 "tipo": "OUTRO" ou "FACTURA"
}
```

### Consultar referência -> **GET** -> [BASE_URL:PORT/api/v1/references]

- **request**

```sh request
{
 "source_ids": [],
 "payment_ids": [],
}
```

- **response**

```sh
[
    {
     "payment_id":"38400"
     "source_id":"BANCOINT1"
     "entity_id":"01003"
     "reference":"010001154"
     "amount":"1"
     "date_start":"2019-08-12+01:00"
     "date_end":"2019-08-15+01:00"
     "status":"ACTIVE"
    },
    ...
]
```

### Cancelar referência -> **POST** -> [BASE_URL:PORT/api/v1/references/cancel]

- **request**

```sh request
{
 
}
```

- **response**

```sh
{

}
```
