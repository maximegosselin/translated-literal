# TranslatedLiteral

[![Latest Version](https://img.shields.io/github/release/maximegosselin/translated-literal.svg)](https://github.com/maximegosselin/translated-literal/releases)
[![Build Status](https://img.shields.io/travis/maximegosselin/translated-literal.svg)](https://travis-ci.org/maximegosselin/translated-literal)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

*TranslatedLiteral* is used to represent translated strings in JSON.

The library is framework-agnostic and does not have any dependencies.


## System Requirements

PHP 7.0 or later.


## Install

Install using [Composer](https://getcomposer.org/):

```
$ composer require maximegosselin/translated-literal
```

*TranslatedLiteral* is registered under the `MaximeGosselin\TranslatedLiteral` namespace.


## Usage

Instantiate a `Literal` object by defining the default locale and translation.

A `Literal` instance is **immutable**. A copy can be created using `withLocale` and `withTranslation` functions.

```php
$literal = new Literal('fr-ca', 'Bonjour le monde');
 
$literal = $literal->withTranslation('en-us', 'Hello world')->withLocale('en-us');
 
echo $literal;
```

This would output:

```
{
    "locale": "en_US",
    "content": {
        "fr_CA": "Bonjour le monde",
        "en_US": "Hello world"
    }
}
```


## Tests

Run the following command from the project folder.
```
$ vendor/bin/phpunit
```


## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
