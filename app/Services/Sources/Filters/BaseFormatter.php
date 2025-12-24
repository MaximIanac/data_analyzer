<?php

namespace App\Services\Sources\Filters;

use App\Models\Entity;
use App\Services\Sources\Contracts\FormatterInterface;
use stdClass;

abstract class BaseFormatter implements FormatterInterface
{
    /** Message header */
    protected string $header;

    /** Message body */
    protected string $body;

    /** Prepared data for rendering */
    protected stdClass $data;

    /** Changed fields */
    protected array $changes;

    /** Original values before update */
    protected array $original;

    /** Related entity */
    protected Entity $entity;

    /** Fields that should trigger notifications when entity was updated */
    protected array $watch = [];

    /**
     * @param Entity $entity   Source entity
     * @param array  $changes  Changed attributes
     * @param array  $original Original attributes
     */
    public function __construct(Entity $entity, array $changes = [], array $original = [])
    {
        $this->changes = $changes;
        $this->original = $original;
        $this->entity = $entity;
        $this->data = new stdClass();

        $this->processData()
            ->setHeader()
            ->setBody();
    }

    public static function make(Entity $entity): static
    {
        return new static($entity);
    }

    /**
     * Prepare formatter data.
     */
    abstract protected function processData(): static;

    /**
     * Get old/new values for a changed field.
     */
    protected function diff(string $field): ?stdClass
    {
        if (!array_key_exists($field, $this->changes)) {
            return null;
        }

        return (object)[
            'old' => $this->original[$field] ?? null,
            'new' => $this->changes[$field],
        ];
    }

    /**
     * Render text only if value exists.
     */
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

    /**
     * Format number with optional suffix.
     *
     * @param int|float|null $value
     * @param string $append
     * @return string|null
     */
    protected function number(int|float|null $value, string $append = ''): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return number_format($value, 0, '', ' ') . $append;
    }

    /**
     * Render field with change highlighting.
     */
    protected function changedField(string $field, mixed $current, string $text): ?string
    {
        $diff = $this->diff($field);

        if (!$diff) {
            return $this->addIf($current, "$text %s");
        }

        return sprintf(
            "$text ~~%s~~ → *%s*",
            $diff->old,
            $diff->new
        );
    }

    /**
     * Check if any watched fields were changed.
     */
    public function hasWatchedChanges(): bool
    {
        return (bool) array_intersect_key($this->changes, array_flip($this->watch));
    }

    /**
     * Get final formatted message.
     */
    public function get(): string
    {
        return
            "$this->header\n" .
            str_repeat('─', 12) . "\n\n" .
            "$this->body";
    }
}
