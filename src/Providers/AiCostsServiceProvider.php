<?php

declare(strict_types=1);

namespace Aaix\LaravelAiCosts\Providers;

use Illuminate\Support\ServiceProvider;

class AiCostsServiceProvider extends ServiceProvider
{
   public function register(): void
   {
      $this->mergeConfigFrom(__DIR__ . '/../../config/ai-costs.php', 'ai-costs');
   }

   public function boot(): void
   {
      if ($this->app->runningInConsole()) {
         $this->publishes([
            __DIR__ . '/../../config/ai-costs.php' => config_path('ai-costs.php'),
         ], 'ai-costs-config');
      }
   }
}
