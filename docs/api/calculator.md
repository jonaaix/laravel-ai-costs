# AiCostCalculator

```php
namespace Aaix\LaravelAiCosts\Services;

final class AiCostCalculator
```

Stateless static service that turns token counts into an [`AiCostResult`](/api/cost-result).

## `fromResponse()`

```php
public static function fromResponse(
    \Laravel\Ai\Responses\AgentResponse $response,
): \Aaix\LaravelAiCosts\DTO\AiCostResult
```

Builds a result from a `laravel/ai` response. Reads:

- `$response->usage->promptTokens`
- `$response->usage->completionTokens`
- `$response->meta->model`
- `$response->meta->provider`

## `fromUsage()`

```php
public static function fromUsage(
    \Laravel\Ai\Responses\Data\Usage $usage,
    string $model,
    ?string $provider = null,
): \Aaix\LaravelAiCosts\DTO\AiCostResult
```

When `$provider` is `null`, it is inferred from the model name. See [provider detection](/guide/calculator#provider-auto-detection).

## `fromTokens()`

```php
public static function fromTokens(
    int $inputTokens,
    int $outputTokens,
    string $model,
    ?string $provider = null,
): \Aaix\LaravelAiCosts\DTO\AiCostResult
```

The lowest-level entry point. Used internally by `fromResponse()` and `fromUsage()`.

## Behaviour

- All three methods return an `AiCostResult` readonly DTO.
- Pricing is resolved in the order described in [Pricing resolution](/guide/pricing-resolution).
- Throws `\InvalidArgumentException` if no price can be found.
- `inputCostUsd = (inputTokens / 1_000_000) * pricing['input']` — same for output.
