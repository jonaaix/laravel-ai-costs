<h1 align="center">Laravel AI Costs</h1>

<p align="center">
Cost tracking for <a href="https://laravel.com/docs/ai-sdk">Laravel AI</a> agents. Calculates API costs from usage metadata across providers.
</p>

<p align="center">
  <a href="https://packagist.org/packages/aaix/laravel-ai-costs"><img src="https://img.shields.io/packagist/v/aaix/laravel-ai-costs.svg?style=flat-square" alt="Latest Version on Packagist"></a>
  <a href="https://packagist.org/packages/aaix/laravel-ai-costs"><img src="https://img.shields.io/packagist/dt/aaix/laravel-ai-costs.svg?style=flat-square" alt="Total Downloads"></a>
  <a href="https://github.com/aaix/laravel-ai-costs/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/aaix/laravel-ai-costs.svg?style=flat-square" alt="License"></a>
</p>

---

**Laravel AI Costs** provides a zero-config way to calculate API costs for [Laravel AI](https://laravel.com/docs/ai-sdk) agent responses. Pricing for **2,600+ models** is resolved automatically from the [LiteLLM](https://github.com/BerriAI/litellm) community pricing database (cached daily). Pass in a response, usage object, or raw token counts — get back a clean DTO with USD costs.

## How Pricing Works

Pricing is resolved in order:

1. **Local config overrides** — your published `ai-costs.php` (if any)
2. **LiteLLM database** — [2,600+ models](https://github.com/BerriAI/litellm/blob/main/model_prices_and_context_window.json), cached once daily
3. **Exception** — if neither source knows the model

## Requirements

- PHP 8.2+
- Laravel 11+ or 12+
- [laravel/ai](https://github.com/laravel/ai) ^0

## Installation

```bash
composer require aaix/laravel-ai-costs
```

Optionally publish the config to customize model pricing:

```bash
php artisan vendor:publish --tag=ai-costs-config
```

## Usage

### With the Trait (recommended)

Replace `Promptable` with `TracksAiCost` on your agent — it wraps `prompt()` and tracks costs automatically:

```php
use Aaix\LaravelAiCosts\Concerns\TracksAiCost;
use Laravel\Ai\Contracts\Agent;

class MyAgent implements Agent
{
    use TracksAiCost; // replaces Promptable

    // ...
}
```

Every `prompt()` call is tracked transparently:

```php
$agent = MyAgent::make();
$agent->prompt('Analyze this data...');
$agent->prompt('Summarize the results...');

$agent->lastCost();       // AiCostResult for the last prompt
$agent->lastCost()->totalCostUsd;  // 0.000345
$agent->costs();          // array of all AiCostResult objects
$agent->totalCostUsd();   // sum of all prompts
$agent->resetCosts();     // clear tracked costs
```

### Direct Calculator

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

// From a laravel/ai response (model & provider auto-resolved)
$cost = AiCostCalculator::fromResponse($response);

// From a laravel/ai Usage object
$cost = AiCostCalculator::fromUsage($usage, 'claude-sonnet-4-6');

// From raw token counts
$cost = AiCostCalculator::fromTokens(10000, 500, 'deepseek-chat');
```

### AiCostResult DTO

All methods return an `AiCostResult` readonly DTO:

| Property | Type | Description |
|----------|------|-------------|
| `inputCostUsd` | `float` | Input token cost in USD |
| `outputCostUsd` | `float` | Output token cost in USD |
| `totalCostUsd` | `float` | Combined cost in USD |
| `inputTokens` | `int` | Number of input tokens |
| `outputTokens` | `int` | Number of output tokens |
| `model` | `string` | Model identifier |
| `provider` | `string` | Auto-detected or explicit provider |
| `totalCostCents()` | `float` | Combined cost in cents |

## Configuration

Out of the box, no configuration is needed — pricing comes from LiteLLM automatically.

To override pricing for specific models, publish the config and add entries:

```bash
php artisan vendor:publish --tag=ai-costs-config
```

```php
// config/ai-costs.php
'models' => [
    // Local overrides take precedence over LiteLLM
    'my-custom-model' => ['input' => 1.00, 'output' => 3.00],
    'gpt-4o'          => ['input' => 2.50, 'output' => 10.00], // pin a specific price
],
```

Model names have dots removed for config key compatibility (e.g. `gpt-4.1` becomes `gpt-41`). The calculator handles this normalization automatically. Prefix matching is supported — `my-custom*` matches `my-custom-model`, `my-custom-v2`, etc.

### Cache

LiteLLM pricing is cached for 24 hours by default. You can adjust this via environment variables:

```env
AI_COSTS_CACHE_TTL=86400
```

To manually refresh:

```php
use Aaix\LaravelAiCosts\Support\LitellmPricingProvider;

LitellmPricingProvider::clearCache();
```

## Testing

```bash
php artisan test --filter=AiCostCalculator
```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
