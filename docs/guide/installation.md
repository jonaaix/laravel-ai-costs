# Installation

## Requirements

- PHP **8.2+**
- Laravel **11** or **12**
- [`laravel/ai`](https://github.com/laravel/ai) `^0`

## Install via Composer

```bash
composer require aaix/laravel-ai-costs
```

The service provider is auto-discovered via the `extra.laravel.providers` entry in `composer.json` — no manual registration needed.

## Publish the config (optional)

The package works out of the box with no configuration. Publish the config only if you want to pin specific model prices or change the LiteLLM cache TTL:

```bash
php artisan vendor:publish --tag=ai-costs-config
```

This creates `config/ai-costs.php`. See the [Configuration](/guide/configuration) page for available options.

## Verify

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

$cost = AiCostCalculator::fromTokens(
    inputTokens: 1000,
    outputTokens: 500,
    model: 'claude-sonnet-4-6',
);

dump($cost->totalCostUsd);
```

If a price is returned, the package is wired up correctly. If you see a `No pricing found for model [...]` exception, see [Pricing resolution](/guide/pricing-resolution).
