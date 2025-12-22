<?php

namespace App\Services\Sources\Filters;

use App\Models\Entity;
use App\Services\Sources\Contracts\FormatterInterface;
use stdClass;

abstract class BaseFormatter implements FormatterInterface
{
    protected string $header;
    protected string $body;
    protected stdClass $data;
    protected Entity $entity;

    public function __construct(Entity $entity)
    {
        $this->data = new stdClass();
        $this->entity = $entity;

        $this->processData()
            ->setHeader()
            ->setBody();
    }

    public static function make(Entity $entity): static
    {
        return new static($entity);
    }

    abstract protected function processData(): static;


    public function get(): string
    {
        return
            "$this->header\n" .
            str_repeat('─', 12) . "\n\n" .
            "$this->body";
    }

    protected function addIf(int|float|string|array|null $value, string $text): string
    {
        if (is_null($value)) {
            return '';
        }

        if (is_array($value)) {
            if (in_array(null, $value, true) || in_array('', $value, true)) {
                return '';
            }

            return vsprintf($text, $value);
        }

        return sprintf($text, $value);
    }
}
