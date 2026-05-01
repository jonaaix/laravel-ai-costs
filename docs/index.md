---
layout: home

hero:
  name: Laravel AI Costs
  text: Cost tracking for Laravel AI agents.
  tagline: Calculate API costs from usage metadata across 2,600+ models — zero config required.
  image:
    src: /logo.webp
    alt: Laravel AI Costs
  actions:
    - theme: brand
      text: Get started
      link: /guide/getting-started
    - theme: alt
      text: View on GitHub
      link: https://github.com/aaix/laravel-ai-costs

features:
  - title: Zero-config pricing
    details: Pricing for 2,600+ models is resolved automatically from the LiteLLM community database, cached daily.
  - title: Drop-in trait
    details: Replace Promptable with TracksAiCost on your agent — every prompt() call is tracked transparently.
  - title: Works everywhere laravel/ai works
    details: Anthropic, OpenAI, Gemini, Mistral, DeepSeek, Groq — provider auto-detection from the model name.
  - title: Local overrides
    details: Pin custom prices or override LiteLLM via the published config. Wildcard prefix matching included.
  - title: Readonly DTO
    details: Get back a clean AiCostResult with input/output USD costs, token counts, model, and provider.
  - title: Per-agent totals
    details: Roll up costs across an agent's prompt() calls with totalCostUsd() and costs() — ready for budgets, logs, and dashboards.
---
