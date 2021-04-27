# RedirectionIO Proxy for Symfony

**[DEPRECATED]**: This library is deprecated and will not be maintained anymore.
It does not work with the [current version of the redirection.io
agent](https://redirection.io/documentation/developer-documentation/installation-of-the-agent),
but only with the legacy 1.x branch. We advise you to migrate and use [one of
the recommended
integrations](https://redirection.io/documentation/developer-documentation/available-integrations#recommended-integrations).

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

The following command dump your configuration:

```bash
bin/console  debug:config redirection_io
```

## Do not process some requests

You might want to exclude some requests, because you know they will never have
some redirections on it, or you don't want log them. It's the case for debug
routes for example: `/_wdt`, `/_profiler`, and `/_error`. That's why we ignore
theses request by default thanks to their URL prefixes.

### Ignore requests by URL prefix

You can add more prefixes to the configuration:

```yaml
redirection_io:
    excluded_prefixes:
        - /admin
        - /api
        # ...
```

### Ignore requests by Host

```yaml
redirection_io:
    excluded_hosts:
        - api.example.com
        - admin.example.com
        # - 127.0.0.1
        # ...
```

### Ignore other requests

If you need to do custom code to ignore some requests, you have to implement
`RedirectionIO\Client\ProxySymfony\CircuitBreaker\CircuitBreakerInterface`.

If you are not using the default configuration of Symfony (`autowire=true` and
`autoconfigure=true`), you will have to register the service and tag it with
`redirectionio.circuit_breaker`.
