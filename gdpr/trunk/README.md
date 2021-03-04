# Pollen GDPR Component

[![Latest Version](https://img.shields.io/badge/release-1.0.0-blue?style=for-the-badge)](https://www.presstify.com/pollen-solutions/cookie-law/)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)
[![PHP Supported Versions](https://img.shields.io/badge/PHP->=7.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/supported-versions.php)

Pollen **GDPR** Component provides tools to manage the privacy policy of applications.

## Installation

```bash
composer require pollen-solutions/gdpr
```

## Pollen Framework Setup

### Declaration

```php
// config/app.php
use Pollen\Gdpr\GdprServiceProvider;

return [
      //...
      'providers' => [
          //...
          GdprServiceProvider::class,
          //...
      ]
      // ...
];
```

### Configuration

```php
// config/gdpr.php
// @see /vendor/pollen-solutions/gdpr/config/gdpr.php
return [
      //...

      // ...
];
```
