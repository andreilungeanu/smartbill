# Contributing

Thanks for considering a contribution!

## Development setup

```bash
composer install
```

## Commands

```bash
composer test                # Run the Pest test suite
composer test:type-coverage  # Type coverage check
composer analyse             # PHPStan level 6 (src + tests)
composer lint                # Pint + PHPStan
```

Run a single test:

```bash
vendor/bin/pest --filter='returns the invoice number'
```

## Guidelines

- Target **PHP 8.2** in `src/` — avoid 8.3+ syntax. Tests are Pest 5 and run on PHP 8.4+.
- Every endpoint method goes through [BaseEndpoint](../src/Endpoints/BaseEndpoint.php): send with `sendQuery()` (GET/PUT/DELETE, query string) or `sendJson()` (POST, JSON body), and route the response through `decode()` or `download()`. Pass `allowEmpty: true` to `decode()` only for calls whose answer the caller does not need (cancel, restore, delete).
- Never use the `Http` facade inside endpoints, and never call `$this->client` directly — the senders build a fresh client per request. Arch tests enforce the facade ban.
- Check the OpenAPI spec in [docs/smartbill-openapi-spec.json](../docs/smartbill-openapi-spec.json) before adding or changing endpoint signatures. [DOCUMENTATION.md](../DOCUMENTATION.md) is an older transcription and still shows deprecated signatures.
- New tests must be independent — PHPUnit runs in random order with `failOnWarning`/`failOnRisky`.
- Feature tests use `describe()` groups, `Http::fake([literal-URL => Http::response(...)])`, and `smartbill()`.

## Pull requests

1. Make sure `composer test`, `composer test:type-coverage`, and `composer lint` all pass.
2. Update [CHANGELOG.md](../CHANGELOG.md) under **Unreleased** when relevant.
