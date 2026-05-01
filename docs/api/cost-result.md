# AiCostResult

```php
namespace Aaix\LaravelAiCosts\DTO;

final readonly class AiCostResult
```

Immutable value object returned by every entry point on [`AiCostCalculator`](/api/calculator).

## Properties

| Property | Type | Description |
| --- | --- | --- |
| `inputCostUsd` | `float` | Input token cost in USD |
| `outputCostUsd` | `float` | Output token cost in USD |
| `totalCostUsd` | `float` | Combined cost in USD (`inputCostUsd + outputCostUsd`) |
| `inputTokens` | `int` | Number of input tokens |
| `outputTokens` | `int` | Number of output tokens |
| `model` | `string` | Model identifier as reported by the provider |
| `provider` | `string` | Provider — auto-detected or supplied explicitly |

## Methods

### `totalCostCents()`

```php
public function totalCostCents(): float
```

Returns `totalCostUsd * 100`. Convenient for storing as integers in cents columns:

```php
$cents = (int) round($cost->totalCostCents());
```

## Example

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

$cost = AiCostCalculator::fromTokens(1000, 500, 'claude-sonnet-4-6');

$cost->inputCostUsd;    // 0.003
$cost->outputCostUsd;   // 0.0075
$cost->totalCostUsd;    // 0.0105
$cost->totalCostCents(); // 1.05
$cost->model;           // "claude-sonnet-4-6"
$cost->provider;        // "anthropic"
```

::: tip
The class is `final readonly` — you cannot mutate it after construction. To change a value, build a new instance via the calculator.
:::
