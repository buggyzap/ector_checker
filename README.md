# Ector Checker

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ector/checker.svg?style=flat-square)](https://packagist.org/packages/ector/checker)
[![Tests](https://img.shields.io/github/actions/workflow/status/ector/checker/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/buggyzap/ector_checker/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ector/checker.svg?style=flat-square)](https://packagist.org/packages/ector/checker)

Composer dependency to check if a Ector instance is valid.

## Installation

You can install the package via composer:

```bash
composer require ector/ector-checker
```

## Usage


### Setup class as a symfony service

```yaml
# config/admin/services.yml
services:
  _defaults:
    public: true

  ector.checker:
    class: 'Ector\Checker\Checker'
```

### Inject  the Ector/Checker via symfony container in module __construct

```php
if ($this->checker === null && $this->context->controller instanceof AdminController) {
    $this->checker = $this->get("ector.checker");
}
```

### Run the healtcheck in right hook

```php
public function hookActionAdminControllerInitAfter($params)
{
    $controller = $params["controller"];
    $this->checker->healthCheck($controller);
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [DGCAL SRL](https://github.com/buggyzap)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
