# RedirectionIO Proxy for Symfony

redirection.io Symfony Proxy works in combination with
[redirection.io](https://redirection.io).

If you don't know what is redirection.io, take the time to make a quick tour on
the website.

Before using it, you need:
- a redirection.io account. If you don't have an account, please [contact us here](https://redirection.io/contact-us).
- a configured redirection.io agent on your server. Please follow the [installation guide](https://redirection.io/documentation/developer-documentation/installation-of-the-agent).

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
redirection_io:
    connections:
        agent_tcp: tcp://127.0.0.1:20301
        agent_unix: unix:///var/run/redirectionio_agent.sock
```
