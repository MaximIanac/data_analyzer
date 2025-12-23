<?php

namespace App\Console\Commands\Sources;

use App\Services\Sources\Clients\Marketplace999\Actions\SearchFlatsAction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sources:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command runs all available sources to fetch data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('▶ Starting sources run');

        try {
            $this->line('• Running flats source...');

            (new SearchFlatsAction())->handle();

            $this->info('✔ Flats source finished successfully');
        } catch (\Throwable $e) {
            $this->error('✖ Flats source failed');
            Log::error('[sources] Flats source error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        $this->info('■ Sources run completed');
    }
}
