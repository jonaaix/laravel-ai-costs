<?php

declare(strict_types=1);

namespace Aaix\LaravelAiCosts\Concerns;

use Aaix\LaravelAiCosts\DTO\AiCostResult;
use Aaix\LaravelAiCosts\Services\AiCostCalculator;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Laravel\Ai\Responses\AgentResponse;

trait TracksAiCost
{
   use Promptable {
      prompt as private promptWithoutTracking;
   }

   /** @var AiCostResult[] */
   protected array $aiCosts = [];

   public function prompt(
      string $prompt,
      array $attachments = [],
      Lab|array|string|null $provider = null,
      ?string $model = null,
      ?int $timeout = null,
   ): AgentResponse {
      $response = $this->promptWithoutTracking($prompt, $attachments, $provider, $model, $timeout);

      $this->aiCosts[] = AiCostCalculator::fromResponse($response);

      return $response;
   }

   public function lastCost(): ?AiCostResult
   {
      return $this->aiCosts[count($this->aiCosts) - 1] ?? null;
   }

   /**
    * @return AiCostResult[]
    */
   public function costs(): array
   {
      return $this->aiCosts;
   }

   public function totalCostUsd(): float
   {
      return array_sum(array_map(fn (AiCostResult $c) => $c->totalCostUsd, $this->aiCosts));
   }

   public function resetCosts(): void
   {
      $this->aiCosts = [];
   }
}
