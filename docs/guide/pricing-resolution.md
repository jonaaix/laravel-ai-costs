# Pricing resolution

Whenever the calculator needs a price for a `(model, provider)` pair, it walks a deterministic resolution chain. Understanding the chain helps you decide where to put overrides and how to debug missing prices.

## Resolution order

```
┌────────────────────────────────────────────────────────────┐
│ 1. Local config — exact match on normalised key             │
│    config('ai-costs.models.{normalised-model}')             │
└──────────────────────────┬──────────────────────────────────┘
                           │ miss
                           ▼
┌────────────────────────────────────────────────────────────┐
│ 2. Local config — wildcard prefix match                     │
│    keys ending in '*' against the normalised key            │
└──────────────────────────┬──────────────────────────────────┘
                           │ miss
                           ▼
┌────────────────────────────────────────────────────────────┐
│ 3. LiteLLM — provider-scoped key first                      │
│    e.g. "anthropic/claude-sonnet-4-6"                       │
└──────────────────────────┬──────────────────────────────────┘
                           │ miss
                           ▼
┌────────────────────────────────────────────────────────────┐
│ 4. LiteLLM — bare model key                                 │
│    e.g. "claude-sonnet-4-6"                                 │
└──────────────────────────┬──────────────────────────────────┘
                           │ miss
                           ▼
       throw InvalidArgumentException with hint
```

## Why this order?

- **Local config wins** so you can pin a price without forking LiteLLM.
- **Provider-scoped LiteLLM keys win over bare keys** because the same model name can exist under multiple providers (e.g. `claude-3.5-sonnet` on Anthropic vs. via a proxy).
- **Wildcards live next to exact matches** so a single `'my-custom*'` line covers a family of internal models.

## When resolution fails

If no source returns a price, the calculator throws:

```
InvalidArgumentException: No pricing found for model [my-model].
Add it to your ai-costs.php config or contribute the model at
https://github.com/BerriAI/litellm
```

Common fixes:

1. **Typo in the model name** — check what the provider returned in `$response->meta->model`.
2. **Provider not detected** — pass `$provider` explicitly so LiteLLM tries `provider/model` first.
3. **New model not yet in LiteLLM** — pin it locally, or open a PR upstream.
4. **Stale cache** — call `LitellmPricingProvider::clearCache()` if a fix has landed upstream within the last 24h.

## Pricing units

All prices are **USD per 1M tokens**. LiteLLM stores per-token costs internally; the package multiplies by `1_000_000` when building its index, so both LiteLLM and your local config speak the same unit at the call site.
