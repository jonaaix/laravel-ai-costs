---
name: laravel-ai-costs-development
description: Track and calculate API costs for Laravel AI agents using aaix/laravel-ai-costs.
---

# Laravel AI Costs Development

## When to use this skill

Use this skill when the project depends on `aaix/laravel-ai-costs` and the user wants to track or calculate costs for `laravel/ai` agent calls.

## Features

- **`TracksAiCost` trait** — replaces `Laravel\Ai\Promptable` on an agent and records an `AiCostResult` per `prompt()` call. Do **not** also `use Promptable` on the same class — the trait imports it internally and that would cause a trait conflict. Example usage:

```php
use Aaix\LaravelAiCosts\Concerns\TracksAiCost;

class SupportAgent implements \Laravel\Ai\Contracts\Agent
{
    use TracksAiCost;
}

$agent = SupportAgent::make();
$agent->prompt('...');
$agent->lastCost();     // ?AiCostResult
$agent->totalCostUsd(); // float across all prompts on this instance
```

- **`AiCostCalculator`** — stateless, three entry points, all return an `AiCostResult`. Example usage:

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

AiCostCalculator::fromResponse($response);
AiCostCalculator::fromUsage($response->usage, 'claude-sonnet-4-6');
AiCostCalculator::fromTokens(10_000, 500, 'deepseek-chat');
```

- **Local pricing overrides** — pin a price in `config/ai-costs.php` under `models`. Prices are **USD per 1M tokens**. Model keys have **dots removed**: `gpt-4.1` → `gpt-41`. Local config wins over LiteLLM.

```php
'models' => [
    'gpt-41' => ['input' => 2.00, 'output' => 8.00],
],
```

- **Missing-price exceptions** — if neither local config nor LiteLLM knows the model, the calculator throws `\InvalidArgumentException`. Fix by pinning the price locally or passing `$provider` explicitly so LiteLLM can try the `provider/model` key.
