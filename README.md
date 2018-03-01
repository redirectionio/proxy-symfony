# RedirectionIO Proxy for Symfony

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Build Status](https://img.shields.io/travis/redirectionio/proxy-symfony/master.svg)](https://travis-ci.org/redirectionio/proxy-symfony)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/redirectionio/proxy-symfony.svg)](https://scrutinizer-ci.com/g/redirectionio/proxy-symfony)
[![Quality Score](https://img.shields.io/scrutinizer/g/redirectionio/proxy-symfony.svg)](https://scrutinizer-ci.com/g/redirectionio/proxy-symfony)

[![Email](https://img.shields.io/badge/email-support@redirection.io-blue.svg)](mailto:support@redirection.io)

redirection.io Symfony Proxy works in combination with [redirection.io](https://redirection.io).

If you don't know what is redirection.io, take the time to make a quick tour on the website.

Before using it, you need:
- a redirection.io account
- a configured redirection.io agent on your server

You don't have an account ? Please contact us [here](https://redirection.io/contact-us).
You don't have an installed and configured agent ? Follow the [installation guide](https://redirection.io/documentation/developer-documentation/getting-started-installing-the-agent).

Drop us an email to `support@redirection.io` if you need help or have any question.

## Installation

```bash
# enable contrib recipes repository
$ composer config extra.symfony.allow-contrib true

# require the bundle
$ composer require redirectionio/proxy-symfony
```

## Configuration

### Configuration file location

- Symfony framework-standard-edition: `app/config/config.yml`
- Symfony Flex: `config/packages/redirectionio.yaml`

### Full Configuration Example

```yaml
# redirection.io Configuration
redirectionio:
    connections:
        real_agent:
            host: "192.168.64.2"
            port: 20301
        fake_agent:
            host: "localhost"
            port: 3100
        # ...
```
