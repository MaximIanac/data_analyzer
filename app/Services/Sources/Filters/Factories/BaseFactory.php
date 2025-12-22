<?php

namespace App\Services\Sources\Filters\Factories;

use App\Services\Sources\Enums\SourceClientType;
use Illuminate\Support\Str;
use InvalidArgumentException;

abstract class BaseFactory
{
    protected string $baseNamespace = 'App\\Services\\Sources\\Clients';

    abstract protected function getClassSuffix(): string;
    abstract protected function getSubDirectory(): string;
    abstract protected function getExpectedInterface(): string;

    public function make(
        SourceClientType $sourceType,
        string $entityName,
        ...$params
    ): object
    {
        $className = $this->buildClassName($sourceType, $entityName);

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class {$className} does not exist.");
        }

        $instance = new $className(...$params);

        $this->validateInterface($instance);

        return $instance;
    }

    protected function buildClassName(
        SourceClientType $sourceType,
        string $entityName
    ): string
    {
        $sourceClassName = $this->formatSourceName($sourceType);
        $entityClassName = $this->formatEntityName($entityName);

        $subDir = $this->getSubDirectory() ? $this->getSubDirectory() . '\\' : '';

        return sprintf(
            '%s\\%s\\%s%s',
            $this->baseNamespace,
            $sourceClassName,
            $subDir,
            $entityClassName
        );
    }

    protected function formatSourceName(SourceClientType $type): string
    {
        return Str::of($type->value)
            ->studly()
            ->value();
    }

    protected function formatEntityName(string $entityName): string
    {
        return Str::of($entityName)
            ->studly()
            ->append($this->getClassSuffix())
            ->value();
    }

    protected function validateInterface(object $instance): void
    {
        $expectedInterface = $this->getExpectedInterface();

        if ($expectedInterface !== '' && !($instance instanceof $expectedInterface)) {
            throw new InvalidArgumentException(
                "Class must implement {$expectedInterface}"
            );
        }
    }
}
