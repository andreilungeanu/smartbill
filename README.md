# Smartbill Laravel Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andreilungeanu/smartbill.svg?style=flat-square)](https://packagist.org/packages/andreilungeanu/smartbill)
[![Total Downloads](https://img.shields.io/packagist/dt/andreilungeanu/smartbill.svg?style=flat-square)](https://packagist.org/packages/andreilungeanu/smartbill)

A Laravel package for the Smartbill API, supporting Laravel 11, 12 and 13. CI tests against Laravel 13.

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

You can interact with the API in three ways:

### 1. Using the Facade (recommended for Laravel)
This is the most convenient method for use within a Laravel application.
```php
use AndreiLungeanu\Smartbill\Facades\Smartbill;

$response = Smartbill::invoices()->createV2($invoiceData);
```

### 2. Using the Service Container
This is useful for dependency injection within your own classes.
```php
use AndreiLungeanu\Smartbill\Smartbill;

$smartbill = app(Smartbill::class);
$response = $smartbill->invoices()->createV2($invoiceData);
```

Both of these methods work seamlessly because Laravel's service container automatically handles the creation of the required HTTP client and injects it into the package.

### 3. Manual Instantiation (Advanced)
Parameterless `new Smartbill()` is no longer possible: the constructor now takes a configured HTTP client. If you need to use this package outside of a Laravel application or wish to manually construct the object, you must now provide a configured `Illuminate\Http\Client\PendingRequest` instance to its constructor.

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
use Illuminate\Support\Facades\Log;

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

// createV2 returns an array with the API response
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

`documentViewUrl` is a public link that needs no authentication. It serves an HTML view of
the document, not the PDF — use `getPdf()` below for the PDF bytes.
The deprecated `create()` returns the same body without the last three keys.

---

### Example 2: Downloading an Invoice PDF

This example shows how to download the PDF content of an existing invoice.

```php
use AndreiLungeanu\Smartbill\Facades\Smartbill;
use Illuminate\Support\Facades\Log;
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

This is just a small sample of the available methods. The authoritative contract is Smartbill's
OpenAPI spec at [api.smartbill.ro](https://api.smartbill.ro/).
[DOCUMENTATION.md](https://github.com/andreilungeanu/smartbill/blob/main/DOCUMENTATION.md) is an
older transcription kept on GitHub for reference; it still shows some deprecated signatures.

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
Smartbill sometimes wraps it in HTML meant for its own interface; the tags and the trailing
help text are removed, and the wrapped detail is kept.
`getResponse()` still holds the untouched response.

Exceeding the rate limit (30 calls per 10 seconds per token) throws
`SmartbillRateLimitException` and locks the token for ten minutes. Do not retry it.

## Known Issues

Smartbill-side behaviour, verified against the live API on 2026-08-23. Listed so callers
know what to expect; none of it is something this package can fix.

- **A rate-limit breach answers `403`, not the documented `429`.** The blocking response
  carries no `Retry-After` and no `X-RateLimit-*` headers, and its `cooldown` field is `0`
  even though the token stays locked for ten minutes. `X-RateLimit-*` ride on `2xx` only —
  every error response drops them, so they cannot drive a backoff.
- **A `cif` that does not belong to the account answers `401`, not `403`.** Retrying with
  fresh credentials will not help; the credentials are fine, the `cif` is not.
- **`GET /invoice/pdf` answers `502` with an nginx HTML page** for an unknown document or a
  missing parameter, which is indistinguishable from a real outage. `GET /estimate/pdf`
  answers a normal `400` with `errorText` for the same mistake.
- **`GET /payment/text` answers `500` with a Tomcat error page** for an unknown `id`.
- **`GET /estimate/invoices` answers `200` with a populated `errorText`** when the proforma
  exists but has not been invoiced. That is the normal state, not a failure — read
  `areInvoicesCreated`. A proforma that does not exist answers `410`.
- **`/document/send` requires `subject` and `bodyText` Base64 encoded.** Existence is
  checked first, so an unknown document answers `Documentul nu a fost gasit` and the Base64
  requirement never surfaces — test it against a document that exists.
- **`GET /series` lists only `f`, `p` and `c` series.** Other configured document types are
  not reachable through the API, filtered or unfiltered.
- **A `Bon` payment sent without `products` answers `500`.** Send the product lines; the
  documented `400` for a missing cash register only appears once the request is complete.

### ~~Internal Server Errors on Invalid Request Data~~ &mdash; fixed by Smartbill

> **Resolved.** This issue was found and reported from this package. Re-tested against the
> live API on 2026-08-23 and confirmed fixed: a misspelled field now returns `400` with
> `errors[].param` naming it &mdash; precisely the behaviour asked for below. The original
> report is kept struck through for the record. See [Error handling](#error-handling) for
> how the package surfaces it today.

~~When working with the Smartbill API, there are a few known issues to be aware of:~~

1.  ~~**Internal Server Errors on Invalid Request Data**:~~
    ~~The API may return a `500 Internal Server Error` when the request payload contains invalid data, such as a typo in a required field name.~~

    ~~For example, sending `nume` instead of `name` in the client object will trigger a `500` error:~~
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

    ~~Ideally, the API should respond with a `400 Bad Request` status and a helpful error message detailing which field is incorrect. Instead, it returns a generic `500` error, which makes debugging difficult as it incorrectly suggests a server-side failure rather than a client-side mistake.~~

~~While this package attempts to mitigate these issues where possible, the fundamental problems lie with the API's implementation. We are awaiting fixes from the Smartbill provider to ensure more reliable and standards-compliant behavior.~~

## AI coding agents (Laravel Boost)

This package ships AI guidelines at `resources/boost/guidelines/core.blade.php`. If your
application uses [Laravel Boost](https://github.com/laravel/boost) (`^2.2`), Boost can fold
them into your agent instruction files (`CLAUDE.md`, `AGENTS.md`, Cursor rules, ...) so your
assistant knows the API's traps before it writes a single call.

Boost will not enable them on its own — you have to opt in:

```bash
php artisan boost:install
```

On an existing Boost install, use `php artisan boost:update --discover` instead (Boost
2.3.2+; the default from 2.5.0). Either way, pick `andreilungeanu/smartbill` when Boost
asks which third-party guidelines to include. Boost skips that prompt in non-interactive
runs, and from 2.5.3 also when `boost:update` runs as a Composer script — so a
`post-update-cmd` hook will not add the package for you.

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
