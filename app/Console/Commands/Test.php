<?php

namespace App\Console\Commands;

use App\Models\Entity;
use App\Services\Pipelines\EntityProcessing\FilterDuplicatesPipe;
use App\Services\Pipelines\EntityProcessing\StoreEntitiesPipe;
use App\Services\Sources\Clients\Marketplace999\Actions\SearchFlatsAction;
use App\Services\Sources\Clients\Marketplace999\Marketplace999Client;
use App\Services\Sources\Configs\Marketplace999Config;
use App\Services\Sources\Data\EntityData;
use App\Services\Sources\Drivers\GraphQLDriver;
use App\Services\Sources\Enums\EntityFilter;
use Illuminate\Console\Command;
use Illuminate\Pipeline\Pipeline;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $entities = Entity::where('external_id', 103114971)->orWhere('external_id', 103101983)->get();

        $entitiesData = EntityData::collect($entities);
        $fieldsToCheck = (new Marketplace999Config())->getFieldsToCheck(EntityFilter::FLAT_DEFAULT);

        app(Pipeline::class)
            ->send($entitiesData)
            ->through([
                FilterDuplicatesPipe::make($fieldsToCheck),
                StoreEntitiesPipe::class,
            ])
            ->thenReturn();


        (new SearchFlatsAction())->handle();
    }
}
