# redirection.io Symfony Bundle

redirection.io Symfony Bundle works in combination with [redirection.io](https://redirection.io).

If you don't know what is redirection.io, take the time to make a quick tour on the website.

Before using it, you need:
- a redirection.io account
- a configured redirection.io agent on your server

You don't have an account ? Please create one [here](https://redirection.io).
You don't have an installed and configured agent ? Follow the [installation guide](https://redirection.io).

Drop us an email to `coucou@redirection.io` if you need help or have any question.

## Installation

```bash
# enable contrib recipes repository
$ composer config extra.symfony.allow-contrib true

# require the bundle
$ composer require redirectionio/symfony-bundle
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
