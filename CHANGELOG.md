# Changelog

All notable changes to `laravel-ai-costs` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2026-07-24

### Fixed
- Compatibility with `laravel/ai` v0.10: `TracksAiCost::prompt()` now accepts `Decisions|string` to match the updated `Promptable` trait and `Agent` contract (human-in-the-loop tool approvals)
- Responses without model metadata (e.g. runs paused for tool approval) no longer break cost tracking — they are skipped instead

### Changed
- Require `laravel/ai` `^0.10` (the `Decisions` class does not exist in earlier versions)

## [1.0.1] - 2026-05-01

### Added
- VitePress documentation site under `docs/` with guide and API reference
- Project logo (`resources/laravel-ai-costs.webp`) and README header
- Custom VitePress theme matching the logo palette (gold accent, teal→purple gradient)
- Laravel Boost skill at `resources/boost/skills/laravel-ai-costs-development/SKILL.md`

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
