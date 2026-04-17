# Smartbill

Laravel package wrapping the Smartbill.ro REST API. Target **PHP 8.2** — CI runs `prefer-lowest` on 8.2 across Laravel 11/12 on Ubuntu + Windows (Laravel 13 requires PHP 8.3+, so that matrix row is excluded), so avoid 8.3+ syntax in code that must work on L11/L12.

## Commands

```bash
composer test                # Pest v4
composer test:type-coverage  # type-coverage plugin
composer analyse             # PHPStan level 5 (src + tests)
composer lint                # Pint + PHPStan
```

Single test: `vendor/bin/pest --filter='can create an invoice'`. CI auto-fixes Pint style on push, so formatting locally is optional.

## Architecture

- [src/Smartbill.php](src/Smartbill.php) — dispatcher. One method per API resource, each returns an endpoint object.
- [src/Endpoints/](src/Endpoints/) — 7 endpoint classes (Invoices, Estimates, Payments, Taxes, Series, Stocks, Document). All extend [BaseEndpoint](src/Endpoints/BaseEndpoint.php), which just holds an injected `PendingRequest`.
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

Every endpoint method follows this shape — no exceptions:

```php
return $this->client
    ->{verb}('/path', $data)
    ->throw(fn (Response $r) => throw new SmartbillApiException($r))
    ->json(); // ->body() for PDFs
```

Optional query params are appended conditionally (see [SeriesEndpoint::list()](src/Endpoints/SeriesEndpoint.php)). When adding an endpoint, copy the pattern — never reach for the `Http` facade; [tests/ArchTest.php](tests/ArchTest.php) enforces that endpoints extend `BaseEndpoint`, end in `Endpoint`, and can't use `Http`/`curl_exec`/`file_get_contents`.

## Config

Env vars: `SMARTBILL_API_USERNAME`, `SMARTBILL_API_TOKEN`, optional `SMARTBILL_API_URL` (defaults to `https://ws.smartbill.ro/SBORO/api`), optional `SMARTBILL_TIMEOUT` in seconds (defaults to 30). See [config/smartbill.php](config/smartbill.php).

## Test pattern

Feature tests use `Http::fake([literal-URL => Http::response(...)])` and resolve via `app(Smartbill::class)`. Suite credentials live in [tests/TestCase.php](tests/TestCase.php). PHPUnit runs in random order with `failOnWarning`/`failOnRisky` — new tests must be independent.

## Gotchas

- Manual instantiation requires a pre-built `PendingRequest` — the old string-credentials constructor is gone. Resolve through the container instead.
- Smartbill's upstream API returns HTTP 500 on malformed payloads (e.g. `nume` vs `name`) instead of 400. `SmartbillApiException` surfaces whatever body came back — don't infer validation errors from the 500 itself.
- `composer.json` sets `minimum-stability: dev` + `prefer-stable: true` — intentional, leave it.
