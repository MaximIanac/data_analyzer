<?php

namespace App\Services\Sources\Filters\Factories;


use App\Services\Sources\Enums\EntityFilter;
use App\Services\Sources\Enums\MetricFilter;

class VariableFactory extends BaseFactory
{
    protected function getClassSuffix(): string
    {
        return 'Variables';
    }

    protected function getSubDirectory(EntityFilter|MetricFilter $filter = null): string
    {
        return 'Filters\\Variables';
    }

    protected function getExpectedInterface(): string
    {
        return '';
    }
}
