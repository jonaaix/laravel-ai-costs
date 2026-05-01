# Getting started

**Laravel AI Costs** provides a zero-config way to calculate API costs for [Laravel AI](https://laravel.com/docs/ai-sdk) agent responses. Pricing for **2,600+ models** is resolved automatically from the [LiteLLM](https://github.com/BerriAI/litellm) community pricing database (cached daily). Pass in a response, usage object, or raw token counts — get back a clean DTO with USD costs.

## Why this package?

`laravel/ai` gives you token counts in the response metadata, but not pricing. Pricing changes constantly across providers, and maintaining a hand-rolled price list is a chore. This package leans on the LiteLLM community database for the heavy lifting and lets you override anything that needs pinning.

## Quick example

```php
use Aaix\LaravelAiCosts\Services\AiCostCalculator;

$response = $agent->prompt('Summarise this document.');

$cost = AiCostCalculator::fromResponse($response);

$cost->totalCostUsd;   // 0.000345
$cost->inputTokens;    // 1024
$cost->outputTokens;   // 256
$cost->model;          // "claude-sonnet-4-6"
$cost->provider;       // "anthropic"
```

## Next steps

- [Install the package](/guide/installation)
- [Track costs automatically with the trait](/guide/trait)
- [Use the calculator directly](/guide/calculator)
- [Understand pricing resolution](/guide/pricing-resolution)
