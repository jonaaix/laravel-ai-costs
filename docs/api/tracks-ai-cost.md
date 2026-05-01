# TracksAiCost

```php
namespace Aaix\LaravelAiCosts\Concerns;

trait TracksAiCost
```

Drop-in replacement for `Laravel\Ai\Promptable` that tracks an [`AiCostResult`](/api/cost-result) on each `prompt()` call. Internally it imports `Promptable` and renames the original `prompt()` to a private `promptWithoutTracking()`.

## `prompt()`

```php
public function prompt(
    string $prompt,
    array $attachments = [],
    \Laravel\Ai\Enums\Lab|array|string|null $provider = null,
    ?string $model = null,
    ?int $timeout = null,
): \Laravel\Ai\Responses\AgentResponse
```

Same signature as `Promptable::prompt()`. Forwards to the underlying implementation, then appends `AiCostCalculator::fromResponse($response)` to the internal cost buffer.

## `lastCost()`

```php
public function lastCost(): ?AiCostResult
```

Returns the most recent `AiCostResult`, or `null` if no prompt has been issued.

## `costs()`

```php
/** @return AiCostResult[] */
public function costs(): array
```

Returns every `AiCostResult` recorded since instantiation (or the last `resetCosts()`).

## `totalCostUsd()`

```php
public function totalCostUsd(): float
```

Sum of `totalCostUsd` across the buffer.

## `resetCosts()`

```php
public function resetCosts(): void
```

Clears the internal buffer.

## State scope

Costs are stored on the agent **instance** (`protected array $aiCosts`), so they live as long as the agent does. If you re-instantiate the agent per request (the common Laravel pattern), each request starts with an empty buffer.
