<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Skill model.
 *
 * @property-read int $id
 * @property-read string $name
 * @property-read \Carbon\CarbonImmutable|null $created_at
 * @property-read \Carbon\CarbonImmutable|null $updated_at
 */
final class Skill extends Model
{
    /** @use HasFactory<\Database\Factories\SkillFactory> */
    use HasFactory;

    public $timestamps = false;
}
