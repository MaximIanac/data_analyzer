<?php

namespace App\Listeners;

use App\Events\EntityUpdated;
use App\Jobs\SendMessageToTelegram;
use App\Services\Sources\Filters\Factories\FormatterFactory;

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

        $formatter = (new FormatterFactory())->make(
            $entity->source,
            $entity->filter_type->value,
            $entity,
            $changes,
            $original,
        );

        $message = $formatter->get();

        SendMessageToTelegram::dispatch($message);
    }
}
