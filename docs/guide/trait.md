# TracksAiCost trait

The `TracksAiCost` trait is the recommended way to track costs. It replaces `laravel/ai`'s `Promptable` trait on your agent and transparently records an `AiCostResult` for every `prompt()` call.

## Setup

Replace `Promptable` with `TracksAiCost`:

```php
use Aaix\LaravelAiCosts\Concerns\TracksAiCost;
use Laravel\Ai\Contracts\Agent;

class SupportAgent implements Agent
{
    use TracksAiCost; // replaces Promptable

    // your agent definition...
}
```

::: tip
`TracksAiCost` uses `Promptable` internally, so do not also `use Promptable` — that would cause a trait conflict.
:::

## Tracking calls

Every `prompt()` call is tracked transparently:

```php
$agent = SupportAgent::make();

$agent->prompt('Analyze this customer ticket...');
$agent->prompt('Suggest a response.');
```

## Reading costs

```php
// Most recent prompt
$agent->lastCost();              // AiCostResult|null
$agent->lastCost()->totalCostUsd; // 0.000345

// All prompts since the agent was instantiated (or resetCosts())
$agent->costs();                 // AiCostResult[]

// Sum across all tracked prompts
$agent->totalCostUsd();          // float

// Reset the internal buffer
$agent->resetCosts();
```

## When to use the trait vs the calculator

Use the **trait** when you want costs tracked on the agent instance — useful for per-conversation totals, per-request budgets, or logging at the end of a job.

Use the [direct calculator](/guide/calculator) when you only need a one-off calculation, you have raw token counts, or you want to compute costs outside of an agent (e.g. backfilling historical responses).
