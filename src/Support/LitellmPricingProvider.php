<?php

declare(strict_types=1);

namespace Aaix\LaravelAiCosts\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final class LitellmPricingProvider
{
    /**
     * Look up pricing for a model (per 1M tokens).
     *
     * @return array{input: float, output: float}|null
     */
    public static function getPricing(string $model): ?array
    {
        $index = Cache::remember(
            'ai-costs:litellm-index',
            (int) config('ai-costs.litellm.cache_ttl', 86400),
            fn () => self::fetchAndBuildIndex(),
        );

        return $index[$model] ?? null;
    }

    public static function clearCache(): void
    {
        Cache::forget('ai-costs:litellm-index');
    }

    /**
     * @return array<string, array{input: float, output: float}>
     */
    private static function fetchAndBuildIndex(): array
    {
        $url = config('ai-costs.litellm.url');

        $response = Http::timeout(15)->get($url);

        if ($response->failed()) {
            return [];
        }

        return self::buildIndex($response->json());
    }

    /**
     * @return array<string, array{input: float, output: float}>
     */
    private static function buildIndex(array $raw): array
    {
        $index = [];

        foreach ($raw as $key => $data) {
            if (! isset($data['input_cost_per_token'], $data['output_cost_per_token'])) {
                continue;
            }

            if ($data['input_cost_per_token'] == 0 && $data['output_cost_per_token'] == 0) {
                continue;
            }

            $index[$key] = [
                'input' => (float) $data['input_cost_per_token'] * 1_000_000,
                'output' => (float) $data['output_cost_per_token'] * 1_000_000,
            ];
        }

        return $index;
    }
}
