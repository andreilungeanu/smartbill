# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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