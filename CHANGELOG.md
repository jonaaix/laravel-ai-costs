# Changelog

All notable changes to `laravel-ai-costs` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-04-11

### Added
- `AiCostCalculator` with `fromResponse()`, `fromUsage()`, and `fromTokens()` methods
- `AiCostResult` readonly DTO with USD and cents accessors
- `TracksAiCost` trait for Laravel AI agents
- `AiProviderEnum` with Gemini, OpenAI, Anthropic, Mistral, DeepSeek, Groq
- Automatic provider detection from model name
- Wildcard/prefix matching for model pricing lookup
- Publishable config with pricing for 30+ models across 6 providers
- Support for Laravel 11 and 12
