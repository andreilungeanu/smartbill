# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.1.0] - 2026-08-22

Development tooling only. No runtime code changed and the supported Laravel range in
`require` is unchanged, so the installed package is identical to 2.0.0.

### Changed
- `orchestra/testbench` now requires `^11.0`. Pest 5 resolves PHPUnit 13 and
  `symfony/process` 8, which the testbench 9 and 10 lines pin to `^7.2`, so the previous
  `^9.0|^10.0|^11.0` could never be satisfied alongside Pest 5
- CI runs Laravel 12 on Pest 4 and Laravel 13 on Pest 5; the suite passes unchanged on
  both. The Laravel 11 row is dropped, its security window having closed in March 2026
- CI floors `orchestra/testbench` at `^10.6` for Laravel 12. Versions 10.2 through 10.5
  call `HandleExceptions::flushState()` against a PHPUnit whose `ErrorHandler::enable()`
  signature changed, which failed the suite on `prefer-lowest`
- `fail-fast` is off, so a single red cell no longer cancels the rest of the matrix
- README, CLAUDE.md and CONTRIBUTING no longer claim Laravel 11 is covered by CI

## [2.0.0] - 2026-08-22

### Breaking
- Endpoint methods now throw when the API answers a 2xx with a populated `errorText`.
  Smartbill reports functional failures that way, so callers that read `errorText` out of
  the returned array must catch `SmartbillApiException` instead
- `SmartbillApiException::report()` is gone, replaced by `context()`. A `report()` that
  returns anything but `false` makes Laravel skip its own reporting, which dropped the
  stack trace and call site from the log entry
- Exception messages are now the cause only: the help markup Smartbill adds for its own
  interface is removed and HTML error pages no longer leak into the message. The
  untouched body stays available through `getResponse()`

### Added
- `SmartbillRequestException` for `invalid_request_error` bodies, exposing `getParam()`,
  `getErrorCode()` and `getErrors()` — the offending field is named exactly, including
  inside lists (`products[0].quantity`)
- `SmartbillRateLimitException`, raised on the 403 that reports the limit as well as on
  429, with `getLimit()`, `getRemaining()` and `getResetAt()`
- `SmartbillApiException::getErrorText()`, `getCooldown()` and `context()`

### Fixed
- Treat a non-zero `status.code` as a failure: `/document/send` reports through that
  envelope and carries no `errorText`, so failures were returned as success
- Return an array when the body is empty or JSON `null`, instead of raising a `TypeError`
- Keep falsy optional query parameters — a `productCode` of `"0"` was silently dropped
- `reverse()` no longer requires `issueDate`; the API treats it as optional
- `getInvoices()` reports an un-invoiced estimate instead of throwing: that state arrives
  as a 2xx with a populated `errorText`
- Accept `int|string` for the payment id and `int|float|string` for the payment value,
  since PHP renders `100.00` as `100` on a query string and Smartbill matches exactly

### Changed
- Raise PHPStan to level 6 and enforce 100% type coverage in CI, which was previously
  claimed but never checked
- Deprecate `invoices()->create()` and `estimates()->create()` in favour of `createV2()`:
  `/invoice` and `/estimate` are absent from Smartbill's OpenAPI spec and answer without
  `documentUrl`, `documentId` and `documentViewUrl`

## [1.4.0] - 2026-08-22

### Fixed
- Send PUT/DELETE parameters as a query string, which is what Smartbill expects, instead
  of a JSON body — `cancel()`, `restore()` and `delete()` never reached the API before
- Repair invalid YAML indentation that stopped GitHub rendering the bug report template
- Correct the documented `getPdf()` failure behaviour: it throws, it does not return an array

### Added
- Annotate the `Smartbill` facade with the endpoint methods, so editors resolve them
- CONTRIBUTING and SECURITY policy

### Changed
- Upgrade the test suite to Pest 5 (`describe` groups, named datasets, PHPStan plugin).
  Pest 5 requires PHP 8.4, so CI now runs 8.4/8.5; `src/` still targets PHP 8.2
- Drop PHPStan suppressions that no longer matched anything
- Refresh `.gitignore` and `.gitattributes`

## [1.3.0] - 2026-04-19

### Added
- Upgrade to Pest v4 with arch tests and type coverage
- Add CLAUDE.md for project documentation
- Add default 30s HTTP timeout (SMARTBILL_TIMEOUT)

### Fixed
- Update method names in documentation for consistency and clarity

### Changed
- Drop unused string branch from SmartbillApiException
- Move Smartbill exception logging into report()
- Bump aglipanci/laravel-pint-action from 2.5 to 2.6
- Bump stefanzweifel/git-auto-commit-action from 6 to 7
- Bump actions/checkout from 4 to 6
- Bump dependabot/fetch-metadata from 2.4.0 to 2.5.0

## [1.2.0] - 2026-04-15

### Added
- Update PHP and Laravel versions in CI configuration and composer.json

### Changed
- Update illuminate/http and illuminate/support versions

### Fixed
- Remove duplicate error message for invalid request data in README

## [1.1.1] - 2025-07-14

### Added
- Refactor endpoint classes to extend BaseEndpoint and remove PendingRequest constructor

### Changed
- Update usage examples in README to reflect dependency injection changes

### Testing
- Add full test coverage for all endpoint methods
- Add unit tests for InvoicesEndpoint and Smartbill classes

## [1.1.0] - 2025-07-14

### Added
- Decouple components via dependency injection

### Changed
- Remove workaround for non-compliant headers

## [1.0.0] - 2025-06-16

Initial release.
