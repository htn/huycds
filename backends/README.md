# Huycds Backends
[![Latest Stable Version](https://poser.pugx.org/Huycds/Backends/v/stable?format=flat-square)](https://packagist.org/packages/Huycds/Backends)
[![Laravel 5.7](https://img.shields.io/badge/Laravel-5.7-orange.svg?style=flat-square)](https://laravel.com)
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
[![Source](http://img.shields.io/badge/source-Huycds/Backends-blue.svg?style=flat-square)](https://github.com/Huycds/Backends)
[![Total Downloads](https://img.shields.io/packagist/dt/Huycds/Backends.svg?style=flat-square)](https://packagist.org/packages/Huycds/Backends)

Huycds Backends is a simple package to allow the means to separate your Laravel 5.7+ application out into Backends. Each module is completely self-contained allowing the ability to simply drop a module in for use.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code.

## Documentation
You will find user friendly and updated documentation in the wiki here: [Huycds Backends Wiki](https://github.com/Huycds/Backends/wiki)

## Quick Installation
Begin by installing the package through Composer.

```
composer require Huycds/Backends
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider

```php
Huycds\Backends\BackendsServiceProvider::class,
```

#### Facade

```php
'Module' => Huycds\Backends\Facades\Module::class,
```

And that's it! With your coffee in reach, start building out some awesome Backends!

## Tests

Run the tests with:

``` bash
vendor/bin/phpunit
```

## Credits

- [Shea Lewis](https://github.com/kaidesu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.