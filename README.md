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

## Usage Examples

You can interact with the API in three ways:

#### 1. Using the Facade (recommended for Laravel)
```php
use AndreiLungeanu\Smartbill\Facades\Smartbill;

$response = Smartbill::invoices()->create($invoiceData);
```

#### 2. Using the Service Container
```php
use AndreiLungeanu\Smartbill\Smartbill;

$smartbill = app(Smartbill::class);
$response = $smartbill->invoices()->create($invoiceData);
```

#### 3. Direct Instantiation
```php
use AndreiLungeanu\Smartbill\Smartbill;

$smartbill = new Smartbill();
$response = $smartbill->invoices()->create($invoiceData);
```

All three methods are valid and provide the same functionality. The Facade is the most convenient for typical Laravel usage, while the other two are useful for testing, dependency injection, or use outside Laravel.

### Example 1: Creating an Invoice

This example shows how to create a new invoice and retrieve its number.

```php
use AndreiLungeanu\Smartbill\Facades\Smartbill;

$invoiceData = [
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

// The create method returns an array with the API response
try {
   $response = Smartbill::invoices()->create($invoiceData);
   // You can now access the invoice number
   $invoiceNumber = $response['number']; // "0044"
} catch (\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException $e) {
   // Handle Smartbill API errors
   Log::error('Smartbill API error: ' . $e->getMessage());
   // Optionally, show a user-friendly message or handle as needed
}
```

#### Example API Response

A successful `create` call will return an array decoded from the following JSON structure:

```json
{
    "errorText": "",
    "message": "",
    "number": "0044",
    "series": "SBINV",
    "url": ""
}
```

---

### Example 2: Downloading an Invoice PDF

This example shows how to download the PDF content of an existing invoice.

```php
use AndreiLungeanu\Smartbill\Facades\Smartbill;
use Illuminate\Support\Facades\Storage;

$cif = 'YOUR_COMPANY_VAT_CODE';
$series = 'SBINV';
$number = '0044';

// The getPdf method returns the raw PDF content as a string on success,
// or an array with error details if not found or failed.
try {
    $pdfContent = Smartbill::invoices()->getPdf($cif, $series, $number);
    Storage::disk('local')->put("invoices/{$series}-{$number}.pdf", $pdfContent);
} catch (\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException $e) {
    Log::error('Smartbill PDF error: ' . $e->getMessage());
    // Optionally, show a user-friendly message or handle as needed
}
```

---

### And many more...

This is just a small sample of the available methods. For a complete list of all available endpoints and their parameters, please see the [full documentation](DOCUMENTATION.md).

## Known Issues

When working with the Smartbill API, there are a few known issues to be aware of:

1.  **Non-Compliant Headers on 400 Errors**:
    A significant issue exists where the Smartbill API returns non-compliant HTTP headers, particularly when a `400 Bad Request` status is returned. This has been most commonly observed on endpoints like `/invoice/pdf` and `/estimates/pdf` when a document is not found. The API returns headers with spaces in their names (e.g., `"Entitate atasata"`), which violates the HTTP/1.1 specification (RFC 7230, section 3.2).

    This non-compliance causes standard HTTP clients like Guzzle (used by Laravel) to throw an `InvalidArgumentException`, as they enforce strict adherence to HTTP standards. To manage this, the package includes a temporary workaround that uses a raw cURL request to bypass Guzzle's validation. While this allows the application to handle the error, please be aware that this behavior may occur in other situations as well.

2.  **Internal Server Errors on Invalid Request Data**:
    The API may return a `500 Internal Server Error` when the request payload contains invalid data, such as a typo in a required field name.

    For example, sending `nume` instead of `name` in the client object will trigger a `500` error:
    ```php
    $invoiceData = [
        "companyVatCode" => "YOUR_COMPANY_VAT_CODE",
        "client" => [
          "nume" => "Test Client SRL", // Incorrect: should be "name"
          "vatCode" => "12345678",
          // ...
        ],
        // ...
    ];
    ```

    Ideally, the API should respond with a `400 Bad Request` status and a helpful error message detailing which field is incorrect. Instead, it returns a generic `500` error, which makes debugging difficult as it incorrectly suggests a server-side failure rather than a client-side mistake.

While this package attempts to mitigate these issues where possible, the fundamental problems lie with the API's implementation. We are awaiting fixes from the Smartbill provider to ensure more reliable and standards-compliant behavior.
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

- [AndreiLungeanu](https://github.com/andreilungeanu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
