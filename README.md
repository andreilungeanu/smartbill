# Smartbill Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andreilungeanu/smartbill.svg?style=flat-square)](https://packagist.org/packages/andreilungeanu/smartbill)
[![Total Downloads](https://img.shields.io/packagist/dt/andreilungeanu/smartbill.svg?style=flat-square)](https://packagist.org/packages/andreilungeanu/smartbill)

A Laravel package for the Smartbill API, offering full compatibility with Laravel versions 11 and 12.

## Installation

You can install the package via composer:

```bash
composer require andreilungeanu/smartbill
```

## Configuration

You can publish the configuration file with:

```bash
php artisan vendor:publish --provider="AndreiLungeanu\Smartbill\SmartbillServiceProvider"
```

This will create a `config/smartbill.php` file in your application's config directory. You should add your Smartbill API credentials to your `.env` file:

```
SMARTBILL_USERNAME=your-username
SMARTBILL_API_TOKEN=your-api-token
```

## Usage

You can use the `Smartbill` facade to interact with the API. The package is divided into resources, each with its own set of methods.

---

## Invoices (Facturi)

### Create Invoice (Emitere factura)

This endpoint is used to create a new invoice.

**Method:** `Smartbill::invoices()->create(array $data)`

#### Issue invoice for NON-VAT taxpayer (Emitere factura firma neplatitoare de TVA)

```php
$data = [
    "companyVatCode" => "YOUR_COMPANY_VAT_CODE",
    "client" => [
      "name" => "UPBIT WEB DESIGN SRL",
      "vatCode" => "39521446",
      "isTaxPayer" => true,
      "address" => "str. Suhurlui, nr. 8",
      "city" => "Pechea",
      "county" => "Galati",
      "country" => "Romania",
      "email" => "contact@upbit.ro",
      "saveToDb" => false
    ],
    "issueDate" => now()->format('Y-m-d'),
    "seriesName" => "YOUR_INVOICE_SERIES",
    "isDraft" => false,
    "dueDate" => now()->addDays(14)->format('Y-m-d'),
    "deliveryDate" => now()->format('Y-m-d'),
    "products" => [
      [
        "name" => "Produs 1",
        "isDiscount" => false,
        "measuringUnitName" => "buc",
        "currency" => "RON",
        "quantity" => 1,
        "price" => 10,
        "saveToDb" => false,
        "isService" => false
      ]
    ]
];

$invoice = Smartbill::invoices()->create($data);
```

#### Issue draft invoice (Emitere factura ciorna)

```php
$data = [
    "companyVatCode" => "YOUR_COMPANY_VAT_CODE",
    "client" => [
        "name" => "UPBIT WEB DESIGN SRL"
    ],
    "issueDate" => now()->format('Y-m-d'),
    "seriesName" => "YOUR_INVOICE_SERIES",
    "isDraft" => true,
    "products" => [
        [
            "name" => "Produs 1",
            "price" => 10,
            "quantity" => 1
        ]
    ]
];

$invoice = Smartbill::invoices()->create($data);
```

---

### Get Invoice PDF (Vizualizare PDF Factura)

**Method:** `Smartbill::invoices()->getPdf(string $cif, string $seriesName, string $number)`

```php
$pdf = Smartbill::invoices()->getPdf('YOUR_COMPANY_VAT_CODE', 'SERIES', 'NUMBER');
```

---

### And many more...

This is just a small sample of the available methods. For a complete list of all available endpoints and their parameters, please see the [full documentation](DOCUMENTATION.md).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Andrei Lungeanu](https://github.com/andreilungeanu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
