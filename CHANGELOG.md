# Rinvex Categories Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v3.0.3] - 2019-12-18
- Fix `migrate:reset` args as it doesn't accept --step
- Create event classes and map them in the model

## [v3.0.2] - 2019-09-24
- Add missing laravel/helpers composer package

## [v3.0.1] - 2019-09-23
- Fix outdated package version

## [v3.0.0] - 2019-09-23
- Upgrade to Laravel v6 and update dependencies

## [v2.1.1] - 2019-06-03
- Enforce latest composer package versions

## [v2.1.0] - 2019-06-02
- Update composer deps
- Drop PHP 7.1 travis test
- Refactor migrations and artisan commands, and tweak service provider publishes functionality

## [v2.0.0] - 2019-03-03
- Rename environment variable QUEUE_DRIVER to QUEUE_CONNECTION
- Require PHP 7.2 & Laravel 5.8
- Apply PHPUnit 8 updates
- Tweak and simplify FormRequest validations

## [v1.0.1] - 2018-12-22
- Update composer dependencies
- Add PHP 7.3 support to travis
- Fix MySQL / PostgreSQL json column compatibility

## [v1.0.0] - 2018-10-01
- Enforce Consistency
- Support Laravel 5.7+
- Rename package to rinvex/laravel-categories

## [v0.0.5] - 2018-09-22
- Update travis php versions
- Drop StyleCI multi-language support (paid feature now!)
- Update composer dependencies
- Prepare and tweak testing configuration
- Update StyleCI options
- Update PHPUnit options
- Add category model factory
- Update PHPUnit options

## [v0.0.4] - 2018-02-18
- Update supplementary files
- Update composer dependencies
- Add PublishCommand to artisan
- Move slug auto generation to the custom HasSlug trait
- Add Rollback Console Command
- Add PHPUnitPrettyResultPrinter
- Typehint method returns
- Drop useless model contracts (models already swappable through IoC)
- Add Laravel v5.6 support
- Simplify IoC binding
- Add force option to artisan commands
- Drop Laravel 5.5 support

## [v0.0.3] - 2017-09-09
- Fix many issues and apply many enhancements
- Rename package rinvex/laravel-categories from rinvex/categorizable

## [v0.0.2] - 2017-06-29
- Enforce consistency
- Add Laravel 5.5 support
- Update validation rules
- Replace hardcoded table names
- Tweak model event registration
- Fix wrong slug generation method order
- Enforce more secure approach using model fillable instead of guarded

## v0.0.1 - 2017-04-08
- Rename package to "rinvex/categorizable" from "rinvex/category" based on 916d250

[v3.0.3]: https://github.com/rinvex/laravel-categories/compare/v3.0.2...v3.0.3
[v3.0.2]: https://github.com/rinvex/laravel-categories/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/rinvex/laravel-categories/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/rinvex/laravel-categories/compare/v2.1.1...v3.0.0
[v2.1.1]: https://github.com/rinvex/laravel-categories/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/laravel-categories/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/rinvex/laravel-categories/compare/v1.0.1...v2.0.0
[v1.0.1]: https://github.com/rinvex/laravel-categories/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/laravel-categories/compare/v0.0.5...v1.0.0
[v0.0.5]: https://github.com/rinvex/laravel-categories/compare/v0.0.4...v0.0.5
[v0.0.4]: https://github.com/rinvex/laravel-categories/compare/v0.0.3...v0.0.4
[v0.0.3]: https://github.com/rinvex/laravel-categories/compare/v0.0.2...v0.0.3
[v0.0.2]: https://github.com/rinvex/laravel-categories/compare/v0.0.1...v0.0.2
