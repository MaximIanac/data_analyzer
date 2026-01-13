<?php

namespace App\Services\Repository;

use App\Models\Metric;
use App\Services\Sources\Enums\MetricKey;

class MetricRepository
{
    /**
     * @param array $context
     * @param MetricKey $key
     * @param float $value
     * @return Metric
     */
    public function createMetric(array $context, MetricKey $key, float $value): Metric
    {
        $dayAgo = now()->subDay();

        $metric = Metric::where($context)
            ->where('key', $key)
            ->where('created_at', '>=', $dayAgo)
            ->latest()
            ->first();

        if ($metric) {
            $metric->update([
                ...$context,
                'key' => $key,
                'value' => $value,
            ]);

            return $metric;
        }

        return Metric::create([
            ...$context,
            'key' => $key,
            'value' => $value,
        ]);
    }
}
