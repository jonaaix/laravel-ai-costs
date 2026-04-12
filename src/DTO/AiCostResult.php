<?php

declare(strict_types=1);

namespace Aaix\LaravelAiCosts\DTO;

final readonly class AiCostResult
{
   public function __construct(
      public float $inputCostUsd,
      public float $outputCostUsd,
      public float $totalCostUsd,
      public int $inputTokens,
      public int $outputTokens,
      public string $model,
      public string $provider,
   ) {}

   public function totalCostCents(): float
   {
      return $this->totalCostUsd * 100;
   }
}
