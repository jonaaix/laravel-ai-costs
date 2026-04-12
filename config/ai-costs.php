<?php

/**
 * AI model pricing configuration.
 *
 * Pricing is resolved in order:
 * 1. Local overrides in 'models' below (per 1M tokens, USD)
 * 2. LiteLLM community pricing database (cached daily)
 *
 * Model keys have dots removed for config compatibility:
 * e.g. "gpt-4.1" becomes "gpt-41"
 */
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
