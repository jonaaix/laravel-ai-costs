# LitellmPricingProvider

```php
namespace Aaix\LaravelAiCosts\Support;

final class LitellmPricingProvider
```

Fetches and caches the [LiteLLM community pricing database](https://github.com/BerriAI/litellm/blob/main/model_prices_and_context_window.json), then exposes per-model lookups in the package's normalised format.

## `getPricing()`

```php
/** @return array{input: float, output: float}|null */
public static function getPricing(string $model): ?array
```

Returns pricing in **USD per 1M tokens** for the given model key, or `null` if the model is not in the index. The model key can be either bare (`claude-sonnet-4-6`) or provider-scoped (`anthropic/claude-sonnet-4-6`) — the calculator tries the scoped form first.

## `clearCache()`

```php
public static function clearCache(): void
```

Forgets the `ai-costs:litellm-index` cache entry. The next `getPricing()` call will refetch from `config('ai-costs.litellm.url')`.

## Cache behaviour

- Cache key: `ai-costs:litellm-index`
- TTL: `config('ai-costs.litellm.cache_ttl')` seconds (default `86400` / 24h)
- Store: whatever Laravel's default cache store is configured to use
- HTTP timeout: 15 seconds
- On HTTP failure: returns an empty index (the calculator then throws `InvalidArgumentException`)

## Index construction

Entries are skipped when:

- `input_cost_per_token` or `output_cost_per_token` is missing
- both costs are `0` (LiteLLM uses zeros as placeholders for free / unknown models)

Surviving entries are multiplied by `1_000_000` to convert from per-token to per-1M-token, matching the unit used by the `models` config array.
