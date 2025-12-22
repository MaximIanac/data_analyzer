<?php

namespace App\Services\Sources\Filters\Factories;

use App\Services\Sources\Contracts\FormatterInterface;

class FormatterFactory extends BaseFactory
{
    protected function getClassSuffix(): string
    {
        return 'Formatter';
    }

    protected function getSubDirectory(): string
    {
        return 'Filters\\Formatters';
    }

    protected function getExpectedInterface(): string
    {
        return FormatterInterface::class;
    }
}
