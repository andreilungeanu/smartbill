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
composer analyse             # PHPStan level 5 (src + tests)
composer lint                # Pint + PHPStan
```

Run a single test:

```bash
vendor/bin/pest --filter='returns the invoice number'
```

## Guidelines

- Target **PHP 8.2** in `src/` — avoid 8.3+ syntax. Tests are Pest 5 and run on PHP 8.4+.
- Every endpoint method must follow the pattern documented in [CLAUDE.md](../CLAUDE.md): JSON bodies via `->post(...)->throw(...)->json()` / `->body()`, query-string PUT/DELETE via `sendQuery()`.
- Never use the `Http` facade inside endpoints — resolve the injected `PendingRequest` from [BaseEndpoint](../src/Endpoints/BaseEndpoint.php). Arch tests enforce this.
- Consult [DOCUMENTATION.md](../DOCUMENTATION.md) (upstream API reference) before adding or changing endpoint signatures.
- New tests must be independent — PHPUnit runs in random order with `failOnWarning`/`failOnRisky`.
- Feature tests use `describe()` groups, `Http::fake([literal-URL => Http::response(...)])`, and `smartbill()`.

## Pull requests

1. Make sure `composer test`, `composer test:type-coverage`, and `composer lint` all pass.
2. Update [CHANGELOG.md](../CHANGELOG.md) under **Unreleased** when relevant.
