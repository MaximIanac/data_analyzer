<?php

namespace App\Listeners;

use App\Events\EntityUpdated;
use App\Jobs\SendMessageToTelegram;
use App\Services\Sources\Clients\Marketplace999\Filters\Formatters\FlatDefaultFormatter;
use App\Services\Sources\Filters\Factories\FormatterFactory;
use Illuminate\Support\Facades\Log;

class SendUpdatedEntityListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EntityUpdated $event): void
    {
        $entity = $event->entity;
        $changes = $event->changes;
        $original = $event->original;

        /** @var FlatDefaultFormatter $formatter */
        $formatter = (new FormatterFactory())->make(
            $entity->source,
            $entity->filter_type->value,
            $entity,
            $changes,
            $original,
        );

        if (!$formatter->hasWatchedChanges()) {
            return;
        }

        Log::channel('sources.entity')->debug('[{source}][{filter_type}] Watched changes were fixed', [
            'entity_id'   => $entity->id,
            'source'      => $entity->source ?? null,
            'filter_type' => $entity->filter_type ?? null,
            'changes'     => $entity->getChanges(),
        ]);

        $message = $formatter->get();

        SendMessageToTelegram::dispatch($message);
    }
}
