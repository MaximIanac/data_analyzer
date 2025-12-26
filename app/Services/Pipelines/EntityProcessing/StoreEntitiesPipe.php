<?php

namespace App\Services\Pipelines\EntityProcessing;

use App\Services\Repository\EntityRepository;
use App\Services\Sources\Data\EntityData;
use Closure;
use Illuminate\Support\Collection;

class StoreEntitiesPipe
{
    /**
     * @param Collection<EntityData> $entities
     * @param Closure $next
     * @return mixed
     */
    public function handle(Collection $entities, Closure $next): mixed
    {
        (new EntityRepository())->storeMany($entities);

        return $next($entities);
    }
}
