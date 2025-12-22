<?php

namespace App\Services\Sources\Clients\Marketplace999\Filters\Formatters;

use App\Services\Sources\Filters\BaseFormatter;

class FlatDefaultFormatter extends BaseFormatter
{
    protected function processData(): static
    {
        $entityData = $this->entity->data;

        $this->data = new \stdClass();
        $this->data->price = '0 €';
        $this->data->oldPrice = null;
        $this->data->perMeter = null;
        $this->data->area = null;
        $this->data->discountPercent = null;
        $this->data->discountAmount = null;
        $this->data->timeText = "🆕 *Добавлено*";


        $this->data->price = number_format($entityData->price, 0, '', ' ') . ' €';
        $this->data->oldPrice = !empty($entityData->oldPrice)
            ? number_format($entityData->oldPrice, 0, '', ' ') . ' €'
            : null;
        $this->data->perMeter = $entityData->has("pricePerMeter")
            ? number_format((int)$entityData->pricePerMeter, 0, '', ' ') . ' €/м²'
            : null;
        $this->data->area = $entityData->price && $entityData->pricePerMeter
            ? round($entityData->price / $entityData->pricePerMeter)
            : null;
        if ($this->data->oldPrice && $this->data->price < $this->data->oldPrice) {
            $this->data->discountPercent = round((1 - $entityData->price / $entityData->oldPrice) * 100);
            $this->data->discountAmount = number_format($entityData->oldPrice - $entityData->price, 0, '', ' ');
        }
        $this->data->timeText = $this->entity->data->reseted ? "🔄 *Обновлено*" : "🆕 *Добавлено*";

        return $this;
    }

    public function setHeader(...$params): static
    {
        $statusEmoji = $this->data->discountPercent
            ? '🔥'
            : $this->data->timeText;

        $title = mb_strlen($this->entity->title) > 100
            ? mb_substr($this->entity->title, 0, 100) . '…'
            : $this->entity->title;

        $this->header = "$statusEmoji *{$title}*\n";
        $this->header .= $this->addIf($this->data->area, "*%s* | ") . "*ID:* `{$this->entity->external_id}`";

        return $this;
    }

    public function setBody(...$params): static
    {
        $this->body = "💰 *Цена:* {$this->data->price}\n";
        $this->body .= $this->addIf($this->data->perMeter, "📐 *За м²:* %s\n");
        $this->body .= $this->addIf($this->data->oldPrice, "📉 *Было:* %s\n");
        $this->body .= $this->addIf(
            [$this->data->discountPercent, $this->data->discountAmount],
            "🔥 *Скидка %s%%* (-%s €)\n\n"
        );

        $this->body .= $this->addIf($this->entity->data->rooms, "🏠 %s");
        $this->body .= $this->addIf(
            [$this->entity->data->floor, $this->entity->data->totalFloor],
            "  •  *Этаж:* %s/%s\n\n"
        );

        $this->body .= "🔗 [Открыть объявление]({$this->entity->data->url})\n";
        $this->body .= "{$this->data->timeText}: " . now()->format('d.m H:i');

        return $this;
    }
}
