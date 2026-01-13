<?php

namespace App\Console\Commands;

use App\Models\Entity;
use App\Models\Metric;
use App\Services\Repository\EntityRepository;
use App\Services\Repository\MetricRepository;
use App\Services\Sources\Enums\EntityFilter;
use App\Services\Sources\Enums\MetricKey;
use App\Services\Sources\Enums\SourceClientType;
use Illuminate\Console\Command;

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
        $context = Entity::whereSource(SourceClientType::MARKETPLACE999)
            ->whereFilterType(EntityFilter::FLAT_DEFAULT);

        $res = (new EntityRepository())->getAvgByField(
            $context,
            "pricePerMeter",
            "rooms", "Apartament cu 2 camere",
        );

        $metric = Metric::create([
            "source" => SourceClientType::MARKETPLACE999,
            "filter_type" => EntityFilter::FLAT_DEFAULT,
            "key" => MetricKey::FLAT_AVG_PPM_2ROOMS,
            "value" => $res,
        ]);
    }
}
