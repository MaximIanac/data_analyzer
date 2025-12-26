<?php

namespace App\Services\Sources\Contracts;

use App\Services\Sources\Enums\EntityFilter;

interface ConfigInterface
{
    public function get(string $key, $default = null);
    public function set(string $key, $value): void;
    public function all(): array;
    public function getFieldsToCheck(EntityFilter $filter): array;
}
