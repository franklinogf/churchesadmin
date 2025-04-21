<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

final class OfferingType extends Model
{
    /** @use HasFactory<\Database\Factories\OfferingTypeFactory> */
    use HasFactory, HasTranslations;

    protected $translatable = ['name'];
}
