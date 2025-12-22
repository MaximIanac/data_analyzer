<?php

namespace App\Services\Sources\Contracts;

use App\Models\Entity;

interface FormatterInterface
{
    public static function make(Entity $entity): static;
    public function setHeader(...$params): static;
    public function setBody(...$params): static;
    public function get(): string;

}
