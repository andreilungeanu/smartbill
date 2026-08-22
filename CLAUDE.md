# Smartbill

Laravel package wrapping the Smartbill.ro REST API. Runtime target is **PHP 8.2** — avoid 8.3+ syntax in `src/` so Laravel 11/12 consumers still work. Tests are Pest 5 (PHP 8.4+, PHPUnit 13); CI runs 8.4/8.5. Laravel 11 is excluded on PHP 8.5.

## Commands

```bash
composer test                # Pest 5
composer test:type-coverage  # type-coverage plugin
composer analyse             # PHPStan level 6 (src + tests)
composer lint                # Pint + PHPStan
```

Single test: `vendor/bin/pest --filter='returns the invoice number'`. CI auto-fixes Pint style on push, so formatting locally is optional.

## Architecture

- [src/Smartbill.php](src/Smartbill.php) — dispatcher. One method per API resource, each returns an endpoint object.
- [src/Endpoints/](src/Endpoints/) — 7 endpoint classes (Invoices, Estimates, Payments, Taxes, Series, Stocks, Document). All extend [BaseEndpoint](src/Endpoints/BaseEndpoint.php), which holds an injected `PendingRequest` and `sendQuery()` for query-string PUT/DELETE.
- [src/SmartbillServiceProvider.php](src/SmartbillServiceProvider.php) — binds `Smartbill::class` **as a singleton** with a basic-auth `PendingRequest`. Throws `InvalidArgumentException` at resolve time if creds are empty.
- [src/Exceptions/SmartbillApiException.php](src/Exceptions/SmartbillApiException.php) — takes a failing `Response`. Message = JSON `errorText` → raw body → `"Smartbill API error"`. HTTP status → exception code. Logs via Laravel's `report()` method — caught exceptions stay silent, unhandled ones hit the framework's exception handler.
- Full upstream API reference: [DOCUMENTATION.md](DOCUMENTATION.md). Consult before inventing endpoint signatures.

## Entry points

```php
Smartbill::invoices()->create($data);           // facade
app(Smartbill::class)->invoices()->create($data); // container
```

Both resolve the same singleton. Tests use the container form.

## The endpoint pattern

Every method routes its response through `decode()` (JSON) or `download()` (PDF bytes)
from [BaseEndpoint](src/Endpoints/BaseEndpoint.php). Both call `guard()`, which is the
only place that decides success or failure:

```php
return $this->decode($this->client->post('/invoice/v2', $data));   // JSON
return $this->download($this->client->get('/invoice/pdf', $query)); // PDF bytes
```

GET already puts the second argument on the query string. PUT/DELETE in Laravel send a
JSON body, but Smartbill expects query parameters — use `sendQuery()` so the singleton
client is not mutated:

```php
return $this->decode($this->sendQuery('DELETE', '/invoice', $query));
```

`guard()` throws when the status failed **or** when `errorText` is non-empty, because a
2xx alone does not mean success in V1. `SmartbillApiException::from()` picks the class:
`SmartbillRateLimitException` on 429, `SmartbillRequestException` when the body is an
`invalid_request_error`, the base class otherwise.

Optional query params are appended when `!== null` — never on truthiness, since `"0"` is
a valid `productCode` (see [StocksEndpoint::list()](src/Endpoints/StocksEndpoint.php)).
When adding an endpoint, copy the matching pattern — never reach for the `Http` facade;
[tests/ArchTest.php](tests/ArchTest.php) enforces that endpoints extend `BaseEndpoint`,
end in `Endpoint`, and can't use `Http`/`curl_exec`/`file_get_contents`.

## Config

Env vars: `SMARTBILL_API_USERNAME`, `SMARTBILL_API_TOKEN`, optional `SMARTBILL_API_URL` (defaults to `https://ws.smartbill.ro/SBORO/api`), optional `SMARTBILL_TIMEOUT` in seconds (defaults to 30). See [config/smartbill.php](config/smartbill.php).

## Test pattern

Pest 5. [tests/Pest.php](tests/Pest.php) wires the suite with `pest()->extends(TestCase::class)`
(not `uses(...)->in(...)`), a global `pest()->beforeEach()` that calls
`Http::preventStrayRequests()`, and the `smartbill()` helper that resolves the container
singleton. Named datasets live in [tests/Datasets/](tests/Datasets/) and are pulled in with
`->with('endpoints')`.

Feature tests group by method with `describe()`, fake with `Http::fake([literal-URL => ...])`,
and assert failures with `->throws(...)`. Arch tests use the bare `arch()->expect(...)` form.
Suite credentials live in [tests/TestCase.php](tests/TestCase.php). PHPUnit runs in random
order with `failOnWarning`/`failOnRisky` — new tests must be independent, and a helper
declared in a test file must not collide with a Laravel global (`response()` does).

## Gotchas

- Manual instantiation requires a pre-built `PendingRequest` — the old string-credentials constructor is gone. Resolve through the container instead.
- A misspelled field (e.g. `nume` vs `name`) returns **400** with an `invalid_request_error`
  body naming the field in `errors[].param` — verified live on both `/invoice/v2` and
  `/invoice`. Older notes claiming a 500 with an HTML body are out of date.
- `POST /invoice` and `POST /estimate` are live but absent from the OpenAPI spec and answer
  with a smaller envelope (no `documentUrl`/`documentId`/`documentViewUrl`). They are
  `@deprecated` in favour of `createV2()`.
- `/estimate/invoices` answers **HTTP 200 with a populated errorText** when the estimate
  exists but has not been invoiced — confirmed live. That is the case `guard()` exists for,
  but it is a normal state there, so `getInvoices()` passes `errorTextIsFailure: false`;
  a missing estimate still answers 410 and throws.
- `/document/send` requires `subject` and `bodyText` **Base64 encoded**; plain text is
  rejected with a 400. `payments()->getText()` mirrors it — the receipt text comes back
  Base64 in `message`. Neither is encoded or decoded by the package.
- `/document/send` answers with a third error envelope, in neither the spec nor the prose:
  `{"status": {"code": 1, "message": "..."}}` — no `errorText`. `resolveMessage()` reads it.
- `/invoice/pdf` answers **502 with an nginx HTML page** for a missing parameter or unknown
  document, and `/payment/text` answers 500 with a Tomcat page. `SmartbillApiException`
  keeps only the text before the first `<`; the raw body stays on `getResponse()`.
- V1 allows 30 calls per 10 seconds per token and locks the token for ten minutes on
  breach. Do not blind-retry a `SmartbillRateLimitException`.
- `composer.json` sets `minimum-stability: dev` + `prefer-stable: true` — intentional, leave it.
