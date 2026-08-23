## Smartbill

`andreilungeanu/smartbill` wraps the Smartbill.ro V1 REST API (Romanian invoicing). Resolve
it from the container or the `Smartbill` facade — the service provider binds it as a
singleton with an authenticated HTTP client. Never construct it with `new Smartbill()`; the
constructor takes a pre-built `Illuminate\Http\Client\PendingRequest`.

One method per resource: `invoices()`, `estimates()`, `payments()`, `taxes()`, `series()`,
`stocks()`, `document()`.

@verbatim
<code-snippet name="Creating an invoice" lang="php">
use AndreiLungeanu\Smartbill\Facades\Smartbill;

$response = Smartbill::invoices()->createV2($invoiceData);
$response['number'];      // "0044"
$response['documentId'];  // 50001414
</code-snippet>
@endverbatim

### Conventions and gotchas

- **Use `createV2()`, never `create()`.** `create()` is deprecated: it calls an endpoint
  Smartbill does not document, and its response omits `documentUrl`, `documentId` and
  `documentViewUrl`. The same applies to `estimates()`.
- **`documentViewUrl` is an HTML page, not a PDF.** It needs no authentication. For PDF
  bytes call `invoices()->getPdf($cif, $series, $number)`, which returns a raw string.
- **Never retry a `SmartbillRateLimitException`.** The limit is 30 calls per 10 seconds per
  token, and a breach locks the token for ten minutes. Smartbill answers the breach with
  `403`, not the documented `429`, and sends no `X-RateLimit-*` headers on any error
  response, so a retry loop turns a ten second wait into a ten minute outage.
- **`document()->send()` needs `subject` and `bodyText` Base64 encoded.** Plain text is
  rejected with a `400`. The package does not encode them for you.
- **A populated `errorText` on a `200` is not always a failure.** For
  `estimates()->getInvoices()` it means the proforma exists but has not been invoiced —
  read `areInvoicesCreated` instead. The package already treats that case as success.
- **A misspelled or wrongly typed field throws `SmartbillRequestException`**, which names
  the offending field. Catch it before the base class.

@verbatim
<code-snippet name="Reading a rejected field" lang="php">
use AndreiLungeanu\Smartbill\Exceptions\SmartbillRequestException;

try {
    Smartbill::invoices()->createV2($invoiceData);
} catch (SmartbillRequestException $e) {
    $e->getParam();     // "client.nume", or "products[0].quantity" inside a list
    $e->getErrorCode(); // "json_mapping_error"
}
</code-snippet>
@endverbatim

Every other failure throws `SmartbillApiException`; `getResponse()` holds the untouched
response. Configuration lives in `config/smartbill.php`, driven by `SMARTBILL_API_USERNAME`
and `SMARTBILL_API_TOKEN`. A missing one throws `SmartbillConfigurationException` naming it.

### Testing

Fake with literal URLs against the base `https://ws.smartbill.ro/SBORO/api`, and call
`Http::preventStrayRequests()` so an unmatched call fails loudly instead of hitting the
live API.

@verbatim
<code-snippet name="Faking a Smartbill call" lang="php">
use Illuminate\Support\Facades\Http;

Http::preventStrayRequests();
Http::fake([
    'https://ws.smartbill.ro/SBORO/api/invoice/v2' => Http::response([
        'errorText' => '', 'number' => '0044', 'series' => 'TE',
    ]),
]);
</code-snippet>
@endverbatim
