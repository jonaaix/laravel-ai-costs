# Configuration

Out of the box, no configuration is needed — pricing comes from LiteLLM automatically. Publish the config only if you want to override prices or tune the cache:

```bash
php artisan vendor:publish --tag=ai-costs-config
```

This creates `config/ai-costs.php`:

```php
return [

    'litellm' => [
        'url' => env(
            'AI_COSTS_LITELLM_URL',
            'https://raw.githubusercontent.com/BerriAI/litellm/main/model_prices_and_context_window.json',
        ),
        'cache_ttl' => env('AI_COSTS_CACHE_TTL', 86400),
    ],

    'models' => [
        // Local overrides — these take precedence over LiteLLM.
        // Format: 'model-name' => ['input' => price_per_1M, 'output' => price_per_1M],
    ],

];
```

## Local pricing overrides

Add entries under `models` to pin a price or define a custom model that LiteLLM doesn't know about:

```php
'models' => [
    'my-custom-model' => ['input' => 1.00, 'output' => 3.00],
    'gpt-4o'          => ['input' => 2.50, 'output' => 10.00],
],
```

Prices are **USD per 1M tokens**, matching the LiteLLM convention.

### Key normalisation

Model names have **dots removed** for config-key compatibility:

| Model | Config key |
| --- | --- |
| `gpt-4.1` | `gpt-41` |
| `claude-sonnet-4-6` | `claude-sonnet-4-6` |
| `gemini-1.5-pro` | `gemini-15-pro` |

The calculator handles this normalisation automatically — you don't need to think about it at the call site.

### Wildcard / prefix matching

A trailing `*` makes the entry match any model whose normalised key starts with the prefix:

```php
'models' => [
    'my-custom*' => ['input' => 1.00, 'output' => 3.00],
],
```

This matches `my-custom-model`, `my-custom-v2`, and so on.

## LiteLLM cache

LiteLLM pricing is cached for 24 hours by default. Tune via env:

```ini
AI_COSTS_CACHE_TTL=86400
```

Or change the source URL (e.g. to a fork or mirror):

```ini
AI_COSTS_LITELLM_URL=https://example.com/my-mirror.json
```

To clear the cache manually (e.g. after pushing a fix to the LiteLLM repo):

```php
use Aaix\LaravelAiCosts\Support\LitellmPricingProvider;

LitellmPricingProvider::clearCache();
```
