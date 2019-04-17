# RedirectionIO Proxy for Symfony

Symfony Proxy works in combination with [redirection.io](https://redirection.io).

If you don't know what is redirection.io, take the time to make a quick tour on
the website.

Before using it, you need:
- a redirection.io account. If you don't have an account, please [contact us here](https://redirection.io/contact-us).
- a configured redirection.io agent on your server. Please follow the [installation guide](https://redirection.io/documentation/developer-documentation/installation-of-the-agent).

Drop us an email to `support@redirection.io` if you need help or have any questions.

## Installation

```bash
composer require redirectionio/proxy-symfony
```

## Configuration

### Configuration file location

- Symfony framework-standard-edition: `app/config/config.yml`
- Symfony Flex: `config/packages/redirectionio.yaml`

### Configuration Example

```yaml
# redirection.io Configuration
redirection_io:
    project_key: 'foo:bar'
    connections:
        agent_tcp: 'tcp://127.0.0.1:20301'
        agent_unix: 'unix:///var/run/redirectionio_agent.sock'
```

### Configuration reference

The following command dump the configuration reference:

```bash
bin/console config:dump-reference redirection_io
```

The following command dump your configuration

```bash
bin/console  debug:config redirection_io
```

## Do not process some URLs

You might want to exclude some URLs, because you know there will never have some
redirection on it, and you don't want log on it. It's the case for debug routes
like (`/_wdt`, `/_profiler`, and `/_error`). That's why ignore theses URL prefix
by default.

### Ignore URL by prefix

You can add more prefixes to the configuration:

```yaml
redirection_io:
    excluded_prefixes:
        - /admin
        - /api
        # ...
```

### Ignore other requests

If you need to do custom code to ignore some Request, you have to implement
`RedirectionIO\Client\ProxySymfony\CircuitBreaker\CircuitBreakerInterface`.

If you are not using the default configuration of Symfony (`autowire=true` and
`autoconfigure=true`), you will have to register the service and tag it with
`redirectionio.circuit_breaker`.
