# Smartbill Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andreilungeanu/smartbill.svg?style=flat-square)](https://packagist.org/packages/andreilungeanu/smartbill)
[![Total Downloads](https://img.shields.io/packagist/dt/andreilungeanu/smartbill.svg?style=flat-square)](https://packagist.org/packages/andreilungeanu/smartbill)

A Laravel package for the Smartbill API, offering full compatibility with Laravel versions 11, 12, and 13.

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
SMARTBILL_API_USERNAME=your-username
SMARTBILL_API_TOKEN=your-api-token

# Optional — HTTP request timeout in seconds (default: 30)
SMARTBILL_TIMEOUT=30
```

## Usage Examples

You can interact with the API in two primary ways:

#### 1. Using the Facade (recommended for Laravel)
This is the most convenient method for use within a Laravel application.
```php
use AndreiLungeanu\Smartbill\Facades\Smartbill;

$response = Smartbill::invoices()->createV2($invoiceData);
```

#### 2. Using the Service Container
This is useful for dependency injection within your own classes.
```php
use AndreiLungeanu\Smartbill\Smartbill;

$smartbill = app(Smartbill::class);
$response = $smartbill->invoices()->createV2($invoiceData);
```

Both of these methods work seamlessly because Laravel's service container automatically handles the creation of the required HTTP client and injects it into the package.

#### 3. Manual Instantiation (Advanced)
Direct instantiation with `new Smartbill()` is no longer possible due to the new dependency injection requirement. If you need to use this package outside of a Laravel application or wish to manually construct the object, you must now provide a configured `Illuminate\Http\Client\PendingRequest` instance to its constructor.

```php
use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\Factory;

// Manually create and configure the HTTP client
$http = new Factory();
$client = $http->withBasicAuth('your-username', 'your-api-token')
    ->baseUrl('https://ws.smartbill.ro/SBORO/api')
    ->acceptJson();

// Pass the configured client to the constructor
$smartbill = new Smartbill($client);
$response = $smartbill->invoices()->createV2($invoiceData);
```

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
        "taxName" => "Normala",
        "taxPercentage" => 21,
        "saveToDb" => false,
        "isService" => false
      ]
    ]
];

// The create method returns an array with the API response
try {
   $response = Smartbill::invoices()->createV2($invoiceData);
   // You can now access the invoice number
   $invoiceNumber = $response['number']; // "0044"
} catch (\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException $e) {
   // Handle Smartbill API errors
   Log::error('Smartbill API error: ' . $e->getMessage());
   // Optionally, show a user-friendly message or handle as needed
}
```

#### Example API Response

A successful `createV2` call will return an array decoded from the following JSON structure:

```json
{
    "errorText": "",
    "message": "",
    "number": "0044",
    "series": "SBINV",
    "url": "",
    "documentUrl": "https://cloud.smartbill.ro/documente/editare/factura/20363/",
    "documentId": 20363,
    "documentViewUrl": "https://cloud.smartbill.ro/documente/extern/pf/factura/f6a9a7d3..."
}
```

`documentViewUrl` is a public link to the PDF that needs no authentication.
The deprecated `create()` returns the same body without the last three keys.

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
// and throws SmartbillApiException if the request fails.
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

## Error handling

A misspelled or wrongly typed field is rejected with `400` and a body that names the
offending field. The package surfaces it as `SmartbillRequestException`:

```php
use AndreiLungeanu\Smartbill\Exceptions\SmartbillRequestException;

try {
    Smartbill::invoices()->createV2($invoiceData);
} catch (SmartbillRequestException $e) {
    $e->getParam();     // "client.nume", or "products[0].quantity" inside a list
    $e->getErrorCode(); // "json_mapping_error"
}
```

Every other failure throws `SmartbillApiException`. `getMessage()` is the cause only —
Smartbill sometimes wraps it in HTML meant for its own interface, and that is stripped.
`getResponse()` still holds the untouched response.

Exceeding the rate limit (30 calls per 10 seconds per token) throws
`SmartbillRateLimitException` and locks the token for ten minutes. Do not retry it.

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
