<p align="center">
  <a href="https://github.com/aaix/laravel-ai-costs">
    <img src="https://jonaaix.github.io/laravel-ai-costs/logo.webp" alt="Laravel AI Costs Logo" width="200">
  </a>
</p>

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

Zero-config cost calculation for [Laravel AI](https://laravel.com/docs/ai-sdk) responses. Pricing for **2,600+ models** is resolved from the [LiteLLM](https://github.com/BerriAI/litellm) database (cached daily); local overrides take precedence.

```bash
composer require aaix/laravel-ai-costs
```

```php
use Aaix\LaravelAiCosts\Concerns\TracksAiCost;

class MyAgent implements \Laravel\Ai\Contracts\Agent
{
    use TracksAiCost; // replaces Promptable
}

$agent = MyAgent::make();
$agent->prompt('...');

$agent->lastCost()->totalCostUsd; // 0.000345
$agent->totalCostUsd();           // sum across all prompts on this instance
```

## Documentation

Full guide and API reference: **[aaix.github.io/laravel-ai-costs](https://aaix.github.io/laravel-ai-costs/)**

## License

[MIT](LICENSE)
