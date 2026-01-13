<?php

namespace App\Services\Sources\Clients\Marketplace999\Actions\Metrics\PPM;

use App\Models\Entity;
use App\Services\Repository\EntityRepository;
use App\Services\Sources\Enums\EntityFilter;
use App\Services\Sources\Enums\SourceClientType;

class CalcAveragePPMAction
{
    public function handle(string $whereValue): float
    {
        $context = Entity::whereSource(SourceClientType::MARKETPLACE999)
            ->whereFilterType(EntityFilter::FLAT_DEFAULT);

        return (new EntityRepository())->getAvgByField(
            $context,
            "pricePerMeter",
            "rooms", $whereValue,
        );
    }
}
