<?php

namespace App\Services\Sources\Filters\Factories;


class VariableFactory extends BaseFactory
{
    protected function getClassSuffix(): string
    {
        return 'Variables';
    }

    protected function getSubDirectory(): string
    {
        return 'Filters\\Variables';
    }

    protected function getExpectedInterface(): string
    {
        return '';
    }
}
