# ChangeLog

## 0.3.1

- Fix compatibly with PHP 7.0

## 0.3.0

- Fix Symfony 4 deprecations
- Allow to deactivate persist flag on client connections

## 0.2.1

- Added `excluded_hosts` configuration option
- Refactor how request are ignored: Introduced a new interface: `CircuitBreakerInterface`
- Fixed support for v0.3 of the SDK

## 0.2.0

- Added support for v0.3 of the SDK

## 0.1.4

- Added `excluded_prefixes` configuration option
- Add target in every log sent, even if redirection does not come from a rule

## 0.1.3

- Added the `ruleId` in the HTTP objects. It will be used to log everything
- Added a way to override the debug mode

## 0.1.2

- Added more debugging features

## 0.1.1

- Added support for 410 HTTP status code

## 0.1.0

- Initial release
