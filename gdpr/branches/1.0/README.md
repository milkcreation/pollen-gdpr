# Cookie Law Component

[![Latest Version](https://img.shields.io/badge/release-1.0.0-blue?style=for-the-badge)](https://www.presstify.com/pollen-solutions/cookie-law/)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE.md)

**Cookie Law** allow manage the privacy policy of apps.

## Installation

```bash
composer require pollen-solutions/cookie-law
```

## Pollen Framework Setup

### Declaration

```php
// config/app.php
return [
      //...
      'providers' => [
          //...
          \Pollen\CookieLaw\CookieLawServiceProvider::class,
          //...
      ];
      // ...
];
```

### Configuration

```php
// config/theme-suite.php
// @see /vendor/pollen-solutions/cookie-law/config/cookie-law.php
return [
      //...

      // ...
];
```
