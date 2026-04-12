<?php

declare(strict_types=1);

namespace Aaix\LaravelAiCosts\Services;

use Aaix\LaravelAiCosts\DTO\AiCostResult;
use Aaix\LaravelAiCosts\Support\LitellmPricingProvider;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\Data\Usage;

final class AiCostCalculator
{
   public static function fromResponse(AgentResponse $response): AiCostResult
   {
      return self::fromTokens(
         $response->usage->promptTokens,
         $response->usage->completionTokens,
         $response->meta->model,
         $response->meta->provider,
      );
   }

   public static function fromUsage(Usage $usage, string $model, ?string $provider = null): AiCostResult
   {
      return self::fromTokens($usage->promptTokens, $usage->completionTokens, $model, $provider);
   }

   public static function fromTokens(int $inputTokens, int $outputTokens, string $model, ?string $provider = null): AiCostResult
   {
      $provider = $provider ?? self::detectProvider($model);
      $pricing = self::getPricing($model, $provider);

      $inputCost = ($inputTokens / 1_000_000) * $pricing['input'];
      $outputCost = ($outputTokens / 1_000_000) * $pricing['output'];

      return new AiCostResult(
         inputCostUsd: $inputCost,
         outputCostUsd: $outputCost,
         totalCostUsd: $inputCost + $outputCost,
         inputTokens: $inputTokens,
         outputTokens: $outputTokens,
         model: $model,
         provider: $provider,
      );
   }

   /**
    * @return array{input: float, output: float}
    */
   private static function getPricing(string $model, string $provider): array
   {
      $configKey = self::modelToConfigKey($model);

      // ----- Step 1: Local config override -----
      $localPricing = config("ai-costs.models.{$configKey}");
      if ($localPricing) {
         return $localPricing;
      }

      foreach (config('ai-costs.models', []) as $key => $prices) {
         if (str_starts_with($configKey, rtrim($key, '*'))) {
            return $prices;
         }
      }

      // ----- Step 2: LiteLLM — provider-specific key first, then bare model -----
      $litellmPricing = LitellmPricingProvider::getPricing("{$provider}/{$model}")
         ?? LitellmPricingProvider::getPricing($model);

      if ($litellmPricing) {
         return $litellmPricing;
      }

      throw new \InvalidArgumentException(
         "No pricing found for model [{$model}]. "
         . 'Add it to your ai-costs.php config or contribute the model at https://github.com/BerriAI/litellm'
      );
   }

   private static function modelToConfigKey(string $model): string
   {
      return str_replace(['.', '-'], ['', '-'], $model);
   }

   private static function detectProvider(string $model): string
   {
      return match (true) {
         str_starts_with($model, 'gemini') => 'gemini',
         str_starts_with($model, 'gpt') || preg_match('/^o\d/', $model) === 1 => 'openai',
         str_starts_with($model, 'claude') => 'anthropic',
         str_starts_with($model, 'mistral') || str_starts_with($model, 'codestral') || str_starts_with($model, 'magistral') => 'mistral',
         str_starts_with($model, 'deepseek') => 'deepseek',
         str_starts_with($model, 'llama') || str_starts_with($model, 'groq') => 'groq',
         default => 'unknown',
      };
   }
}
