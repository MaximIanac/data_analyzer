<?php

namespace App\Models;

use App\Services\Sources\Enums\SourceClientType;
use App\Services\Sources\Enums\EntityFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Entity extends Model
{
    protected $guarded = [];

    protected $casts = [
        "source" => SourceClientType::class,
        "filter_type" => EntityFilter::class,
        "data" => SchemalessAttributes::class,
    ];
}
