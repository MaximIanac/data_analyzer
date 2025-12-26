<?php

namespace App\Services\Sources\Repository;

use App\Models\Entity;
use App\Services\Sources\Data\EntityData;
use Illuminate\Support\Collection;

class EntityRepository
{
    /**
     * @param Collection<EntityData> $entities
     * @return void
     */
    public function storeMany(Collection $entities): void
    {
        foreach ($entities as $data) {
            $entityModel = Entity::where('external_id', $data->external_id)
                ->where('source', $data->source)
                ->first();

            if (!$entityModel) {
                Entity::create($data->toArray());
                continue;
            }

            $entityModel->fill($data->toArray());

            if ($entityModel->isDirty()) {
                $entityModel->save();
            }

//            Entity::updateOrCreate(
//                [
//                    'external_id' => $entity->external_id,
//                    'source' => $entity->source
//                ],
//                $entity->toArray()
//            );
        }
    }

}
