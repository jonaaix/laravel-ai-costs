# Direct calculator

`AiCostCalculator` is the static service that powers the [trait](/guide/trait). Use it directly when you don't have an agent instance, or when you want to compute a cost from raw token counts.

All three entry points return an [`AiCostResult`](/api/cost-result) DTO.

## From a `laravel/ai` response

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

$response = $agent->prompt('Hello');

$cost = AiCostCalculator::fromResponse($response);
```

The model and provider are read from `$response->meta`, and the token counts come from `$response->usage`.

## From a `laravel/ai` Usage object

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

$cost = AiCostCalculator::fromUsage($response->usage, 'claude-sonnet-4-6');

// Or pin the provider explicitly
$cost = AiCostCalculator::fromUsage($response->usage, 'claude-sonnet-4-6', 'anthropic');
```

Useful when you've stored a `Usage` object separately from the rest of the response.

## From raw token counts

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

$cost = AiCostCalculator::fromTokens(
    inputTokens: 10_000,
    outputTokens: 500,
    model: 'deepseek-chat',
);
```

Use this for backfilling historical data, building cost estimates, or unit tests. If `$provider` is omitted it is auto-detected from the model name.

## Provider auto-detection

| Model prefix | Detected provider |
| --- | --- |
| `gemini*` | `gemini` |
| `gpt*` or `o\d…` (e.g. `o1`, `o3`) | `openai` |
| `claude*` | `anthropic` |
| `mistral*`, `codestral*`, `magistral*` | `mistral` |
| `deepseek*` | `deepseek` |
| `llama*`, `groq*` | `groq` |
| anything else | `unknown` |

If the model doesn't match any prefix, pass `$provider` explicitly so LiteLLM can resolve a `provider/model` pricing key.
