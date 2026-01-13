<?php

namespace App\Services\Sources\Filters\Factories;

use App\Services\Sources\Contracts\FormatterInterface;
use App\Services\Sources\Enums\EntityFilter;
use App\Services\Sources\Enums\MetricFilter;
use InvalidArgumentException;

class FormatterFactory extends BaseFactory
{
    protected function getClassSuffix(): string
    {
        return 'Formatter';
    }

    protected function getSubDirectory(EntityFilter|MetricFilter $filter = null): string
    {
        if (is_null($filter)) {
            throw new InvalidArgumentException('Filter needs to be defined');
        }

        return 'Filters\\Formatters\\' . (
            $filter instanceof MetricFilter
                ? "Metric"
                : "Entity"
            );
    }

    protected function getExpectedInterface(): string
    {
        return FormatterInterface::class;
    }
}
